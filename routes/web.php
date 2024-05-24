<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\SystemConfiguration;
use Symfony\Component\HttpFoundation\Request;
use App\Http\Controllers\SystemConfigurationController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\VitalSignController;
use App\Http\Controllers\MedicalSheetController;
use App\Http\Controllers\NewBornController;
use App\Http\Controllers\PartographController;
use App\Http\Controllers\auditController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';
Route::middleware('guest')->group(function () {
    Route::get('/',function(){
        return view('auth.login');
    });
    Route::get('/about',function(){
        return view('auth.about');
    });
    Route::get('/login2',function(){
        return view('auth.index');
    });
    Route::get('/medcert_form',function(){
        return view('auth.medcert');
    });
    Route::get('/request_document',function(){
        return view('auth.login');
    });
    Route::get('/tae',function(){
        return 'take';
    });
    Route::post('/system_insert',[SystemConfigurationController::class, 'system_insert']);
    Route::get('/get_system_data',[SystemConfigurationController::class, 'get_system_data']);
    Route::post('/insert_address', [AddressController::class, 'insert_address']);

    Route::post('/verify_account',[UserController::class, 'verifyAccount']);
    Route::get('/create_admin', [UserController::class, 'create_admin']);
});

Route::middleware(['auth'])->group(function() {
    /*  
    |
    | Some Routes needed to be before the route with "{any?}" parameter.
    | So that it will be found first, after entering the route.
    | if route is found before the "{any?}" it will be initiate and the interpreter wont continue to next line of code.
    */

    Route::get('/auth-user', [AuthenticatedSessionController::class, 'show']);
    Route::get('/get_user', [UserController::class, 'index'] );

    Route::post('/register_insert', [UserController::class, 'register_insert']);
    Route::post('/register_update', [UserController::class, 'register_update']);
    Route::post('/register_delete', [UserController::class, 'register_delete']);
    Route::post('/getFiles',[UserController::class, 'getFiles']);
    Route::post('/deleteFileDatabase',[UserController::class, 'deleteFileDatabase']);

    Route::get('/downloadFiles/{file}', [UserController::class, 'downloadFiles']);

    Route::post('/case_insert', [AdmissionController::class, 'case_insert']);
    Route::get('/get_case', [AdmissionController::class, 'get_case']);
    Route::post('/case_update', [AdmissionController::class, 'case_update']);
    Route::post('/case_delete', [AdmissionController::class, 'case_delete']);

    Route::get('/get_province', [AddressController::class, 'get_province']);

    // NOTE: masters route
    Route::post('/religion_insert', [ReligionController::class, 'religion_insert']);
    Route::get('/get_religion', [ReligionController::class, 'get_religion']);
    Route::post('/religion_update', [ReligionController::class, 'religion_update']);
    Route::post('/religion_delete', [ReligionController::class, 'religion_delete']);

    Route::post('/doctor_insert', [DoctorController::class, 'doctor_insert']);
    Route::get('/get_doctor', [DoctorController::class, 'get_doctor']);
    Route::post('/doctor_update', [DoctorController::class, 'doctor_update']);
    Route::post('/doctor_delete', [DoctorController::class, 'doctor_delete']);

    Route::post('/room_insert', [RoomController::class, 'room_insert']);
    Route::get('/get_room', [RoomController::class, 'get_room']);
    Route::post('/room_update', [RoomController::class, 'room_update']);
    Route::post('/room_delete', [RoomController::class, 'room_delete']);
    Route::post('/update_bed', [RoomController::class, 'update_bed']);

    // NOTE: patient route
    Route::post('/patient_insert', [PatientController::class, 'patient_insert']);
    Route::get('/get_patient', [PatientController::class, 'get_patient']);
    Route::post('/patient_update', [PatientController::class, 'patient_update']);
    Route::post('/patient_delete', [PatientController::class, 'patient_delete']);
    

    // Patient monitor route

    //VITAL SIGN
    Route::post('/vital_sign_insert', [VitalSignController::class, 'vital_sign_insert']);
    Route::post('/vital_sign', [VitalSignController::class, 'vital_sign']);
    Route::post('/vital_sign_update', [VitalSignController::class, 'vital_sign_update']);

    //MEDICATION SHEET
    Route::post('/medication_sheet_insert', [MedicalSheetController::class, 'medication_sheet_insert']);
    Route::post('/medication_sheet', [MedicalSheetController::class, 'medication_sheet']);
    Route::post('/medication_sheet_update', [MedicalSheetController::class, 'medication_sheet_update']);
    
    //NEW BORN
    Route::post('/new_born_insert', [NewBornController::class, 'new_born_insert']);
    Route::post('/new_born', [NewBornController::class, 'new_born']);
    Route::post('/new_born_update', [NewBornController::class, 'new_born_update']);

    //Partograph
    Route::post('/partograph_insert', [PartographController::class, 'partograph_insert']);
    Route::post('/partograph', [PartographController::class, 'partograph']);
    //AUDIT TRAIL
    Route::post('/audit_insert', [auditController::class, 'audit_insert']);
    Route::post('/audit', [auditController::class, 'audit']);



    Route::get('/{any?}', [SpaController::class, 'index'])->where('any', '.*');



});
