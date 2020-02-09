<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiPhonesTable extends Migration
{
    function __construct(){
        $this->name = 'phones';
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = 'phones';
        Schema::create($this->name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('public_service_id');
            $table->string('phone_number', 10);
            $table->timestamps();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
        });
        Schema::table($this->name, function($table) {
            $table->foreign('public_service_id')->references('id')->on('public_services');
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
