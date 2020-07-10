<?php

namespace App\Employee;

use App\Core\Responses\Success;
use App\Employee\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Core\Responses\Error;
use App\Core\Responses\Fail;
use App\Relation\Relation;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $employees = Employee::where('user_id', auth()->user()->id)
                ->with('municipality')
                ->with('defaultRelations.lokacija')
                ->orderBy('active', 'desc')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
            return response()->json(new Success($employees));
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }


    public function getActiveOnes()
    {
        try {
            $employees = Employee::where('user_id', auth()->user()->id)
                ->with('municipality')
                ->with('defaultRelations')
                ->where('active', true)
                ->orderBy('active', 'desc')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
            return response()->json(new Success($employees));
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    public function store(Request $request)
    {
        try {
            $employee = new Employee($request->all());

            $currentUserAlreadyHasEmployee = auth()->user()
                ->employees
                ->filter(function ($emp) use ($employee) {
                    return $emp->number == $employee->number
                        || $emp->jmbg == $employee->jmbg;
                })
                ->count() > 0;

            if ($currentUserAlreadyHasEmployee)
                return response()->json(Fail::withMessage('Zaposleni sa navedenim jmbg-om ili brojem je već unet u bazu'));

            $employee->user_id = auth()->user()->id;
            if($employee->municipality_id<0)
                $employee->municipality_id=null;
            $employee->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json(new Error('Greška prilikom snimanja podataka u bazu'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
                $entity->email = $request['email'];

                $entity->banc_account = $request['banc_account'];
                $entity->municipality_id = $request['municipality_id'];
                if ($entity->municipality_id <= 0)
                    $entity->municipality_id = null;

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
            $employee = Employee::findOrFail($id);
            if ($employee->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $employee->delete();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function availableRelations($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            if ($employee->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $currentRelationsId = $employee->defaultRelations->pluck('id');

            $availableRelations =
                Relation::where('user_id', auth()->user()->id)
                ->with('lokacija')
                ->whereNotIn('id', $currentRelationsId)->get();

            return $this->successfullResponse($availableRelations);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function addDefaultRelation($employeeId, Request $request)
    {
        try {
            $relationId = $request['relationId'];
            $employee = Employee::findOrFail($employeeId);
            if ($employee->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $employee->defaultRelations()->attach($relationId);

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function removeDefaultRelation($id, Request $request)
    {
        try {
            $relationId = $request['relationId'];

            $employee = Employee::findOrFail($id);
            if ($employee->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $employee->defaultRelations()->detach($relationId);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function updateEmail($jmbg, Request $request)
    {
        try {
            $email = $request['email'];

            $employee = Employee::where('jmbg',$jmbg)->firstOrFail();
            if ($employee->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $employee->email=$email;
            $employee->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

}
