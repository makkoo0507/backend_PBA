<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class ApiRecordController extends Controller
{
    public function index()
    {
        $records = Record::all();
        return response()->json([
            'status' => true,
            'records' => $records
        ],200);
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->all();
            foreach($data as $item){
                Record::create($item);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "データの追加が完了",
            ],200);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Error:" .$e->getMessage(),
            ]);
        }
    }

    public function show(Record $record)
    {
        return response()->json([
            'status' => true,
            'record' => $record
        ],200);
    }

    public function update(Request $request, Record $record)
    {
        $record->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "更新の完了"
        ],200);
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return response()->json([
            'status' => true,
            'message' => "削除の完了",
        ],200);
    }

    public function getRecordsByUserIdAndRoomId(Request $request,$userId,$roomId)
    {
        $year = $request->query("year") ;
        $month = $request->query("month") ;
        if(!$year || !$month){
            return response()->json([
                'status' => false,
                'message' => "yearまたはmonthが不正。year:{$year},month:{$month}"
            ], 422);
        }
        $firstDayOfMonth = Carbon::createFromDate($year, $month)->startOfMonth()->toDateString();
        $lastDayOfMonth = Carbon::createFromDate($year, $month)->endOfMonth()->toDateString();

        $records = Record::where('user_id', $userId)
        ->where('room_id', $roomId)
        ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
        ->get();
        if (!$records) {
            // return response()->json([
            //     'status' => false,
            //     'message' => "レコードの取得に失敗"
            // ], 404);
            return response()->json([
                'status' => true,
                'records' => []
            ], 200);
        }

        return response()->json([
            'status' => true,
            'records' => $records
        ], 200);
    }

    public function getRanks(Request $request,$roomId)
    {
        $records = Record::where('room_id', $roomId)->get();
        $totalAmountsByUser = [];
        if (!$records) {
            return response()->json([
                'status' => true,
                'ranks' => [],
                'message' => "レコードがありません"
            ], 200);
        }
        foreach($records as $record){
            $userId = $record['user_id'];
            if(!isset($totalAmountsByUser[$userId])){
                $totalAmountsByUser[$userId] = 0;
            }
            $totalAmountsByUser[$userId] += $record["amount"];
        }
        asort($totalAmountsByUser);

        $ranks = [];
        $rank = 0;
        $tmpRank = 0;
        $preAmount = -1;
        foreach($totalAmountsByUser as $userId => $amount){
            $userName = User::find($userId)->name;
            $tmpRank += 1;
            if($preAmount < $amount){
                $rank = $tmpRank;
            }
            $ranks[] = [
                "user_id" => $userId,
                "user_name" => $userName,
                "rank" => $rank,
                "amount" => $amount
            ];
            $preAmount = $amount;
        }
        return response()->json([
            'status' => true,
            'ranks' => $ranks
        ], 200);
    }

}
