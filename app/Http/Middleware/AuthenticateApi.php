<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateApi extends Authenticate
{
    protected function authenticate($request, array $guards)
    {
//        if ($request->has('Authorization')) {
//            $request->headers->set('Authorization', 'Bearer ' . $request->input('Authorization'));
//        }

        if ($this->auth->guard('api')->check()) {
            return $this->auth->shouldUse('api');
        }

        throw new UnauthorizedHttpException('', 'Unauthenticated');
    }
}
