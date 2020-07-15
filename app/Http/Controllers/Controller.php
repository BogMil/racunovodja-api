<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Core\Responses\Success;
use App\Core\Responses\Fail;
use App\Core\Responses\Error;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successfullResponse($data = null)
    {
        return response()->json(new Success($data));
    }

    protected function unsuccessfullResponse($data = null)
    {
        return response()->json(new Success($data));
    }

    protected function failWithMessage($message)
    {
        return response()->json(Fail::withMessage($message));
    }

    protected function errorResponse($message, $e)
    {
        try{
            $m="-----------USER-----------\n";
            $m=$m. auth()->user()->email."\n";
            $m=$m."-----------USER ERROR-----------\n";
            $m=$m.$message."\n";
            $m=$m."-----------EXCEPTION-----------\n";
            $m=$m.$e->getMessage()."\n";

            Log::critical($m);
            return response()->json(new Error($message));
        }catch(\Exception $ex){
            Log::critical($e->getMessage());
            return response()->json(new Error($message));
        }

    }
}
