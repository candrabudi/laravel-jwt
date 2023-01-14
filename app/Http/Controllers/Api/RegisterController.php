<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\IsFalse;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required', 
            'email'     => 'required|email|unique:users', 
            'password'  => 'required|min:8|confirmed'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'   => false,
                'code'      => 422,
                'message'   => 'Sorry something wrong!',
                'data'      => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        if($user){
            return response()->json([
                'success'   => true,
                'code'      => 201,
                'data'      => [
                    'user'  => $user
                ]
            ], 201);
        }

        return response()->json([
            'success'   => false,
            'code'      => 400,
            'message'   => 'failed register',
            'data'      => []
        ], 400);
    }
}
