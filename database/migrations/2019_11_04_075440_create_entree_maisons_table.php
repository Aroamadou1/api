<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntreeMaisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entree_maisons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fournisseur_id')->nullable();
            $table->integer('numero')->nullable();
            $table->integer('quantiteTotale');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->timestamp('confirmed_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entree_maisons');
    }
}
