<?php

namespace App\Http\Controllers;

use App\Models\VitalSign;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
    //insert patient vital sign

    public function vital_sign_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            
            $vital_sign = new VitalSign();
            $vital_sign->case_no = $request->case_no;
            $vital_sign->time = $request->time;
            $vital_sign->blood_presure = $request->blood_presure;
            $vital_sign->temperature = $request->temperature;
            $vital_sign->pulse_rate = $request->pulse_rate;
            $vital_sign->respiratory_rate = $request->respiratory_rate;
            $vital_sign->fetal_heart_tone = $request->fetal_heart_tone;
            $vital_sign->internal_examination = $request->internal_examination;
            
            $vital_sign->save();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }catch(\QueryException $q){
            DB::rollBack();
            return $q;
        }
    }

    //Get patien vital sign
    public function vital_sign(Request $request){
        try {
            // return $request->case_no;
            $case_no = intval($request->case_no);
            $vital_sign = VitalSign::select(
                'vital_signs.*'
            )
            ->where('case_no', '=', $case_no)    
            ->get();
            return $vital_sign;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }catch(\QueryException $q){
            DB::rollBack();
            return $q;
        }
    }
    public function vital_sign_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            // VitalSign::find($request->id);
            $vital_sign = VitalSign::find($request->id);
            $vital_sign->case_no = $request->case_no;
            $vital_sign->time = $request->time;
            $vital_sign->blood_presure = $request->blood_presure;
            $vital_sign->temperature = $request->temperature;
            $vital_sign->pulse_rate = $request->pulse_rate;
            $vital_sign->respiratory_rate = $request->respiratory_rate;
            $vital_sign->fetal_heart_tone = $request->fetal_heart_tone;
            $vital_sign->internal_examination = $request->internal_examination;
            
            $vital_sign->save();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }catch(\QueryException $q){
            DB::rollBack();
            return $q;
        }
        
    }
}
