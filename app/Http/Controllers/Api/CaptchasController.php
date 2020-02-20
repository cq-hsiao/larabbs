<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request,CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha_'.Str::random(15);
        $expiredAt = now()->addMinutes(2);

        $phone = $request->phone;
        $captcha = $captchaBuilder->build();
        Cache::put($key,['phone' => $phone,'code' => $captcha->getPhrase()],$expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

//        return response()->json($result,201);
        return response()->json($result)->setStatusCode(201);
    }
}
