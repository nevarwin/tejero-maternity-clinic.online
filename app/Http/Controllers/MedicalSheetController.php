<?php

namespace App\Http\Controllers;
use App\Models\MedicationSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MedicalSheetController extends Controller
{
    //MEDICAL SHEET INSERT
    public function medication_sheet_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            
            $medication_sheet = new MedicationSheet();
            $medication_sheet->date = $request->date;
            $medication_sheet->case_no = $request->case_no;
            $medication_sheet->medication_dosage = $request->medication_dosage;
            $medication_sheet->hours = $request->hours;
            $medication_sheet->stat_medication = $request->stat_medication;
            $medication_sheet->save();
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

    //MEDICAL SHEET GET
    public function medication_sheet(Request $request){
        try {
            
            $case_no = intval($request->case_no);
            $medication_sheet = MedicationSheet::select(
                'medication_sheets.*'
            )
            ->where('case_no', '=', $case_no) 
            ->get();
            return $medication_sheet;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }catch(\QueryException $q){
            DB::rollBack();
            return $q;
        }
    }

    public function medication_sheet_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            // VitalSign::find($request->id);
            $medication_sheet = MedicationSheet::find($request->id);
            $medication_sheet->case_no = $request->case_no;
            $medication_sheet->date = $request->date;
            $medication_sheet->hours = $request->hours;
            $medication_sheet->medication_dosage = $request->medication_dosage;
            $medication_sheet->stat_medication = $request->stat_medication;
            
            $medication_sheet->save();
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
