<?php

namespace App\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Relation::where('user_id', auth()->user()->id)
                ->orderBy('name')
                ->with('lokacija')
                ->get();

            return $this->successfullResponse($data);
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
            $entity = new Relation($request->all());

            $currentUserAlreadyHasRelation = auth()->user()
                ->relations
                ->filter(function ($rel) use ($entity) {
                    return $rel->name == $entity->name;
                })->count() > 0;

            if ($currentUserAlreadyHasRelation)
                return $this->failWithMessage('Relacija već postoji u bazi!');

            $entity->user_id = auth()->user()->id;
            $entity->save();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->errorResponse('Greška prilikom snimanja podataka u bazu!', $e);
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
            $entity = Relation::findOrFail($id);
            if ($entity->user_id != auth()->user()->id)
                return $this->failWithMessage('Nemate parava pristupa tuđim podacima');

            $numberOfOtherEmployeesWithSameRelationName = auth()->user()
                ->relations
                ->filter(function ($ent) use ($entity, $request) {
                    return $ent->id != $entity->id
                        && $ent->name == $request['name'];
                })->count();;

            if ($numberOfOtherEmployeesWithSameRelationName > 0)
                return $this->failWithMessage('Već postoji relacija sa tim nazivom');

            $entity->name = $request['name'];
            $entity->price = $request['price'];
            $entity->lokacija_id = $request['lokacija_id'];
            $entity->save();

            return $this->successfullResponse();
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
            $entity = Relation::findOrFail($id);
            if ($entity->user_id == auth()->user()->id) {

                if ($entity->defaultRelations->count() >= 0)
                    return $this->failWithMessage('Nije moguće obrisati relaciju jer je dodeljena kao podrazumevana nekom od zaposlenih.');

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
