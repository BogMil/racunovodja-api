<?php

namespace App\Lokacija;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LokacijaController extends Controller
{
    public function index()
    {
        try {
            $data = Lokacija::where('user_id', auth()->user()->id)
                ->orderBy('naziv')
                ->get();

            return $this->successfullResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    public function store(Request $request)
    {
        try {
            $entity = new Lokacija($request->all());

            $postojeLokacijeSaIstimImenom = auth()->user()
                ->lokacije
                ->filter(function ($rel) use ($entity) {
                    return $rel->naziv == $entity->naziv;
                })->count() > 0;

            if ($postojeLokacijeSaIstimImenom)
                return $this->failWithMessage('Lokacija već postoji u bazi!');

            $entity->user_id = auth()->user()->id;
            $entity->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu!', $e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $entity = Lokacija::findOrFail($id);
            if ($entity->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $postojeLokacijeSaIstimImenom = auth()->user()
                ->lokacije
                ->filter(function ($ent) use ($entity, $request) {
                    return $ent->id != $entity->id
                        && $ent->naziv == $request['naziv'];
                })->count() > 0;

            if ($postojeLokacijeSaIstimImenom )
                return $this->failWithMessage('Već postoji lokacija sa tim nazivom');

            $entity->naziv = $request['naziv'];
            $entity->save();

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }

    public function destroy($id)
    {
        try {
            $entity = Lokacija::findOrFail($id);
            if ($entity->user_id == auth()->user()->id) {

                $entity->delete();
                return $this->successfullResponse();
            } else {
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }
}
