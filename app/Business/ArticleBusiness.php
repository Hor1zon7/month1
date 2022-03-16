<?php

namespace App\Business;

use App\Lib\ES;
use App\Models\Article;
use http\Env\Request;
use mysql_xdevapi\Exception;

class ArticleBusiness
{
    public static function addArticle($request)
    {
        try {
            $data = $request->post('data');
            $data['img'] = implode(',', $data['img']);
            return Article::create($data);
        } catch (\Exception $exception) {
            throw new  Exception('系统错误');
        }
    }

    public static function esAdd($request,$articleID)
    {
        $data=$request->toArray()['data'];
        $data['id']=$articleID;
        $esData=[
            'index'=>'article',
            'id'=>$articleID,
            'body'=>$data
        ];
       return ES::add($esData);
    }
}
