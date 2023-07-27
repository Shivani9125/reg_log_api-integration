<?php

namespace App\Http\Controllers\Api;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Validator;
//  use PHPOpenSourceSaver\JWTAuth\Facades\JWWTAuth;

use Auth;
use App\Models\User;
class AuthController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth:api',[
            'except' => [
                'login',
                'register'
            ]

            ]);
    }
        
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        $user = User::create([
        'name' => $request->name,
        'email'=> $request->email,
        'password'=> $request->password

        ]);
        $token= $user->createToken('myToken')->plainTextToken;
        //$token = Auth::login($user);

        return response()->json([
        'status' =>'success',
        'message' => 'User Registered Successfully!',
        'user' => $user,
        'token' => $token
        ]);

    }
   
   
    function login(Request $request)
    {
       
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
        $user =User::where('email',$request->email)->first();
        if($user)
        {
            //$token= $user->createToken('myToken')->plainTextToken;
            $token = Auth::login($user);
            return response()->json([
                'status' =>'success',
                'message' => 'User login Successfully!',
                'user' => $user,
                'token' => $token
                ]);
        }
        return response()->json([
            'success' =>false,
            'message' => 'Unauthorized'

        ],401
    );


  
}

}

