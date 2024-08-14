<?php

namespace App\Http\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generateCaptcha(Request $request)
    {
        $token = $request->input('token');

        // 生成验证码
        $builder = new CaptchaBuilder;
        $builder->build();

        // 将验证码存储在缓存中，使用传入的token作为key
        Cache::put($token, $builder->getPhrase(), 300); // 验证码存储5分钟

        // 返回验证码图片
        return response()->json(['captcha_image' => $builder->inline()]);
    }

    public function validateCaptcha(Request $request)
    {
        $request->validate([
            'captcha' => 'required|string',
        ]);

        $captchaPhrase = Session::get('captchaPhrase');

        if ($captchaPhrase !== $request->input('captcha')) {
            return response()->json(['message' => '验证码错误'], 422);
        }

        return response()->json(['message' => '验证码正确']);
    }
}
