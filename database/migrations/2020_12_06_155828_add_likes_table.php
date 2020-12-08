<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('liked_by')->unsigned();
            $table->bigInteger('post_id')->unsigned();;
            $table->timestamps();

            $table->foreign('liked_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
