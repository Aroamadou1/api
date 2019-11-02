<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValiderEntreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valider_entrees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('entree_id');
            $table->unsignedTinyInteger('codeOperation');
            $table->timestamp('created_at');

            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
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
        Schema::dropIfExists('valider_entrees');
    }
}
