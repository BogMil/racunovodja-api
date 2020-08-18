<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLokacijaSkoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lokacije_skole', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table
                ->foreignId('id_korisnika')
                ->references('id')
                ->on('korisnici');
            $table->string('naziv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lokacija_skole');
    }
}
