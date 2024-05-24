<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Religion;
use Illuminate\Support\Facades\DB;

class ReligionController extends Controller
{
    public function religion_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $religion = new Religion();
            $religion->name = strtoupper($request->name);
            $religion->save();
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

    public function get_religion(Request $request){
        try {
            DB::beginTransaction();
            $religions = Religion::select(
                'id',
                'name',
            )
            ->get();
            return $religions;
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

    public function religion_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $religion = Religion::find($request->id);
            $religion->name = strtoupper($request->name);
            $religion->save();
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
    public function religion_delete(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $religion = Religion::find($request->id);
            $religion->delete();
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
