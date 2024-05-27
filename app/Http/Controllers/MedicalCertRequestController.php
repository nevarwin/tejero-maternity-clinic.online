<?php

namespace App\Http\Controllers;

use App\Mail\MedCert;
use App\Models\MedicalCertRequest;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class MedicalCertRequestController extends Controller {
    public function get_medcert(Request $request) {
        try {
            DB::beginTransaction();
            $medcerts = DB::table('medical_cert_requests')
                ->select('id', 'full_name', 'doctors_name', 'contact_number', 'case_number', 'description', 'created_at', 'updated_at', 'status')
                ->orderBy('id', 'DESC')
                ->get();
            DB::commit();

            if ($medcerts->isEmpty()) {
                return response()->json(['message' => 'No data found'], 404);
            }

            return response()->json($medcerts);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $q) {
            DB::rollBack();
            return response()->json(['error' => $q->getMessage()], 500);
        }
    }


    public function store(Request $request) {
        $request->validate([
            'full_name' => 'required|string',
            'doctors_name' => 'required|string',
            'contact_number' => 'required|string',
            'case_number' => 'required|string',
            'description' => 'required|string',
        ]);

        $medCertRequest = MedicalCertRequest::create($request->all());

        return response()->json(['message' => 'Request saved successfully', 'data' => $medCertRequest], 201);
    }
}
