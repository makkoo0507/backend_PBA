<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiTestController;
Route::apiResource('tests', ApiTestController::class);

// ユーザー
use App\Http\Controllers\ApiUserController;
Route::apiResource('users', ApiUserController::class);
Route::get('users/{userId}/rooms', [ApiUserController::class,'getUserRooms']);
Route::get('users/{userId}/records', [ApiUserController::class,'getRecords']);


// ルーム
use App\Http\Controllers\ApiRoomController;
Route::apiResource('rooms',ApiRoomController::class);
Route::get('rooms/{roomId}/users', [ApiRoomController::class,'getUsersByRoomId']);

// ルームユーザー
use App\Http\Controllers\ApiRoomUsersController;
Route::apiResource('room_users',ApiRoomUsersController::class);
Route::post('room_users/by_room_account', [ApiRoomUsersController::class,'storeByRoomAccount']);

//レコード
use App\Http\Controllers\ApiRecordController;
Route::apiResource('records',ApiRecordController::class);
Route::get('records/{userId}/{roomId}', [ApiRecordController::class,'getRecordsByUserIdAndRoomId']);
Route::post('records/{userId}/{roomId}', [ApiRecordController::class,'getRecordsByUserIdAndRoomId']);
Route::get('rooms/{roomId}/records/ranks', [ApiRecordController::class,'getRanks']);
//ログイン
use App\Http\Controllers\ApiAuthController;
Route::post('login', [ApiAuthController::class, 'login']);
