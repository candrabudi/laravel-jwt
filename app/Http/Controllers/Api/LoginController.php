<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'     => 'required', 
            'password'  => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->erros(), 422);
        }

        $credentials = $request->only('email', 'password');

        if(!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success'   => false,
                'code'      => 401,
                'message'   => 'Email atau password Anda salah!',
                'data'      => []
            ], 401);
        }
        $data = array(
            'user'      => auth()->user(),
            'token'     =>  $token
        );
        return response()->json([
            'success'   => true,
            'code'      => 200,
            'message'   => 'Successfully Register!',
            'data'      => $data
        ], 200);
    }
}
