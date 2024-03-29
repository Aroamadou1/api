<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGererSortiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerer_sorties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendeur_id');
            $table->unsignedBigInteger('sortie_id');
            $table->unsignedTinyInteger('codeOperation');
            $table->timestamp('created_at');

            $table->foreign('vendeur_id')
            ->references('id')
            ->on('vendeurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('sortie_id')
            ->references('id')
            ->on('sorties')
            ->onDelete('restrict')
            ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gerer_sorties');
    }
}
