<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortieProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sortie_produits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sortie_id');
            $table->unsignedBigInteger('magasinier_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('produit_id');
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->softDeletes();

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('sortie_id')
            ->references('id')
            ->on('sorties')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('magasinier_id')
            ->references('id')
            ->on('magasiniers')
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
        Schema::dropIfExists('sortie_produits');
    }
}
