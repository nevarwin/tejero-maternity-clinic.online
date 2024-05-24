<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::all();
        return Auth::user();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function change_password_account(Request $req){
        
        // return $req;    
        try {
            $user = User::find($req->id);
            $user->password = Hash::make($req->password);
            $user->save();
            // return $pin_number;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function change_password(Request $req){
        // return $req;
        $min = 100000;
        $max = 999999;
        $randomNumber = mt_rand($min, $max);
        $pin_number = strval($randomNumber);
        try {
            $user = User::find($req->id);
            $user->pin = $pin_number;
            $user->save();
            return $pin_number;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function verifyAccount(Request $req){
        // return $req;
        $user = User::where('email',$req->email)->first();
        return $user;
    }

    public function send_pin_number(Request $req){
        // return $req;
        $min = 100000;
        $max = 999999;
        $randomNumber = mt_rand($min, $max);
        $pin_number = strval($randomNumber);
        try {
            $user = User::find($req->id);
            $user->pin = $pin_number;
            $user->save();
            return $pin_number;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }




    public function get_user(Request $req){
        try {
            $users = User::get();
            return $users;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function register_insert(Request $req){
        // return $req;
        try {
            $user = new User;
            $user->name = strtoupper($req->name);
            $user->username = strtoupper($req->username);
            $user->password = Hash::make($req->password);
            $user->email = $req->email;
            $user->status = $req->status;
            $user->access = $req->access;
            $user->save();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function register_update(Request $req){
        // return $req;
        try {
            $user = User::find($req->id);
            $user->name = strtoupper($req->name);
            $user->username = strtoupper($req->username);
            $user->password = Hash::make($req->password);
            $user->status = $req->status;
            $user->access = $req->access;
            $user->save();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function register_delete(Request $req){
        // return $req;
        try {
            $user = User::find($req->id);
            $user->delete();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create_admin(){
        try {
            $users = User::get();
            if(count($users) < 1){
                $user = new User;
                $user->name = 'ADMIN';
                $user->username = 'ADMIN';
                $user->password = Hash::make('admin101');
                $user->email = 'admin@changeMe.com';
                $user->save();
                return 'success';
            }else{
                return 'already has admin';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function saveFile(Request $request)
    {
        // return $request;

        try {
            foreach ($request->file as $key => $value) {
                $dataB64 = $value['data'];
                list($type, $dataB64) = explode(';', $dataB64);
                list(, $dataB64)      = explode(',', $dataB64);
                $file_data = base64_decode($dataB64);
                
                \Storage::disk('public')->put($value['name'], $file_data);
 
            }
            return 'FILE UPLOADED';
        } catch (\Throwable $th) {
          return $th;
        }
 
    }
    public function saveFileDatabase(Request $request)
    {
        // return $request;

        try {
            $data = \DB::connection('mysql')->table('files')
            ->insert([
                'case_no'=>$request->case_no,
                'file'=>$request->file,
                'path'=>$request->path,
            ]);
            return "save";
        } catch (\Throwable $th) {
          return $th;
        }
 
    }
    public function getFiles(Request $request)
    {
        // return $request;

        try {
            $data = \DB::connection('mysql')->table('files')
            ->select("*")
            ->where('case_no', $request->case_no)
            ->get();
            return $data;
        } catch (\Throwable $th) {
          return $th;
        }
 
    }
    public function downloadFiles($file)
    {
        // return $request;

        try {
            return \Storage::disk('public')->download($file);;
        } catch (\Throwable $th) {
          return $th;
        }
 
    }
    public function deleteFileDatabase(Request $request)
    {
        try {
            \DB::connection('mysql')->table('files')
                ->where('id', $request->id)
                ->delete();
    
            return response()->json(['message' => 'Files deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error deleting files', 'details' => $th->getMessage()], 500);
        }
 
    }
}
