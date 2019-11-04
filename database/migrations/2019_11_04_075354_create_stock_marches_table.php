<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMarchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_marches', function (Blueprint $table) {
            $table->unsignedBigInteger('produit_id')->unique();
            $table->integer('quantiteStock')->default(0);
            $table->integer('quantiteReel')->default(0);
            $table->boolean('isChecking')->default(0);

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
        Schema::dropIfExists('stock_marches');
    }
}
