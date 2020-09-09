<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetaljiKorisnikaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalji_korisnika', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('poreski_identifikacioni_broj')->default('');
            $table->string('maticni_broj')->default('');
            $table
                ->foreignId('id_korisnika')
                ->references('id')
                ->on('korisnici');
            $table
                ->foreignId('id_opstine')
                ->nullable()
                ->default(null);
            $table->string('email_za_slanje')->default('');
            $table->string('password_email_za_slanje')->default('');
            $table->string('bankovni_racun')->default('');
            $table
                ->integer('tip_skole')
                ->nullable()
                ->default(null);
            $table->string('sifra_skole')->default('');
            $table->string('naziv_skole')->default('');
            $table->string('mesto')->default('');
            $table->string('ulica_i_broj')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalji_korisnika');
    }
}
