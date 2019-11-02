<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentePorteursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_porteurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vente_id');
            $table->unsignedBigInteger('porteur_id');
            $table->boolean('isChecked')->default(false);

            $table->foreign('porteur_id')
            ->references('id')
            ->on('porteurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('vente_id')
            ->references('id')
            ->on('ventes')
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
        Schema::dropIfExists('vente_porteurs');
    }
}
