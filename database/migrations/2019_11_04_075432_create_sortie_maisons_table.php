<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortieMaisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sortie_maisons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('numero')->unique();
            $table->string('nomClient');
            $table->string('contactClient');
            $table->integer('quantiteTotale');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->timestamp('sortie_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sortie_maisons');
    }
}
