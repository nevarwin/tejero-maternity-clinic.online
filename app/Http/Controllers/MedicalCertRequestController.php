<?php

namespace App\Http\Controllers;

use App\Mail\MedCert;
use App\Models\MedicalCertRequest;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function updateStatus(Request $request) {
        // Log the incoming request data
        Log::info('Request Data: ', $request->all());

        // Validate the incoming request data
        $validated = $request->validate([
            'id' => 'required|integer|exists:medical_cert_requests,id', // Adjust table name as needed
            'status' => 'required|string'
        ]);

        // Log the validated data
        Log::info('Validated Data: ', $validated);

        try {
            // Find the medcert by id
            $medCert = MedicalCertRequest::find($validated['id']);
            if (!$medCert) {
                return response()->json(['message' => 'Medical certificate not found'], 404);
            }

            // Update the status
            $medCert->status = $validated['status'];
            $medCert->save();

            return response()->json(['message' => 'Status updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating status: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
