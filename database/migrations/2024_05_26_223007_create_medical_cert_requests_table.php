<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalCertRequestsTable extends Migration {
    public function up() {
        Schema::create('medical_cert_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('doctors_name');
            $table->string('contact_number');
            $table->string('case_number');
            $table->text('description');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('medical_cert_requests');
    }
}
