<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('country_code');
            $table->string('phone');
            $table->string('country');
            $table->string('city');
            $table->string('password');
            $table->string('lat');
            $table->string('lng');
            $table->string('image')->default('');
            $table->string('profession')->default('');
            $table->boolean('status')->default(0);
            $table->longText('information');
            $table->longText('reason');
            $table->unsignedBigInteger('level_id');
            $table->string('otp')->default('');
            $table->string('otp_time')->default('');
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
        Schema::dropIfExists('users');
    }
};
