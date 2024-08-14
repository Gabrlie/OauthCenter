<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class VerifyCaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 从 session 中获取验证码
        $captchaPhrase = Session::get('captchaPhrase');

        // 验证请求中的验证码是否正确
        if (strcasecmp($captchaPhrase, $request->input('captcha')) !== 0) {
            return back()->withErrors(['captcha' => '验证码错误，请重新输入。']);
        }

        return $next($request);
    }
}
