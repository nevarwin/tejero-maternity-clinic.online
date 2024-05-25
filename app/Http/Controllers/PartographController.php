<?php

namespace App\Http\Controllers;

use App\Models\PartographModel;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PartographController extends Controller {
    public function partograph_insert(Request $request) {
        try {
            // return $request;
            DB::beginTransaction();

            $partograph = new PartographModel();
            $partograph->hours_ruptured_membranes = $request->hours_ruptured_membranes;
            $partograph->rapid_assesment = $request->rapid_assesment;
            $partograph->vaginal_bleeding = $request->vaginal_bleeding;
            $partograph->amniotic_fluid = $request->amniotic_fluid;
            $partograph->contraction = $request->contraction;
            $partograph->fetal_heart_rate = $request->fetal_heart_rate;
            $partograph->urine_volded = $request->urine_volded;
            $partograph->axiliary = $request->axiliary;
            $partograph->pulse = $request->pulse;
            $partograph->blood_presure = $request->blood_presure;
            $partograph->cervical_dilation = $request->cervical_dilation;
            $partograph->delivery_of_placenta = $request->delivery_of_placenta;
            $partograph->oxytocin = $request->oxytocin;
            $partograph->problem_note_onset = $request->problem_note_onset;
            $partograph->case_no = $request->case_no;
            $partograph->dilation = $request->dilation;
            $partograph->maxDuration = $request->maxDuration;
            $partograph->date = $request->date;
            $partograph->time = $request->time;

            $partograph->save();
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
    public function partograph(Request $request) {
        try {
            $case_no = $request->case_no;

            $partograph = DB::table('partograph_models')
                ->where('case_no', $case_no)
                ->get();

            return $partograph;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } catch (QueryException $q) {
            DB::rollBack();
            return $q;
        }
    }
}
