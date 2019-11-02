<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGererEntreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerer_entrees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('magasinier_id');
            $table->unsignedBigInteger('entree_id');
            $table->unsignedTinyInteger('codeOperation');
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at')->nullable();

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('magasinier_id')
            ->references('id')
            ->on('magasiniers')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('entree_id')
            ->references('id')
            ->on('entrees')
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
        Schema::dropIfExists('gerer_entrees');
    }
}
