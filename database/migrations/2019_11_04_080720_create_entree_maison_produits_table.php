<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntreeMaisonProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entree_maison_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entree_id')->nullable();
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite');
            $table->timestamp('created_at');
            $table->softDeletes();

            $table->foreign('entree_id')
            ->references('id')
            ->on('entree_maisons')
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
        Schema::dropIfExists('entree_maison_produits');
    }
}
