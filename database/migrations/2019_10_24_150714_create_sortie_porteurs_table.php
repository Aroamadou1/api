<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortiePorteursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sortie_porteurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sortie_id');
            $table->unsignedBigInteger('porteur_id');
            $table->boolean('isChecked')->default(false);

            $table->foreign('porteur_id')
            ->references('id')
            ->on('porteurs')
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
        Schema::dropIfExists('sortie_porteurs');
    }
}
