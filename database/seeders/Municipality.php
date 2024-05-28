<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Municipality extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $municipality = [
            [
                'id' => 1,
                'name' => 'Tanza',
                'province_id' => 1,
            ],
        ];

        DB::table('municipals')->insert($municipality);
    }
}
