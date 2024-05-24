<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function doctor_insert(Request $request){
        try {
            DB::beginTransaction();
            $doctor = new Doctor();
            $doctor->name = strtoupper($request->name);
            $doctor->position = strtoupper($request->position);
            $doctor->save();
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

    public function get_doctor(Request $request){
        try {
            DB::beginTransaction();
            $doctors = Doctor::select(
                'id',
                'name',
                'position'
            )
            ->get();
            return $doctors;
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

    public function doctor_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $doctor = Doctor::find($request->id);
            $doctor->name = strtoupper($request->name);
            $doctor->position = strtoupper($request->position);
            $doctor->save();
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
    public function doctor_delete(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $doctor = Doctor::find($request->id);
            $doctor->delete();
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