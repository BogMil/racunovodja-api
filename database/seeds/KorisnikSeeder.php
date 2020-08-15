<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KorisnikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('korisnici')->insert([
            'naziv' => 'Gimnazija i Ekonomska škola Branko Radičević',
            'email' => 'milanbogdanovic11@hotmail.com',
            'password' => Hash::make('asd'),
            'validan_do'=>(new \DateTime('now'))->modify('+6 month')->format('Y-m-d h:i:s'),
            'ulica_i_broj'=>'Cara Lazara 106',
            'grad'=>'Kovin',
        ]);

        DB::table('korisnici')->insert([
            'naziv' => 'Srednja strucna skola Vasa Pelagic',
            'email' => 'bogmilko@gmail.com',
            'password' => Hash::make('asd'),
            'validan_do'=>(new \DateTime('now'))->modify('+6 month')->format('Y-m-d h:i:s'),
            'ulica_i_broj'=>'Kralja Petra 260',
            'grad'=>'Smederevo',
        ]);
    }
}
