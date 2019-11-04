<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValiderSortiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valider_sorties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('magasinier_id');
            $table->unsignedBigInteger('sortie_id');
            $table->timestamp('created_at');

            $table->foreign('magasinier_id')
            ->references('id')
            ->on('magasiniers')
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
        Schema::dropIfExists('valider_sorties');
    }
}
