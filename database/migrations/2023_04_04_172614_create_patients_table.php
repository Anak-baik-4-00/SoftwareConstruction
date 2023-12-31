<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->integer('patientID')->unique();
            $table->string('name');
            $table->integer('age');
            $table->string('gender');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('phoneNumber');
            $table->integer('role');
            $table->integer('userstatus');
            $table->integer('is_admin');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE patients ADD pimg LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
