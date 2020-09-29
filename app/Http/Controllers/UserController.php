<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use JWTAuthException;
use Hash;
use App\User;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        $user = DB::table('users')->where('username', $request->get('username'))->first();

        if ($user) {
            return response()->json([
                'status' => 0,
                'message' => 'Tài khoản đã tồn tại',
            ]);
        }
        $user = $this->user->create([
            'username' => $request->get('username'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'avatarUrl' => $request->get('avatarUrl')
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Tạo mới thành công',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = DB::table('users')->where('id', $id);
            if (!$user) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $user = DB::table('users')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
                    'avatarUrl' => $request->get('avatarUrl'),
                    'updated_at' => Carbon::now(),
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thành công',
                    'data' => [
                        'id' => $id,
                        'name' => $request->get('name')
                    ]
                ], 200);
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e,
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 0,
                    'message' => 'invalid_username_or_password'
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Login successfully',
            'data' => compact('token')
        ]);
    }

    public function getUserInfo(Request $request)
    {
        $user = JWTAuth::toUser($request->token);
        if ($user) {
            return response()->json([
                'data' => $user,
                'status' => 200
            ]);
        }
    }
    public function logout()
    {
        JWTAuth::logout();
    }
}
