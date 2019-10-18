<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsmadatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsmadatas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('gsma_device_id');
            $table->string('gsma_model_name');
            $table->string('gsma_brand_name');
            $table->string('gsma_marketing_name');
            $table->bigInteger('tac_id');

            $table->foreign('tac_id')->references('id')->on('tacs')->onDelete('cascade');
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
        Schema::dropIfExists('gsmadatas');
    }
}
