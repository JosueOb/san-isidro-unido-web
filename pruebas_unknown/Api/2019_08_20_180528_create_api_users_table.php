<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiUsersTable extends Migration
{
    function __construct(){
        $this->name = 'users';
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname',40);
            $table->string('lastname',40);
            $table->string('email',50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar',255)->nullable();
            $table->string('password')->nullable();
            $table->boolean('state')->default(1);
            $table->string('phone', 10)->nullable();
            $table->string('basic_service_image',255)->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });

        Schema::table($this->name, function($table) {
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->name);
    }
}
