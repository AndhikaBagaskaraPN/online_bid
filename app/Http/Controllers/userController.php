<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\responseController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator; 
use JWTAuth; 
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;

class userController extends Controller
{
    public function login(Request $req){
        $credentials = $req->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        try {
            if(!$token){
                return response()->json(['error' => 'Invalid credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error'=>'Could Not Create Token'], 500);
        }

        responseController.responseApiWithData('success', 'Login Successful', $token, 200);
    }

    public function register(Request $req){
        $validate = Validator::make($req->all(), [
            'first_name' => 'required|string|max:256',
            'last_name' => 'required|string|max:256',
            'address1' => 'string|max:256',
            'phone' => 'required|numeric|min:11|max:13',
            'username' => 'required|string|unique:users|min:4',
            'email'=> 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors()->toJson(), 400);
        }else{
            try {
                $user = new User;
                $user->first_name = $req->first_name;
                $user->last_name = $req->last_name;
                $user->address = $req->address;
                $user->phone = $req->phone;
                $user->username = $req->username;
                $user->email = $req->email;
                $user->password = Hash::make($req->password);
                $user->role = $req->role;
                $created_by = 0;
                $created_at = Carbon::now();
                $updated_by = 0;
                $updated_at = null;
                $user->save();
                $token = JWTAuth::fromUser($user, [
                    'id'->$user->id,
                    'role'->$user->role
                ]);

                responseController.responseApiWithoutData('success', 'register successful', 200);
            } catch (Exception $e) {
                responseController.responseApiWithoutData('failed', 'something wrong', 500);
            }
        }
    }
}
