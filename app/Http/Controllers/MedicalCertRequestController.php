<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalCertRequest;

class MedicalCertRequestController extends Controller {
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
