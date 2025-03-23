<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/Register",
     *     summary="使用者註冊",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="註冊所需的使用者資料",
     *         @OA\JsonContent(
     *             required={"username","account","email","password"},
     *             @OA\Property(property="username", type="string", example="Administrator"),
     *             @OA\Property(property="account", type="string", example="admin"),
     *             @OA\Property(property="email", type="string", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="admin123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="註冊成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="註冊成功"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    public function Register(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'account'  => 'required|string|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'username' => $request->input('username'),
            'account'  => $request->input('account'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => 'user'
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => '註冊成功',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/Login",
     *     summary="使用者登入",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="登入所需的帳號與密碼",
     *         @OA\JsonContent(
     *             required={"account","password"},
     *             @OA\Property(property="account", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="admin123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="登入成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="登入成功"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="帳號或密碼錯誤"
     *     )
     * )
     */
    public function Login(Request $request)
    {
        $request->validate([
            'account' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('account', 'password');

        try {
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => '帳號或密碼錯誤'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => '無法產生 token'], 500);
        }

        return response()->json([
            'message' => '登入成功',
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/Logout",
     *     summary="使用者登出",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="已登出",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="已登出")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="登出失敗"
     *     )
     * )
     */
    public function Logout(Request $request)
    {
        \Log::info('Request Headers: ' . json_encode($request->headers->all()));
        try {
            $token = JWTAuth::getToken();
            \Log::info('Logout token: ' . $token);
            JWTAuth::invalidate($token);
            return response()->json(['message' => '已登出']);
        } catch (\Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['error' => '登出失敗，請重試'], 500);
        }
    }
}
