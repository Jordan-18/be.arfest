<?php

namespace Modules\Auth\Http\Services;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService{
    public function register($request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name'      => ['required','string','max:128'],
                'username'  => ['required','string','max:128','unique:users'],
                'email'     => ['required','string','email','max:64','unique:users'],
                'password'  => ['required','string'],
            ]);

            User::create([
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
                'useraccess'=> 'ca68f15c91184faba866bea7dd3484e8',
                'password'  => Hash::make($request->password),
            ]); 

            $user = User::where('email',$request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            
            DB::commit();
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user               
            ],  'User Registered');
        } 
        catch(Exception $error)
        {
            DB::rollBack();
            return ResponseFormatter::error([
                'message' => 'Terjadi Kesalahan saat register',
                'error' => $error->getMessage()
            ], 'User Register Failed',500);
        }
    }

    public function login($request)
    {
        try{
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email','password']);
            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Anuathorized'
                ],'Anuathorized Failed',500);
            }

            $user = User::where('email',$request->email)->first();

            if(! Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated ');
        }catch(Exception $error){
            return ResponseFormatter::error([
                'message' => 'Terjadi Sebuah Kesahalan',
                'error' => $error->getMessage()
            ],'Anuathorized Failed',500);
        }
    }

    public function fetch($request)
    {
        return ResponseFormatter::success($request->user(),'Data Profile User Berhasil Diambil');
    }

    public function logout($request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token,'Token Revoked');
    }

    public function UpdateProfile($request)
    {
        $data = $request->all();

        $user = Auth::user();
        // $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');
    }
}