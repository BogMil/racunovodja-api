<?php

namespace App\Services;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\KorisnikRepository;
use App\Repositories\ZaposleniRepository;
use App\SlanjeMailaLog;
use App\Zaposleni;

class SlanjeMailovaService
{
    public function log($data)
    {
        $entity = new SlanjeMailaLog($data);
        $entity->email_korisnika = auth()->user()->email;
        $entity->save();
    }
}
