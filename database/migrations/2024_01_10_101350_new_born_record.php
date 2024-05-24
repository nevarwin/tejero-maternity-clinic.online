<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewBornRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('new_born_record', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->integer('patient_id');
            $table->string('case_no');
            $table->date('date_of_birth');
            $table->time('time_of_birth');
            $table->string('birth_weight');
            $table->string('birth_lenght');
            $table->string('hc');
            $table->string('ac');
            $table->string('cc');
            $table->string('general_aperance');
            $table->string('skin');
            $table->string('head_neck');
            $table->string('eyes');
            $table->string('ent');
            $table->string('thorax');
            $table->string('lungs');
            $table->string('heart');
            $table->string('abdomen');
            $table->string('genatalia');
            $table->string('trunk_spine');
            $table->string('extremities');
            $table->string('anus');
            $table->string('reflexes');
            // Add more columns as needed
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
