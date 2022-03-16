<?php

namespace App\Http\Controllers;

use App\Business\ArticleBusiness;
use App\Lib\ES;
use App\Lib\Qiniu;
use App\Models\Article;
use App\Models\comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $data = Article::get();
        foreach ($data as $item) {
            $item['img'] = explode(',', $item['img'])[0];
        }
        return $data;
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $res = Qiniu::upload($file);
        return $res;
    }

    public function addArticle(Request $request)
    {
        try {
            $article = ArticleBusiness::addArticle($request);
            $articleID = $article->id;
//            同时向es同步数据
            $res = ArticleBusiness::esAdd($request, $articleID);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500, 'msg' => '服务器内部错误', 'data' => $exception->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        $word = $request->get('word');
//        es搜索
        return ES::search($word);

    }

    public function detail(Request $request)
    {
        $id = $request->get('id');
        $data = Article::where('id', $id)->first()->toArray();
        $data['img'] = explode(',', $data['img']);
        $data['tag'] = array_filter(explode(',', $data['tag']));
        return $data;
    }

    public function comment(Request $request)
    {
        $comment=$request->get('comment');
        $articleID=$request->get('articleID');
        $user_id=auth()->id();
        comment::insert(['user_id'=>$user_id,'article_id'=>$articleID,'comment'=>$comment]);

    }

    /**
     * es初始化
     * @return array
     */
    public function esinit()
    {
        $res = ES::init();
        return ($res);
    }

}
