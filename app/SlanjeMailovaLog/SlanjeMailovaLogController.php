<?php

namespace App\SlanjeMailovaLog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlanjeMailovaLogController extends Controller
{
    public function log(Request $request)
    {
        try {
            $entity = new SlanjeMailovaLog($request->all());
            $entity->user_email =  auth()->user()->email;
            $entity->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu!', $e);
        }
    }

}
