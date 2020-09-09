<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlanjeMailaLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slanje_maila_log', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('email_korisnika');
            $table->string('subject');
            $table->string('vrsta');
            $table->boolean('uspesno');
            $table->string('greska')->nullable();
            $table->string('naziv_skole_iz_fajla');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slanje_maila_log');
    }
}
