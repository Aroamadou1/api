<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventaireProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaire_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inventaire_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantiteStock');
            $table->integer('quantiteReel');
            $table->integer('quantiteCompte');
            $table->boolean('isAjusted')->nullable();
            $table->timestamp('created_at');
            $table->softDeletes();

            $table->foreign('inventaire_id')
            ->references('id')
            ->on('inventaires')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('produit_id')
            ->references('id')
            ->on('produits')
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
        Schema::dropIfExists('inventaire_produits');
    }
}
