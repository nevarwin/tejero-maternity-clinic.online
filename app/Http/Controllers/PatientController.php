<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\Address;
class PatientController extends Controller
{
    public function patient_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $address = new Address();
            $address->province_id = $request->province_id;
            $address->municipality_id = $request->municipality_id;
            $address->barangay_id = $request->barangay_id;
            $address->region = strtoupper($request->region);
            $address->house_address = strtoupper($request->house_address);
            $address->address_txt = strtoupper($request->address_txt);
            $address->save();
            $last_id = DB::getPdo()->lastInsertId();

            $patient = new Patient();
            $patient->name = strtoupper($request->name);
            $patient->age = $request->age;
            $patient->sex_id = $request->sex_id;
            $patient->civil_status_id = $request->civil_status_id;
            $patient->occupation = strtoupper($request->occupation);
            $patient->religion_id = $request->religion_id;
            $patient->nationality = strtoupper($request->nationality);
            $patient->address_id = $last_id;
            $patient->contact_no = $request->contact_no;
            $patient->birthday = $request->birthday;
            $patient->weight = $request->weight;
            $patient->birth_place = strtoupper($request->birth_place);
            $patient->type_of_patient = $request->type_of_patient;
            $patient->lmp = $request->lmp;
            $patient->edc = $request->edc;
            $patient->aog = $request->aog;

            $patient->save();
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

    public function get_patient(Request $request){
        try {
            // return $request;

            $patients = Patient::select(
                'patients.*',
                'address.province_id',
                'address.municipality_id',
                'address.barangay_id',
                'address.region',
                'address.house_address',
                'address.address_txt',

                'religions.name as religion_name',
            )
            ->leftJoin('address','address.id','patients.address_id')
            ->leftJoin('religions','religions.id','patients.religion_id')
            ->get();
            return $patients;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }catch(\QueryException $q){
            DB::rollBack();
            return $q;
        }
    }

    public function patient_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $address = new Address();
            $address->province_id = $request->province_id;
            $address->municipality_id = $request->municipality_id;
            $address->barangay_id = $request->barangay_id;
            $address->region = strtoupper($request->region);
            $address->house_address = strtoupper($request->house_address);
            $address->address_txt = strtoupper($request->address_txt);
            $address->save();
            $last_id = DB::getPdo()->lastInsertId();

            $patient = Patient::find($request->id);
            $patient->name = strtoupper($request->name);
            $patient->age = $request->age;
            $patient->sex_id = $request->sex_id;
            $patient->civil_status_id = $request->civil_status_id;
            $patient->occupation = strtoupper($request->occupation);
            $patient->religion_id = $request->religion_id;
            $patient->nationality = strtoupper($request->nationality);
            $patient->address_id = $last_id;
            $patient->contact_no = $request->contact_no;
            $patient->birthday = $request->birthday;
            $patient->weight = $request->weight;
            $patient->birth_place = strtoupper($request->birth_place);
            $patient->type_of_patient = $request->type_of_patient;
            $patient->lmp = $request->lmp;
            $patient->edc = $request->edc;
            $patient->aog = $request->aog;
            $patient->save();
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

    public function patient_delete(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $doctor = Patient::find($request->id);
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
