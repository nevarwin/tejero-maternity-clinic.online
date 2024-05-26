<?php

use App\Http\Controllers\MedicalCertRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Mail\TestMail;
use App\Mail\MedCert;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/get_user', [UserController::class, 'get_user']);
// Route::post('/verifyAccount',[UserController::class, 'verifyAccount']);
Route::post('/verify_account', [UserController::class, 'verifyAccount']);
Route::post('/send_pin_number', [UserController::class, 'send_pin_number']);
Route::post('/change_password', [UserController::class, 'change_password']);
Route::post('/change_password_account', [UserController::class, 'change_password_account']);
//SENDING EMAIL
Route::post('/mail/store', function (Request $request) {
    // Data Saving;\
    // return $request;
    try {

        \DB::beginTransaction();
        $from =  (object)[];
        // $from->email =  $request->senderEmail; //old 2023-08-11
        $from->email =  'no-reply@gmail.com'; //new change in 2023-08-11
        $from->name =  $request->From;

        // $to = $request->to;
        $to = $request->to;
        $pin_number = strval($request->pin);

        // $cc = $request->cc;

        Mail::to($to)->send(new TestMail($from, $pin_number));

        \DB::commit();
    } catch (\Exception $e) {
        //throw $th;
        \DB::rollback();
        return $e->getMessage();
    }
    return 'Done!';
});

Route::post('/mail/medcert', function (Request $request) {
    // Data Saving;\
    // return $request->pin->full_name;
    try {

        \DB::beginTransaction();
        $from =  (object)[];
        // $from->email =  $request->senderEmail; //old 2023-08-11
        $from->email =  'no-reply@gmail.com'; //new change in 2023-08-11
        $from->name =  $request->From;

        // $to = $request->to;
        $to = $request->to;
        $name = $request->name;
        $doctors_name = $request->doctors_name;
        $contact_number = $request->contact_number;
        $case_number = $request->case_number;
        $description = $request->description;

        // $cc = $request->cc;

        Mail::to($to)->send(new MedCert($from, $name, $doctors_name, $contact_number, $case_number, $description));

        \DB::commit();
    } catch (\Exception $e) {
        //throw $th;
        \DB::rollback();
        return $e->getMessage();
    }
    return 'Done!';
});

Route::post('/saveFile', [UserController::class, 'saveFile']);
Route::post('/saveFileDatabase', [UserController::class, 'saveFileDatabase']);
Route::get('/getFiles', [UserController::class, 'getFiles']);
Route::post('/medcert', [MedicalCertRequestController::class, 'store']);
