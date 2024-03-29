<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 150);
            $table->mediumText('description');
            $table->boolean('state');
            // $table->date('date');
            // $table->time('time');
            $table->json('ubication')->nullable();
            //por ahora //event_responsible,moderator_aproval, policy_
            // $table->boolean('is_attended')->default(false);
            $table->unsignedBigInteger('user_id')->index();
            // $table->unsignedBigInteger('police_id')->index()->nullable();
            //foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('police_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('posts');
    }
}
