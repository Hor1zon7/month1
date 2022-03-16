<?php

namespace App\Http\Controllers;

use App\Business\LoginBusiness;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $userinfo=LoginBusiness::login($request);
        $openid=$userinfo['openid'];
//        判断用户是否存在
        $res=User::where('openid',$openid)->first();
        if(empty($res)){
            User::insert(['openid'=>$openid]);
            $res=User::where('openid',$openid)->first();
        }
//        根据用户生成token
        $token=$res->createToken('api')->accessToken;
        return $token;



    }
}
