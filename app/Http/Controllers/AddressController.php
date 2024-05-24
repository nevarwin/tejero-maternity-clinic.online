<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Models\Municipal;
use App\Models\Province;

class AddressController extends Controller
{
    public function insert_address(Request $request){
        try{
            $address = $request->address;
            $province = [];
            $municipality = [];
            $barangay = [];
            $municipality_id = 1;
            $barangay_id = 1;
            foreach ($address as $key => $value) {
                
                $province[] = array(
                    'id' => $key + 1,
                    'name' => $value['province'],
                );
                // return $province;

                foreach ($value['municipality'] as $key1 => $value1) {

                    $municipality[] = array(
                        'id' => $municipality_id,
                        'name' => $value1['name'],
                        'province_id' => $key + 1,
                    );
                    // return $municipality;

                    if (count($value1['barangay']) > 0) {
                        foreach ($value1['barangay'] as $key2 => $value2) {

                            $barangay[] = array(
                                'id' => $barangay_id,
                                'name' => $value2['name'],
                                'province_id' => $key + 1,
                                'municipal_id' => $municipality_id,
                            );
                            $barangay_id++;
                            // return $barangay;
                        }
                    }
                    $municipality_id++;
                }
                // return $barangay;
            }
            // return $barangay;
            Province::insert($province);
            Municipal::insert($municipality);
            // Barangay::insert($barangay);
            $chunks = array_chunk($barangay, 1000);
            foreach ($chunks as $chunk) {
                Barangay::insert($chunk);
            }
            return 'success';
        }catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    public function get_province(Request $request){
        $province = Province::select(
            'id',
            'name',
        )
        ->with(['municipality.barangay'])
        ->get();
        return $province;
    }

}
