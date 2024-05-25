<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Religion extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //
        $religions = [
            [
                'id' => 1,
                'name' => 'Catholic'
            ],
            [
                'id' => 2,
                'name' => 'Protestant'
            ],
            [
                'id' => 3,
                'name' => 'Muslim'
            ],
            [
                'id' => 4,
                'name' => 'Hindu'
            ],
            [
                'id' => 5,
                'name' => 'Buddhist'
            ],
            [
                'id' => 6,
                'name' => 'Others'
            ],
        ];

        DB::table('religions')->insert($religions);
    }
}
