<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTacForeignKeyToSurveryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_data', function (Blueprint $table) {
            $table->unsignedBigInteger('tac_id');
            $table->foreign('tac_id')->references('id')->on('tacs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_data', function (Blueprint $table) {
            $table->dropForeign(['tac_id']);
        });
    }
}
