<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZaposleniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zaposleni', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('aktivan')->default(true);
            $table->string('jmbg');
            $table->string('sifra');
            $table->string('prezime');
            $table->string('ime');
            $table->string('bankovni_racun');
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table
                ->foreignId('id_opstine')
                ->nullable()
                ->references('id')
                ->on('opstine');
            $table
                ->foreignId('id_korisnika')
                ->references('id')
                ->on('korisnici');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zaposleni');
    }
}
