<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ListsController extends Controller
{
    /**
     * 列表页页面
     */
    public function index(Request $request)
    {
        // 搜索条件title
        $title = $request->input('title', '');
        // 标签云id
        $tid = $request->input('tagname_id', 0);
        // 栏目id
        $cid = $request->input('cid', 0);
        $id = 0;
      
      
        // 获取头部栏目数据
        $cate_data = IndexController::cate_redis();	
      
        // 获取标签云的数据
        $tagname_datas =IndexController::tagname_redis();
      
        // 获取特别推荐文章数据
        $special_articles = IndexController::special_redis();
        
        // 获取推荐文章数据
        $recommend_articles = IndexController::recommend_redis();
      
        // 获取点击排行文章数据
        $click_articles = IndexController::click_redis();
      
        // 获取友情链接数据
        $friend_data = IndexController::friend_redis();
      
      
        // 获取分类id为键, 分类名为值的数据
        $cate_name = IndexController::getCatesName();
      
        if ($tid != 0) {
            // 获取标签云对应的文章
            $article_data  = DB::table('articles')->where('tid', $tid)->orderBy('ctime', 'desc')->paginate(10);
        } elseif($cid != 0) {
            // 获取该cid下的文章,并按照发布时间排序
            $article_data  = DB::table('articles')->where('cid', $cid)->orderBy('ctime', 'desc')->paginate(10);
        } elseif($title != '') {
        	// 获取搜索条件相关的文章
            $article_data  = DB::table('articles')->where('title', 'like', "%$title%")->orderBy('ctime', 'desc')->paginate(10); 
        } else {
        	return back();
        }

        // 获取标签云数据
        $tagname_data = DB::table('tagnames')->where('id', $tid)->first();

    	// 获取包含栏目id和名称的数组
    	$cate_name = IndexController::getCatesName();

    	return view('home.lists.index', ['click_articles'=>$click_articles, 'recommend_articles'=>$recommend_articles, 'special_articles'=>$special_articles, 'cate_name'=>$cate_name, 'cid'=>$cid, 'tid'=>$tid, 'cate_data'=>$cate_data, 'article_data'=>$article_data, 'tagname_data'=>$tagname_data, 'tagname_datas'=>$tagname_datas, 'friend_data'=>$friend_data, 'cate_name'=>$cate_name]);
    }
}
