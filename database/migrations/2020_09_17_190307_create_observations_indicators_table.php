<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationsIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observations_indicators', function (Blueprint $table) {
            $table->integer('observation_id');
            $table->integer('indicator_id');
            $table->integer('impression');
            $table->primary(['observation_id','indicator_id']);
            $table->foreign('observation_id', 'fk_observations_observation_id')->references('id')->on('observations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('indicator_id', 'fk_indicators_indicator_id')->references('id')->on('indicators')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observations_indicators');
    }
}
