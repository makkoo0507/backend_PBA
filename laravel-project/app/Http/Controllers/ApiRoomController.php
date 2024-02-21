<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return response()->json([
            'status' => true,
            'rooms' => $rooms
        ],200);
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $createdRooms = [];
            $data = $request->all();
            foreach($data as $item){
                $item['room_account'] = '@' . Str::random(6);
                $room = Room::create($item);
                $createdRooms[] = $room;
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "データの追加が完了",
                'rooms' => $createdRooms
            ],200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => "Error:" .$e->getMessage(),
            ]);
        }
    }

    public function show(Room $room)
    {
        return response()->json([
            'status' => true,
            'room' => $room
        ],200);
    }

    public function update(Request $request, Room $room)
    {
        $room->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "更新の完了"
        ],200);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json([
            'status' => true,
            'message' => "削除の完了",
        ],200);
    }

    public function getUsersByRoomId($roomId)
    {
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $users = $room->users;
        return response()->json([
            'status' => true,
            'users' => $users
        ],200);
    }
}
