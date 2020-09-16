<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KreirajPravaPristupaTabelu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prava_pristupa', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('dpl')->default(true);
            $table->boolean('opiro')->default(true);
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
        Schema::table('prava_pristupa', function (Blueprint $table) {
            //
        });
    }
}
