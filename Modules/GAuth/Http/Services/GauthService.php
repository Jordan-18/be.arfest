<?php
namespace Modules\GAuth\Http\Services;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GAuthService{
    public function loginGoogle($request)
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        return ResponseFormatter::success([
            'url' => $url
        ], 'Auth Google');
    }

    public function callback($request)
    {  
        try {
            $code = $request->input('code');
            $user = Socialite::driver('google')->stateless()->user($code);
      
            $userData = User::where('gauth_id', $user->id)->first();
      
            if(empty($userData)){
                $userData = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'gauth_id'=> $user->id,
                    'username'=> $user->name,
                    'gauth_type'=> 'google',
                    'useraccess'=> 'ca68f15c91184faba866bea7dd3484e8',
                    'password' => Hash::make($user->name)
                ]);
            }
            $tokenResult = $userData->createToken('authToken')->plainTextToken;

            $response = ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $userData
            ], 'Authenticated ');
            
            $redirectUrl = env('FRONTEND_URL').'/login'; // Replace with your actual frontend URL
            $redirectUrl .= '?response=' . urlencode(json_encode($response));
            return Redirect::to($redirectUrl);
     
        } catch (Exception $error) {
            $redirectUrl = env('FRONTEND_URL').'/login'; // Replace with your actual frontend URL
            $redirectUrl .= '?error=' . urlencode(json_encode($error->getMessage()));
            return Redirect::to($redirectUrl);
        }
    }
}