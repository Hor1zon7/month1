<?php

namespace App\Lib;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{
    public static function upload($file)
    {

// 需要填写你的 Access Key 和 Secret Key
        $accessKey = "GqTw-O6tEapJJRmrayDksBE_8v_9XrEmlyfPPiEA";
        $secretKey = "LjiDtz-BnCu1ptUBbEk1Slp8UymUFJyve9WROXLv";
        $bucket = "hor1zon7";

// 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

// 生成上传 Token
        $token = $auth->uploadToken($bucket);

// 要上传文件的本地路径
        $filePath =$file;

// 上传到存储后保存的文件名
        $key = md5(microtime()).'.jpg';

// 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        return 'http://images.taylorswift.cloud/'.$key;

    }
}
