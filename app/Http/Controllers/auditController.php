<?php

namespace App\Http\Controllers;
use App\Models\auditModel;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class auditController extends Controller
{
    public function audit_insert(Request $request){
        try {
            $audit = new auditModel;
            $audit->name = $request->name;
            $audit->action = $request->action;
            $audit->description = $request->description;
            $audit->drawerLink = $request->drawerLink;
            $audit->position = $request->position;
            $audit->date = $request->date;
          
            $audit->save();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        
        
    }
}

public function audit(Request $request){
    try {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $query = "SELECT * FROM audit_models WHERE created_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ORDER BY created_at DESC";
        $audit = \DB::select($query);
        return $audit;
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

}