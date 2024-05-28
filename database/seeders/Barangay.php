<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Barangay extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        $barangays = [
            [
                'id' => 1,
                'name' => 'barangay1',
                'province_id' => 1,
                'municipal_id' => 1,
            ],
        ];

        DB::table('barangays')->insert($barangays);
    }
}
