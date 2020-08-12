<?php

namespace App\Dobavljac;

use App\Core\Responses\Success;
use App\Employee\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Core\Responses\Error;
use App\Core\Responses\Fail;
use App\Relation\Relation;
use Illuminate\Http\Request;

class DobavljacController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $rows = Dobavljac::where('user_id', auth()->user()->id)
                ->orderBy('naziv')
                ->get();
            return response()->json(new Success($rows));
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    public function store(Request $request)
    {
        try {
            $entity = new Dobavljac($request->all());

            $entity->user_id = auth()->user()->id;
            $entity->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            Log::critical($e->getMessage());
            return response()->json(new Error('Greška prilikom snimanja podataka u bazu'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $entity = Dobavljac::findOrFail($id);

            if ($entity->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $entity->adresa = $request['adresa'];
            $entity->naziv = $request['naziv'];
            $entity->pib = $request['pib'];
            $entity->ziro_racun = $request['ziro_racun'];
            $entity->save();

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }
}
