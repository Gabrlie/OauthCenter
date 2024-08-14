<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VerifyCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('token');
        $captcha_code = $request->input('captcha');

        // 从缓存中获取验证码并验证
        if ($token && $captcha_code && strtolower(Cache::get($token)) === strtolower($captcha_code)) {
            // 验证成功后，删除缓存中的验证码
            Cache::forget($token);

            return $next($request);
        }

        return response()->json(['error' => '验证码过期'], 422);
    }
}
