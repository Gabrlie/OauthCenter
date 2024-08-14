<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DeveloperAuthenticateApi extends Authenticate
{
    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('api')->check()) {
            // 通过id查询用户
            $user = $request->user('api');
            // 判断用户是否为开发者或者管理员
            if ($user->type == 'admin' || $user->type == 'developer') {
                return $this->auth->shouldUse('api');
            }
        }

        throw new UnauthorizedHttpException('', 'Unauthenticated');
    }
}
