<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseIdColumnToIndicators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indicators', function (Blueprint $table) {
            $table->integer('course_id')->nullable()->after('id');
            $table->foreign('course_id', 'fk_indicators_course')->references('id')->on('courses')->onUpdate('CASCADE')->onDelete('CASCADE');

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
            $table->dropForeign('fk_indicators_course');
            $table->dropColumn('course_id');
        });
    }
}
