<?php

namespace App\Http\Controllers;

use App\Models\NewBorn;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NewBornController extends Controller {
    //INSERT
    public function new_born_insert(Request $request) {
        try {
            DB::beginTransaction();

            $new_born_record = new NewBorn();
            $new_born_record->patient_id = $request->patient_id;
            $new_born_record->case_no = $request->case_no;
            $new_born_record->date_of_birth = $request->date_of_birth;
            $new_born_record->time_of_birth = $request->time_of_birth;
            $new_born_record->birth_weight = $request->birth_weight;
            $new_born_record->birth_lenght = $request->birth_lenght;
            $new_born_record->hc = $request->hc;
            $new_born_record->ac = $request->ac;
            $new_born_record->cc = $request->cc;
            $new_born_record->general_aperance = $request->general_aperance;
            $new_born_record->skin = $request->skin;
            $new_born_record->head_neck = $request->head_neck;
            $new_born_record->eyes = $request->eyes;
            $new_born_record->ent = $request->ent;
            $new_born_record->thorax = $request->thorax;
            $new_born_record->lungs = $request->lungs;
            $new_born_record->heart = $request->heart;
            $new_born_record->abdomen = $request->abdomen;
            $new_born_record->genatalia = $request->genatalia;
            $new_born_record->trunk_spine = $request->trunk_spine;
            $new_born_record->extremities = $request->extremities;
            $new_born_record->anus = $request->anus;
            $new_born_record->reflexes = $request->reflexes;
            $new_born_record->impression = $request->impression;

            $new_born_record->save();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }
    //UPDATE
    public function new_born_update(Request $request) {
        try {
            // return $request;
            DB::beginTransaction();
            // VitalSign::find($request->id);
            $new_born_record = NewBorn::find($request->id);
            $new_born_record->patient_id = $request->patient_id;
            $new_born_record->case_no = $request->case_no;
            $new_born_record->date_of_birth = $request->date_of_birth;
            $new_born_record->time_of_birth = $request->time_of_birth;
            $new_born_record->hc = $request->hc;
            $new_born_record->ac = $request->ac;
            $new_born_record->cc = $request->cc;
            $new_born_record->general_aperance = $request->general_aperance;
            $new_born_record->skin = $request->skin;
            $new_born_record->head_neck = $request->head_neck;
            $new_born_record->eyes = $request->eyes;
            $new_born_record->ent = $request->ent;
            $new_born_record->thorax = $request->thorax;
            $new_born_record->lungs = $request->lungs;
            $new_born_record->heart = $request->heart;
            $new_born_record->abdomen = $request->abdomen;
            $new_born_record->genatalia = $request->genatalia;
            $new_born_record->trunk_spine = $request->trunk_spine;
            $new_born_record->extremities = $request->extremities;
            $new_born_record->anus = $request->anus;
            $new_born_record->reflexes = $request->reflexes;
            $new_born_record->impression = $request->impression;

            $new_born_record->save();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }
    //GET
    public function new_born(Request $request) {
        try {
            // return $request;
            $case_no = $request->case_no;
            $new_born = NewBorn::select(
                'new_borns.*'
            )
                ->where('case_no', $case_no)
                ->get();
            return $new_born;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }
}
