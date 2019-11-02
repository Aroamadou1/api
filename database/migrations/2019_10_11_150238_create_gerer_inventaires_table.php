<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGererInventairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerer_inventaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('inventaire_id');
            $table->unsignedTinyInteger('codeOperation');
            $table->timestamp('created_at');

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
            ->onDelete('restrict')
            ->onUpdate('restrict');
            $table->foreign('inventaire_id')
            ->references('id')
            ->on('inventaires')
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
        Schema::dropIfExists('gerer_inventaires');
    }
}
