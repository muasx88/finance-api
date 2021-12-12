<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return $this->setResponse(401, false, 'Token is invalid');

            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return $this->setResponse(401, false, 'Token is Expired');

            }else{
                
                 return $this->setResponse(401, false, 'Token Not Found');
            }
        }
        return $next($request);
    }
}