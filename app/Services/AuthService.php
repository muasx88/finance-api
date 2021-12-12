<?php 
namespace App\Services;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register($request)
    {
        try {

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password)
            ]);

        } catch (QueryException $e) {
            throw $e;
        }

        return $user;
    }

    public function login($request) : array
    {
        $user = User::where('email', $request->email)->first();

        if($user) {
            $check_password = Hash::check($request->password, $user->password);
            if($check_password){
                $token = JWTAuth::fromUser($user);
                if($token) {
                    $result = [
                        'status'    => true,
                        'message'   => 'Success',
                        'token'     => $token,
                        'user'      => $user
                    ];
                    
                    $user->last_login = now();
                    $user->save();

                    return $result;
                }
            }
        }

         return [
            'status'    => false,
            'message'   => 'Invalid Account User'
        ];
    }
}