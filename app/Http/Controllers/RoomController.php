<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;
use App\Models\Bed;


class RoomController extends Controller
{
    public function room_insert(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $room = new Room();
            $room->name = strtoupper($request->name);
            $room->room_type = $request->room_type;
            $room->save();

            $bedToInsert = [];
            foreach ($request->beds as $key => $value) {
                $bedToInsert[] =[
                    'room_id'=>$room->id,
                    'name'=>strtoupper($value['name'])
                ];
            }
            Bed::insert($bedToInsert);

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

    // public function get_room(Request $request){
    //     try {
    //         DB::beginTransaction();
    //         $rooms = Room::select(
    //             'rooms.id',
    //             'rooms.name',
    //             'rooms.room_type',
    //             'beds.vacant',
    //             DB::raw('beds.name AS bed_name')

    //         )
    //         ->join('beds', 'rooms.id', '=', 'beds.room_id')
    //         ->get();
    //         DB::commit();
    //         return $rooms;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return $e->getMessage();
    //     }
    // }

    public function get_room(Request $request){
        try {
            DB::beginTransaction();
            $rooms = Room::select(
                'id',
                'name',
                'room_type'
            )
            ->with('beds')
            ->get();
            return $rooms;
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

    public function room_update(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $room = Room::find($request->id);
            $room->name = strtoupper($request->name);
            $room->room_type = strtoupper($request->room_type);
            $room->save();
            $beds = Bed::where('room_id',$room->id);
            $existing = $beds->pluck('id')->toArray();
            // return $toDelete;
            $toDelete = [];
            $dontDelete = [];
            $toInsert = [];
            foreach ($request->beds as $key => $value) {
                if(isset($value['id'])){
                    
                    $bed = Bed::find($value['id']);
                    $bed->name = $value['name'];
                    $bed->save();
                    $dontDelete[] = $value['id'];
                }else{
                    $toInsert[] = [
                        'room_id'=>$room->id,
                        'name'=>strtoupper($value['name'])
                    ];
                }
            }
            foreach ($existing as $key => $value) {
                if(!in_array($value,$dontDelete)){
                    $toDelete[] = $value;
                }
            }
            if(count($toDelete)>0){
                Bed::whereIn('id',$toDelete)
                ->delete();
            }
            Bed::insert($toInsert);
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
    public function update_bed(Request $request){
        try {
            // Extract data from the request
            $id = $request->id;
            $new_status = $request->vacant;
    
            // Execute the raw SQL update query
            DB::table('beds')
    ->where('id', $id)
    ->update(['vacant' => $new_status]);
    
            // Optionally, return a response
            return response()->json(['message' => 'Bed status updated successfully']);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    public function room_delete(Request $request){
        try {
            // return $request;
            DB::beginTransaction();
            $room = Room::find($request->id);
            $room->delete();
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
