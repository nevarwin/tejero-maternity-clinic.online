<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicationSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medication_sheets', function (Blueprint $table) {
            $table->id();
            $table->integer('case_no');
            $table->date('date');
            $table->string('medication_dosage');
            $table->integer('hours');
            $table->string('stat_medication');
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
        Schema::dropIfExists('medication_sheets');
    }
}
