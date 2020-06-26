<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identity_card',10);
            $table->string('basic_service_image');
            $table->enum('status_attendance', ['pendiente', 'aprobado', 'rechazado']);
            $table->json('responsible')->nullable();//persona que apruebe o rechaze la solicitud
            $table->unsignedBigInteger('user_id')->index();
            //foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('memberships');
    }
}
