<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Province extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $provinces = [
            [
                'id' => 1,
                'name' => 'Cavite'
            ],
        ];

        DB::table('provinces')->insert($provinces);
    }
}
