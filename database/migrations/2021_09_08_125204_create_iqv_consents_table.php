<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIqvConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iqv_consents', function (Blueprint $table) {
            $table->id();
            $table->string('full_name_en')->nullable();
            $table->string('full_name_ar')->nullable();

             $table->integer('speciality_id')->unsigned();
            $table->foreign('speciality_id')->references('id')->on('iqv_specialities')->onDelete('cascade');

            $table->string('gov')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('hospital_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('notes')->nullable();
            $table->string('on_key_id')->nullable();
            $table->tinyInteger('status')->default(0);
             $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('iqv_consents');
    }
}
