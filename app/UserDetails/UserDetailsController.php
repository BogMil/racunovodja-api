<?php

namespace App\UserDetails;

use App\Constants\OtherSettingsNames;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = UserDetails::where('user_id', auth()->user()->id)
                ->with('municipality')
                ->firstOrFail();

            return $this->successfullResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }


    public function update(Request $request)
    {
        try {
            $id=$request['id'];
            $entity = UserDetails::findOrFail($id);
            if ($entity->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $entity->poreski_identifikacioni_broj = $request['poreski_identifikacioni_broj'];
            $entity->maticni_broj = $request['maticni_broj'];
            $entity->naziv_skole = $request['naziv_skole'];
            $entity->opstina_id = $request['opstina_id'];
            $entity->telefon = $request['telefon'];
            $entity->ulica_i_broj = $request['ulica_i_broj'];
            $entity->email = $request['email'];
            $entity->save();

            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
        }
    }
}
