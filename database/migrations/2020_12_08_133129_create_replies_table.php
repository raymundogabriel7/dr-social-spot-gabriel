<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function(Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('replied_by')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->string('reply');
            $table->timestamps();

            $table->foreign('replied_by')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
