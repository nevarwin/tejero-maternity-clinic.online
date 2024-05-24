<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConfiguration;
use Illuminate\Support\Facades\DB;

class SystemConfigurationController extends Controller
{
    public function system_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $data = new SystemConfiguration();
            $data->name = $request->name;
            $data->save();
            DB::commit();
            return 'Success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function get_system_data(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $data = SystemConfiguration::first();
            return $data;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
