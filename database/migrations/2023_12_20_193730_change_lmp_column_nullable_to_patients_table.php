<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLmpColumnNullableToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->date('lmp')->nullable()->change();
            $table->date('edc')->nullable()->change();
            $table->integer('aog')->nullable()->change();
            $table->integer('type_of_patient')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('lmp')->change();
            $table->string('edc')->change();
            $table->string('aog')->change();
            $table->string('type_of_patient')->change();
        });
    }
}
