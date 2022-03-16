<?php

namespace App\Business;

class LoginBusiness
{
    public static function login($request)
    {
        $code=$request->get('code');
        $appid='wx3eb4f8591cfeb1ce';
        $secret='3d82215960eaf65a35bab5a5bca559e8';
        $url="https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
        $user=json_decode(file_get_contents($url),JSON_UNESCAPED_UNICODE);
        return $user;
    }
}
