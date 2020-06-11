<?php

namespace App\OtherSettings;

use App\Constants\OtherSettingsNames;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtherSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = OtherSetting::where('user_id', auth()->user()->id)
                ->orderBy('name')
                ->get();

            return $this->successfullResponse($data);
        } catch (\Exception $e) {
            return $this->errorResponse('Greška', $e);
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $entity = Relation::findOrFail($id);
    //         if ($entity->user_id != auth()->user()->id)
    //             return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

    //         $numberOfOtherEmployeesWithSameRelationName = auth()->user()
    //             ->relations
    //             ->filter(function ($ent) use ($entity, $request) {
    //                 return $ent->id != $entity->id
    //                     && $ent->name == $request['name'];
    //             })->count();;

    //         if ($numberOfOtherEmployeesWithSameRelationName > 0)
    //             return $this->failWithMessage('Već postoji relacija sa tim nazivom');

    //         $entity->name = $request['name'];
    //         $entity->price = $request['price'];
    //         $entity->save();

    //         return $this->successfullResponse();
    //     } catch (\Exception $e) {
    //         return $this->errorResponse('Greška prilikom snimanja podataka u bazu', $e);
    //     }
    // }
}
