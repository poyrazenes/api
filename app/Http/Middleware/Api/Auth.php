<?php

namespace App\Http\Middleware\Api;

use App\Support\Response\Response;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = new Response();

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $exception) {
            return $response->setCode(401)->setMessage('Token is Invalid')->respond();
        } catch (TokenExpiredException $exception) {
            return $response->setCode(401)->setMessage('Token is Expired')->respond();
        } catch (\Throwable $exception) {
            return $response->setCode(401)->setMessage('Token not found')->respond();
        }

        return $next($request);
    }
}
