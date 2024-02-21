<?php

namespace App\Http\Controllers;

use App\Models\RoomUser;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiRoomUsersController extends Controller
{
    public function index()
    {
        $roomUsers = RoomUser::all();
        return response()->json([
            'status' => true,
            'rooms' => $roomUsers
        ],200);
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            foreach($data as $item){
                RoomUser::create($item);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "データの追加が完了",
            ],200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => "Error:" .$e->getMessage(),
            ]);
        }
    }

    public function storeByRoomAccount(Request $request)
    {
        try{
            DB::beginTransaction();
            $room = Room::where('room_account', $request->room_account)->first();
            $roomUser = new RoomUser();
            RoomUser::create([
                'user_id' => $request->user_id,
                'room_id' => $room->id
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "データの追加が完了",
            ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => "Error:" .$e->getMessage(),
            ]);
        }
    }

    public function show(RoomUser $roomUser)
    {
        return response()->json([
            'status' => true,
            'roomUsers' => $roomUser
        ],200);
    }

    public function update(Request $request,RoomUser $roomUser)
    {
        try{
            DB::beginTransaction();
            $roomUser->update($request->all());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "更新の完了"
            ],200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => "Error:" .$e->getMessage(),
            ]);
        }
    }

    // public function update(Request $request, RoomUsers $room_users)
    // {
    //     $room_users->update($request->all());
    //     return response()->json([
    //         'status' => true,
    //         'message' => "更新の完了1"
    //     ],200);
    // }

    public function destroy(RoomUser $roomUser)
    {
        $roomUser->delete();
        return response()->json([
            'status' => true,
            'message' => "削除の完了",
        ],200);
    }
}
