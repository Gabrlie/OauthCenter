<?php

namespace App\Http\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptchaController extends Controller
{
    public function generateCaptcha()
    {
        $builder = new CaptchaBuilder;
        $builder->build();

        // 存储验证码内容到 session 中
        Session::put('captchaPhrase', $builder->getPhrase());

        // 返回验证码图片
        return response($builder->output())->header('Content-Type', 'image/jpeg');
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
