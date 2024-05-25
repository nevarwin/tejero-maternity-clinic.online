<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PartographModels extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        Schema::create('partograph_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('case_no', 50)->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->integer('maxDuration')->nullable();
            $table->integer('dilation')->nullable();
            $table->string('hours_ruptured_membranes', 50)->nullable();
            $table->string('rapid_assesment', 50)->nullable();
            $table->string('vaginal_bleeding', 50)->nullable();
            $table->string('amniotic_fluid', 50)->nullable();
            $table->string('contraction', 50)->nullable();
            $table->string('fetal_heart_rate', 50)->nullable();
            $table->string('urine_volded', 50)->nullable();
            $table->string('axiliary', 50)->nullable();
            $table->string('pulse', 50)->nullable();
            $table->string('blood_presure', 50)->nullable();
            $table->string('cervical_dilation', 50)->nullable();
            $table->string('delivery_of_placenta', 50)->nullable();
            $table->string('oxytocin', 50)->nullable();
            $table->string('problem_note_onset', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
        Schema::dropIfExists('partograph_models');
    }
}
