<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBilanMaisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bilan_maisons', function (Blueprint $table) {
            $table->unsignedBigInteger('categorieNom');
            $table->string('produitNom');
            $table->integer('quantiteStock')->default(0);
            $table->integer('quantiteReel')->default(0);
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bilan_maisons');
    }
}
