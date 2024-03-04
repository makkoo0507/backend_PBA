<?php
namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email',
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'status' => true,
                'user' => $user,
            ],200);
        }
        return response()->json([
            'status' => false,
            'user' => null,
            'message' => "メールアドレスもしくはパスワードが異なります"
        ],200);
    }
}
