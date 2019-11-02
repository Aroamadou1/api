<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGererVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerer_ventes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('vendeur_id');
            $table->unsignedBigInteger('vente_id');
            $table->unsignedTinyInteger('codeOperation');
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at')->nullable();

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gerer_ventes');
    }
}
