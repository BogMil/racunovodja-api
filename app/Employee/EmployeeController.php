<?php

namespace App\Employee;

use App\Core\Responses\Success;
use App\Employee\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Core\Responses\Error;
use App\Core\Responses\Fail;
use Illuminate\Http\Request;

class EmployeeController extends Controller
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
        $employees = Employee::where('user_id', auth()->user()->id)
            ->with('municipality')
            ->with('defaultRelations')
            ->orderBy('active', 'desc')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return response()->json(new Success($employees));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
