<?php

namespace App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee\Employee;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getMissingJmbgs(Request $request)
    {
        try {
            $jmbgs = $request['jmbgs'];
            $existingjmbgs = Employee::where('user_id', auth()->user()->id)
                ->pluck('jmbg')
                ->toArray();

            $missing = [];

            foreach ($jmbgs as $maybeNew) {
                if (in_array($maybeNew, $existingjmbgs))
                    continue;
                array_push($missing, $maybeNew);
            }


            return $this->successfullResponse($missing);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function getMissingEmployeeNumbers(Request $request)
    {
        try {
            $numbers = $request['numbers'];
            $existingNumbers = Employee::where('user_id', auth()->user()->id)
                ->pluck('number')
                ->toArray();

            $missing = [];

            foreach ($numbers as $maybeNew) {
                if (in_array($maybeNew, $existingNumbers))
                    continue;
                array_push($missing, $maybeNew);
            }


            return $this->successfullResponse($missing);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }
}
