<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use JWTAuthException;
use Hash;
use App\User;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }
   
    public function register(Request $request){
        $user = DB::table('users')->where('email', $request->get('email'))->first();
        if ($user) {
            return response()->json([
                'status'=> 0,
                'message'=> 'Tài khoản đã tồn tại',
            ]);
        }
        $user = $this->user->create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => Hash::make($request->get('password'))
        ]);

        return response()->json([
            'status'=> 200,
            'message'=> 'Tạo mới thành công',
            'data'=>$user
        ]);
    }
    
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
           if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 0,
                'message' => 'invalid_email_or_password'
            ]);
           }
        } catch (JWTAuthException $e) {
            return response()->json(['failed_to_create_token'], 500);
        }
        return response()->json([
            'status' => 200,
            'message'=> 'Login successfully',
            'data' => compact('token')
        ]);
    }

    public function getUserInfo(Request $request){
        $user = JWTAuth::toUser($request->token);
        return response()->json(['result' => $user]);
    }
}
