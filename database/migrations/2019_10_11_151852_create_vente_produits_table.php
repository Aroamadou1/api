<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenteProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vente_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('vendeur_id');
            $table->unsignedBigInteger('sortie_id')->nullabe();
            $table->unsignedBigInteger('produit_id');
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->softDeletes();

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('vendeur_id')
            ->references('id')
            ->on('vendeurs')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('vente_id')
            ->references('id')
            ->on('ventes')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('sortie_id')
            ->references('id')
            ->on('sorties')
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
        Schema::dropIfExists('vente_produits');
    }
}
