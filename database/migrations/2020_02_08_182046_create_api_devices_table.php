<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiDevicesTable extends Migration
{
    function __construct(){
        $this->name = 'devices';
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
            $table->string('phone_id',255);
            $table->unsignedBigInteger('user_id');
            $table->text('description')->nullable();
            $table->string('phone_model', 100)->nullable();
            $table->string('phone_platform', 100)->default('Modelo Generico');
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });
        Schema::table($this->name, function($table) {
            $table->foreign('user_id')->references('id')->on('users');
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
