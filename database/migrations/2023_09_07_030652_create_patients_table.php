<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->integer('sex_id');
            $table->integer('civil_status_id');
            $table->string('occupation');
            $table->integer('religion_id');
            $table->string('nationality');
            $table->integer('address_id');
            $table->string('contact_no');
            $table->date('birthday');
            $table->string('birth_place');
            $table->string('type_of_patient');
            $table->string('lmp');
            $table->string('edc');
            $table->string('aog');
            $table->timestamps();
            $table->softDeletes();
        });
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
}
