<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGererAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gerer_admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin1_id');
            $table->unsignedBigInteger('admin2_id');
            $table->unsignedTinyInteger('codeOperation');
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
        Schema::dropIfExists('gerer_admins');
    }
}
