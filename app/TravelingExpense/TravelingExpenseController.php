<?php

namespace App\TravelingExpense;

use App\Core\Responses\Success;
use App\Employee\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Core\Responses\Error;
use App\Core\Responses\Fail;
use App\Relation\Relation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            if ($te->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate prava');

            $x = TravelingExpense::where('id', $te->id)->with('employeesWithRelation')->get();

            return response()->json(new Success($x[0]));
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

            DB::transaction(function () use ($request) {

                $te = new TravelingExpense($request->all());
                $te->user_id = auth()->user()->id;
                $te->save();

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
                        $expenseRelation->days = 0;
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
