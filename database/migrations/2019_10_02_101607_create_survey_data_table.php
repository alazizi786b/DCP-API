<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyDataTable extends Migration
{

    //survery belongs to tac
    //tac has many surveyes
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('brand_name');
            $table->string('model_name');
            $table->string('shop_address');
            $table->string('web_address');
            $table->text('specifications');
            $table->text('description');
            $table->decimal('latitude', 28, 6)->nullable();
            $table->decimal('longitude', 28, 6)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_data');
    }
}
