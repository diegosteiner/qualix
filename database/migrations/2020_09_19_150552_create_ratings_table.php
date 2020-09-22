<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('course_id');
            $table->integer('ratingscale_id');
            $table->string('name', 256);
            $table->foreign('course_id', 'fk_ratings_course')->references('id')->on('courses')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('ratingscale_id', 'fk_ratings_ratingscale')->references('id')->on('ratingscales')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('ratings');
    }
}
