<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Set validation rules
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate admin using the 'admin-api' guard
        if (!$token = auth()->guard('admin-api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah',
            ], 401);
        }

        // If authentication is successful
        return response()->json([
            'success' => true,
            'admin' => auth()->guard('admin-api')->user(),
            'token' => $token,
        ], 200);
    }
}
