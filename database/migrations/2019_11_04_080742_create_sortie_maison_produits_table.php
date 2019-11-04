<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortieMaisonProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sortie_maison_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sortie_id');
            $table->unsignedBigInteger('produit_id');
            $table->integer('quantite');
            $table->timestamp('created_at');
            $table->softDeletes();

        
            $table->foreign('sortie_id')
            ->references('id')
            ->on('sortie_maisons')
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
        Schema::dropIfExists('sortie_maison_produits');
    }
}
