<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('commented_by')->unsigned();
            $table->bigInteger('post_id')->unsigned();;
            $table->integer('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('commented_by')->references('id')->on('users')->onDelete('cascade');;
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
        Schema::dropIfExists('comments');
    }
}
