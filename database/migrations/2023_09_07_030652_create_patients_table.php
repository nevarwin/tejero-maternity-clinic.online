<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->integer('sex_id');
            $table->integer('weight');
            $table->integer('civil_status_id')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('religion_id')->nullable();
            $table->string('nationality')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('contact_no')->nullable();
            $table->date('birthday');
            $table->string('birth_place');
            $table->string('type_of_patient')->default(1);
            $table->string('lmp')->nullable();
            $table->string('edc')->nullable();
            $table->string('aog')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
