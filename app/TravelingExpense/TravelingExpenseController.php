<?php

namespace App\TravelingExpense;

use App\Constants\OtherSettingsNames;
use App\Constants\Statuses;
use App\Core\Responses\Success;
use App\Employee\Employee;
use App\Http\Controllers\Controller;
use App\OsnoviceIStopePorezaIDoprinosa\OSPDVrstePrimanjaService;
use App\Relation\Relation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\OtherSettings\OtherSetting;

class TravelingExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $te = TravelingExpense::where('user_id', auth()->user()->id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
            return response()->json(new Success($te));
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }


    public function details($id)
    {
        try {
            $te = TravelingExpense::findOrFail($id);
            if($te->status==Statuses::ZAVRSEN){
                $details=json_decode ($te->details);
                return $this->successfullResponse($details);
            }

            if ($te->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate prava');

            $travelingExpenseDetails  = TravelingExpense::where('id', $te->id)->with('employeesWithRelation')->get()[0];

            $OSPDVrstePrimanjaService = new OSPDVrstePrimanjaService();
            $ospd = $OSPDVrstePrimanjaService->naknadaTroskovaZaDolazakIOdlazakSaRada($travelingExpenseDetails->month, $travelingExpenseDetails->year);
            $travelingExpenseDetails->maxNonTaxedValue = $ospd->neoporezivo;
            $travelingExpenseDetails->preracun_na_bruto = $ospd->preracun_na_bruto;
            $travelingExpenseDetails->stopa = $ospd->stopa;
            return response()->json(new Success($travelingExpenseDetails));
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    public function lock($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $te = TravelingExpense::findOrFail($id);
                if ($te->user_id != auth()->user()->id)
                    return $this->failWithMessage('Nemate prava');

                $te->status = Statuses::ZAVRSEN;
                foreach ($te->travelingExpenseEmployees as $employee) {
                    if (count($employee->relationsWithDays) == 0)
                        $employee->delete();
                }

                $te->save();
                $te = TravelingExpense::findOrFail($id);

                $travelingExpenseDetails  = TravelingExpense::where('id', $te->id)->with('employeesWithRelation')->get()[0];
                $OSPDVrstePrimanjaService = new OSPDVrstePrimanjaService();
                $ospd = $OSPDVrstePrimanjaService->naknadaTroskovaZaDolazakIOdlazakSaRada($travelingExpenseDetails->month, $travelingExpenseDetails->year);
                $travelingExpenseDetails->maxNonTaxedValue = $ospd->neoporezivo;
                $travelingExpenseDetails->preracun_na_bruto = $ospd->preracun_na_bruto;
                $travelingExpenseDetails->stopa = $ospd->stopa;

                $te->details=$travelingExpenseDetails;
                $te->save();

            });
            return response()->json(new Success());
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $teWithSameMonthAndYear = TravelingExpense::where('month', $request['month'])
                ->where('year', $request['year'])
                ->get();
            if (count($teWithSameMonthAndYear) > 0)
                return $this->failWithMessage('Već postoji obračun za odabrani mesec i godinu.');

            DB::transaction(function () use ($request) {

                $te = new TravelingExpense($request->all());
                $te->user_id = auth()->user()->id;
                $te->save();

                $days = $this->countDays($te->year, $te->month, array(0, 6));

                $chosenEmployees = $request['employees'];

                foreach ($chosenEmployees as $employeeId) {

                    $employee = Employee::findOrFail($employeeId);
                    $tee = new TravelingExpenseEmployee();
                    $tee->employee_id = $employeeId;
                    $tee->traveling_expense_id = $te->id;
                    $tee->save();

                    foreach ($employee->defaultRelations as $relation) {
                        $expenseRelation = new TravelingExpenseEmployeeRelation();
                        $expenseRelation->relation_id = $relation->id;
                        $expenseRelation->days = $days;
                        $expenseRelation->traveling_expense_employee_id = $tee->id;
                        $expenseRelation->save();
                    }
                }
            });
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    function countDays($year, $month, $ignore)
    {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $entity = Employee::findOrFail($id);

            if ($entity->user_id == auth()->user()->id) {

                $otherEmployees = auth()->user()
                    ->employees
                    ->filter(function ($emp) use ($entity) {
                        return $emp->number != $entity->number
                            && $emp->jmbg != $entity->jmbg;
                    });

                $numberOfOtherEmployeesWithJmbgOrNumber = $otherEmployees->filter(function ($emp) use ($request) {
                    return $emp->number == $request['number']
                        || $emp->jmbg == $request['jmbg'];
                })->count();


                if ($numberOfOtherEmployeesWithJmbgOrNumber > 0)
                    return $this->failWithMessage('Već postoji zaposleni sa tim brojem ili jmbg-om');

                $entity->jmbg = $request['jmbg'];
                $entity->number = $request['number'];
                $entity->last_name = $request['last_name'];
                $entity->first_name = $request['first_name'];
                $entity->active = $request['active'];

                $entity->banc_account = $request['banc_account'];
                $entity->municipality_id = $request['municipality_id'];

                $entity->save();

                return $this->successfullResponse();
            } else {
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $entity = TravelingExpense::findOrFail($id);
            if ($entity->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $entity->delete();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function availableEmployees($id)
    {
        try {
            $te = TravelingExpense::findOrFail($id);
            if ($te->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $currentEmployeeIds = $te->travelingExpenseEmployees->pluck('employee_id');

            $availableEmployees =
                Employee::where('user_id', auth()->user()->id)
                ->where('active', true)
                ->whereNotIn('id', $currentEmployeeIds)->get();

            return $this->successfullResponse($availableEmployees);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function addEmployeeToTravelingExpense($travelingExpenseId, $employeeId)
    {
        try {

            DB::transaction(function () use ($employeeId, $travelingExpenseId) {
                $employee = Employee::findOrFail($employeeId);
                if ($employee->user_id != auth()->user()->id)
                    return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

                $tee = new TravelingExpenseEmployee();
                $tee->employee_id = $employeeId;
                $tee->traveling_expense_id = $travelingExpenseId;
                $tee->save();

                foreach ($employee->defaultRelations as $relation) {
                    $expenseRelation = new TravelingExpenseEmployeeRelation();
                    $expenseRelation->relation_id = $relation->id;
                    $expenseRelation->days = 0;
                    $expenseRelation->traveling_expense_employee_id = $tee->id;
                    $expenseRelation->save();
                }
            });
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function removeEmployeeWithRelations($employeeWithRelationsId)
    {
        try {
            $te = TravelingExpenseEmployee::findOrFail($employeeWithRelationsId);
            if ($te->travelingExpense->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            TravelingExpenseEmployee::destroy($employeeWithRelationsId);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function removeRelation($travelingExpenseRelationId)
    {
        try {
            $te = TravelingExpenseEmployeeRelation::findOrFail($travelingExpenseRelationId);
            if ($te->travelingExpenseEmployee->travelingExpense->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            TravelingExpenseEmployeeRelation::destroy($travelingExpenseRelationId);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function availableRelations($travelingExpenseEmployeeId)
    {
        try {
            $tee = TravelingExpenseEmployee::findOrFail($travelingExpenseEmployeeId);
            if ($tee->travelingExpense->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $currentRelationIds = $tee->relationsWithDays->pluck('relation_id');

            $available =
                Relation::where('user_id', auth()->user()->id)
                ->with('lokacija')
                ->whereNotIn('id', $currentRelationIds)->get();

            return $this->successfullResponse($available);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function addDaysToRelation($id, $days)
    {
        try {
            $teer = TravelingExpenseEmployeeRelation::findOrFail($id);
            if ($teer->travelingExpenseEmployee->travelingExpense->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $teer->days = $days;

            $teer->save();

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function addRelationWithDays($travelingExpenseEmployeeId, $relationId, $days)
    {
        try {
            $tee = TravelingExpenseEmployee::findOrFail($travelingExpenseEmployeeId);
            if ($tee->travelingExpense->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $teer = new TravelingExpenseEmployeeRelation();
            $teer->relation_id = $relationId;
            $teer->days = $days;
            $teer->traveling_expense_employee_id = $tee->id;
            $teer->save();

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }



}
