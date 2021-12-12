<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;    
    }

    public function register(RegisterRequest $request)
    {
        try {

            $this->auth->register($request);

        } catch (\Exception $e) {
            return $this->setResponse(500, false, $e->getMessage());
        }

        return $this->setResponse(201, true, 'success');
    }

    public function login(LoginRequest $request)
    {
        $login = $this->auth->login($request);

        if($login['status']) : 
            return $this->responseWithToken($login['token'], $login['message']);
        endif;

        return $this->setResponse(401, false, $login['message']);
    }

    public function profile()
    {
        $user = auth()->user();

        $data = fractal()
                ->item($user)
                ->transformWith(new UserTransformer)
                ->toArray();

        return $this->setResponse(200, true, 'user profile', $data);
    }
}
