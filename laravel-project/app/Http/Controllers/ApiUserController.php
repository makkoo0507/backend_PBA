<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApiUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => true,
            'users' => $users
        ],200);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $createdUsers = [];
            $data = $request->all();
            foreach($data as $item) {
                $user = User::create($item);
                $createdUsers[] = $user;
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "データの追加が完了",
                'users' => $createdUsers,
            ],200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => "Error: " . $e->getMessage(),
            ],500);
        }
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'user' => $user
        ],200);
    }

    public function update(Request $request, User $user)
    {
        try {
            $user->update($request->all());

            return response()->json([
                'status' => true,
                'message' => '更新の完了'
            ], 200);
        } catch (\Exception $e) {
            $constraintKeyToColumn = [
                'users.users_email_unique' => 'メールアドレス', //email
            ];
            if ($e instanceof \Illuminate\Database\QueryException && $e->getCode() == 23000) {
                $errorMessage = $e->getMessage();
                $matches = [];

                // Use regular expression to extract the key name
                if (preg_match("/key '(.+?)'/", $errorMessage, $matches)) {
                    $constraintKey = $matches[1];
                    if (array_key_exists($constraintKey, $constraintKeyToColumn)) {
                        $columnName = $constraintKeyToColumn[$constraintKey];
                        return response()->json([
                            'status' => false,
                            'message' => "この{$columnName}は既に使用されています。"
                        ], 422);
                    }
                }
            }
            return response()->json([
                'status' => false,
                'message' => '更新に失敗しました。エラー: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => "削除の完了",
        ], 200);
    }

    public function getUserRooms($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $rooms = $user->rooms;

        return response()->json([
            'status' => true,
            'rooms' => $rooms
        ], 200);
    }

    public function getRecords($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $records = $user->records;
        if (!$records) {
            return response()->json(['message' => 'Record not found'], 404);
        }


        return response()->json([
            'status' => true,
            'records' => $records
        ], 200);
    }
}
