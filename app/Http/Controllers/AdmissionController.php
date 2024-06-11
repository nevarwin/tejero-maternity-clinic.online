<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admission;


class AdmissionController extends Controller {
    public function case_insert(Request $request) {
        try {
            // return $request;
            DB::beginTransaction();
            $check = Admission::where('case_no', strtoupper($request->case_no))->first();
            if ($check === null) {
                $case = new Admission();
                $case->case_no = strtoupper($request->case_no);
                $case->patient_id = $request->patient_id;
                $case->doctor_id = $request->doctor_id;
                $case->room_id = $request->room_id;
                $case->bed_name = $request->bed_id;
                $case->relationship = strtoupper($request->relationship);
                $case->husband_name = strtoupper($request->husband_name);
                $case->save();
                // return 'success';
            } else {
                return 1;
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (\QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }

    public function get_case(Request $request) {
        try {
            DB::beginTransaction();
            $cases = DB::table('admissions')
                ->select(
                    'admissions.id',
                    'admissions.case_no',
                    'admissions.patient_id',
                    'admissions.doctor_id',
                    'admissions.room_id',
                    DB::raw('beds.name AS bed_name'),
                    DB::raw('beds.id AS bed_id'),
                    'admissions.relationship',
                    'admissions.husband_name',
                    'patients.name as patient_name',
                    'patients.lmp',
                    'patients.edc',
                    'patients.aog',
                    'patients.weight',
                    'latest_vital_signs.blood_presure',
                    'latest_vital_signs.temperature',
                    'latest_vital_signs.pulse_rate',
                    'latest_vital_signs.respiratory_rate',
                    'latest_vital_signs.fetal_heart_tone',
                    'latest_vital_signs.internal_examination',
                    'doctors.name as doctor_name',
                    'rooms.name as room_name',
                    'rooms.room_type',
                    'admissions.created_at AS admission_date',
                    'admissions.deleted_at AS discharge_date'
                )
                ->leftJoin('patients', 'patients.id', '=', 'admissions.patient_id')
                ->leftJoin('doctors', 'doctors.id', '=', 'admissions.doctor_id')
                ->leftJoin('rooms', 'rooms.id', '=', 'admissions.room_id')
                ->leftJoin('beds', 'beds.id', '=', 'admissions.bed_name')
                ->leftJoin(DB::raw('(SELECT case_no, blood_presure, temperature, pulse_rate, respiratory_rate, fetal_heart_tone, internal_examination
                        FROM vital_signs
                        ORDER BY id DESC
                        LIMIT 1) AS latest_vital_signs'), 'latest_vital_signs.case_no', '=', 'admissions.case_no')

                ->orderBy('admissions.id', 'DESC')
                ->get();
            return $cases;
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (\QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }

    public function case_update(Request $request) {
        try {
            // return $request;
            DB::beginTransaction();
            $case = Admission::find($request->id);
            // $case->case_no = strtoupper($request->case_no);
            $case->patient_id = $request->patient_id;
            $case->doctor_id = $request->doctor_id;
            $case->room_id = $request->room_id;
            $case->bed_name = $request->bed_id;
            $case->relationship = strtoupper($request->relationship);
            $case->husband_name = strtoupper($request->husband_name);
            $case->save();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (\QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }
    public function case_delete(Request $request) {
        try {
            DB::beginTransaction();

            // Using raw SQL to perform soft delete
            $id = $request->id;
            $affected = DB::table('admissions')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            if ($affected > 0) {
                DB::commit();
                return 'success';
            } else {
                DB::rollBack();
                return 'Record not found';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
