<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIndicatorRequirementIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('indicators', function (Blueprint $table) {
            $table->dropForeign('fk_requirements');
            $table->dropColumn('requirement_id');
            $table->dropColumn('mandatory');
            $table->date('created_at')->useCurrent()->change();
            $table->date('updated_at')->useCurrent()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->integer('mandatory');
            $table->integer('requirement_id')->nullable();
            $table->foreign('requirement_id', 'fk_indicators_requirements')->references('id')->on('requirements')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
}
