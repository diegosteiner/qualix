<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements_indicators', function (Blueprint $table) {
            $table->integer('requirement_id');
            $table->integer('indicator_id');
            $table->primary(['requirement_id','indicator_id']);
            $table->foreign('requirement_id', 'fk_requirements_indicators_requirement')->references('id')->on('requirements')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('indicator_id', 'fk_requirements_indicators_indicator')->references('id')->on('indicators')->onUpdate('CASCADE')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requirements_indicators');
    }
}
