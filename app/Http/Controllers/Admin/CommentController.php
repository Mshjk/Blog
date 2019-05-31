<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CommentController extends Controller
{
  
    /**
     * 评论列表页
     */
    public function index()
    {
        // 获取用户数据
        $users = [];
        $user_datas = DB::table('users')->select('id', 'uname')->get();
        foreach ($user_datas as $k=>$v) {
            $users[$v->id] = $v->uname;
        }  
      
        // 获取文章数据
        $articles = [];
        $article_datas = DB::table('articles')->select('id', 'title')->get();
        foreach ($article_datas as $k=>$v) {
            $articles[$v->id] = $v->title;
        }  
      
    	// 获取评论数据
    	$data = DB::table('comments')->orderBy('id','asc')->paginate(5);
    	return view('admin.comment.index', ['data'=>$data, 'users'=>$users, 'articles'=>$articles]);
    }


    /**
     * 删除评论
     */
    public function del(Request $request)
    {
        
        $id = $request->input('id', 0);

        $res = DB::table('comments')->where('id',$id)->delete();

        if ($res) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }
}
