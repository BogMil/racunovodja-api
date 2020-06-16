<?php

namespace App\OsnoviceIStopePorezaIDoprinosa;

use App\Constants\VrstePrimanja;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class OSPDVrstePrimanjaService
{

    public function naknadaTroskovaZaDolazakIOdlazakSaRada($month, $year)
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);

        $value = VrstaPrimanja::where('naziv', VrstePrimanja::NAKNADA_TROSKOVA_ZA_DOLAZAK_I_ODLAZAK_SA_RADA)
            ->first()
            ->vrednosti()
            ->where('od', '<=', $date->format('Y-m-d'))
            ->where(function ($q) use ($date) {
                $q->where('do', '>=', $date->format('Y-m-d'))
                    ->orWhere('do', null);
            })
            ->first();
        return $value;
    }
}
