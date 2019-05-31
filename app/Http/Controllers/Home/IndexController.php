<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use DB;

class IndexController extends Controller
{

	/**
     * 前台 栏目 数据
     */
	public static function getPidCates()
	{
		// 获取父栏目
    	$cates_parent_data = DB::table('cates')->where('pid',0)->orderBy('id','asc')->get();

    	$temp = [];
    	foreach ($cates_parent_data as $k=>$v) {
    		// 获取当前栏目的子栏目
    		$cates_child_data = DB::table('cates')->where('pid', $v->id)->get();
    		$v->sub = $cates_child_data;
    		$temp[] = $v;
    	}

    	return $temp;
	}
    
  
    /**
     * 首页轮播图右边的两篇文章
     * @return $temp array
     */
    private static function top_right()
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取点赞量和点击排在前面的2篇文章 的 id和title thumb cid
        $articles_title = DB::table('articles')->select('id', 'title', 'thumb', 'cid')->orderBy('goodnum', 'desc')->orderBy('num', 'desc')->limit(2)->get();
        
        // 遍历操作文章数据, 使得文章id为键, 文章标题 cid和缩略图为值
        foreach ($articles_title as $k=>$v) {
            $temp[$v->id] = ['title'=>$v->title, 'thumb'=>$v->thumb, 'cid'=>$v->cid];
        }
      
        return $temp;
    }
  
    /**
     * 特别推荐
     * @return $temp array
     */
    public static function special()
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取点赞量排在前面的3篇文章 的 id和title thumb cid
        $articles_title = DB::table('articles')->select('id', 'title', 'thumb', 'cid')->orderBy('goodnum', 'desc')->limit(3)->get();
        
        // 遍历操作文章数据, 使得文章id为键, 文章标题和缩略图为值
        foreach ($articles_title as $k=>$v) {
            $temp[$v->id] = ['title'=>$v->title, 'thumb'=>$v->thumb, 'cid'=>$v->cid];
        }
      
        return $temp;
    }
    
  
    /**
     * 推荐文章
     * @return $temp array
     */
    public static function recommend()
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取id为2的倍数 排在前面的5篇文章 的 id和title thumb ctime
        $articles_title = DB::select('select id,title,thumb,ctime from articles where id%2=0 order by ctime desc limit 5');
        
        // 遍历操作文章数据, 使得文章id为键, 文章标题和缩略图 创建时间为值
        foreach ($articles_title as $k=>$v) {
            $temp[$v->id] = ['title'=>$v->title, 'thumb'=>$v->thumb, 'ctime'=>$v->ctime];
        }
      
        return $temp;
    }
  
    
    /**
     * 点击排行
     * @return $temp array
     */
    public static function click_top()
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取点赞量排在前面的3篇文章 的 id和title thumb cid
        $articles_title = DB::table('articles')->select('id', 'title', 'thumb')->orderBy('num', 'desc')->limit(5)->get();
        
        // 遍历操作文章数据, 使得文章id为键, 文章标题和缩略图为值
        foreach ($articles_title as $k=>$v) {
            $temp[$v->id] = ['title'=>$v->title, 'thumb'=>$v->thumb];
        }
      
        return $temp;
    }
  
  
    /**
     * 获取栏目的名称
     * @param
     * @return array 包含栏目id和名称的数组 
     */
    public static function getCatesName()
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取所有的栏目id和名称
        $cates_cname_data = DB::table('cates')->select('id', 'cname')->get();

        // 遍历操作栏目数据, 使得栏目id为键, 栏目名称为值
        foreach ($cates_cname_data as $k=>$v) {
            $temp[$v->id] = $v->cname;
        }

        return $temp;
    }

    
    /**
     * 获取头部栏目redis数据
     */
    public static function cate_redis()
    {
        
        if (Redis::exists('cate_redis_data')) {
            $cate_data = json_decode(Redis::get('cate_redis_data'));
        } else {
            // 数据库获取数据
          	$cate_data = self::getPidCates();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('cate_redis_data', json_encode($cate_data));
        }
      
        return $cate_data;
    }
    
    
    /**
     * 获取首页轮播图redis数据
     */
    public static function banner_redis()
    {
        
        if (Redis::exists('banner_redis_data')) {
            $banner_data = json_decode(Redis::get('banner_redis_data'));
        } else {
            // 数据库获取数据
          	$banner_data = DB::table('banners')->where('status', 1)->orderBy('id','asc')->get();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('banner_redis_data', json_encode($banner_data));
        }
      
        return $banner_data;
    }
  
    
    /**
     * 获取标签云redis数据
     */
    public static function tagname_redis()
    {
        
        if (Redis::exists('tagname_redis_data')) {
            $tagname_datas = json_decode(Redis::get('tagname_redis_data'));
        } else {
            // 数据库获取数据
          	$tagname_datas = DB::table('tagnames')->get();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('tagname_redis_data', json_encode($tagname_datas));
        }
      
        return $tagname_datas;
    }
  
   
    /**
     * 获取友情链接redis数据
     */
    public static function friend_redis()
    {
        
        if (Redis::exists('friend_redis_data')) {
            $friend_data = json_decode(Redis::get('friend_redis_data'));
        } else {
            // 数据库获取数据
            $friend_data = DB::table('friends')->get();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('friend_redis_data', json_encode($friend_data));
        }
      
        return $friend_data;
    }
    
  
    /**
     * 获取特别推荐文章redis数据
     */
    public static function special_redis()
    {
        
        if (Redis::exists('special_redis_articles')) {
            $special_articles = json_decode(Redis::get('special_redis_articles'));
        } else {
            // 数据库获取数据
            $special_articles = self::special();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('special_redis_articles', json_encode($special_articles));
            $special_articles = json_decode(Redis::get('special_redis_articles'));
        }
      
        return $special_articles;
    }  
  
    
    /**
     * 获取推荐文章redis数据
     */
    public static function recommend_redis()
    {
        
        if (Redis::exists('recommend_redis_articles')) {
            $recommend_articles = json_decode(Redis::get('recommend_redis_articles'));
        } else {
            // 数据库获取数据
            $recommend_articles = self::recommend();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('recommend_redis_articles', json_encode($recommend_articles));
            $recommend_articles = json_decode(Redis::get('recommend_redis_articles'));
        }
      
        return $recommend_articles;
    }  
  
  
    /**
     * 获取点击排行文章redis数据
     */
    public static function click_redis()
    {
        
        if (Redis::exists('click_redis_articles')) {
            $click_articles = json_decode(Redis::get('click_redis_articles'));
        } else {
            // 数据库获取数据
            $click_articles = self::click_top();
            // 将数据转换成字符串 压入到redis中
        	Redis::set('click_redis_articles', json_encode($click_articles));
            $click_articles = json_decode(Redis::get('click_redis_articles'));
        }
      
        return $click_articles;
    }    
  
  
    /**
     * 前台首页
     */
    public function index()
    {
      	// 获取头部栏目数据
        $cate_data = self::cate_redis();	
      
        // 获取轮播图数据
        $banner_data = self::banner_redis();
      
        // 获取标签云的数据
        $tagname_datas = self::tagname_redis();
      
        // 获取特别推荐文章数据
        $special_articles = self::special_redis();
        
        // 获取推荐文章数据
        $recommend_articles = self::recommend_redis();
      
        // 获取点击排行文章数据
        $click_articles = self::click_redis();
      
        // 获取首页轮播图右边的两篇文章
        $top_right = self::top_right();
        
        // 获取友情链接数据
        $friend_data = self::friend_redis();

        // 获取 首页 默认显示文章数据 12条
        $index_data = DB::table('articles')->orderBy('ctime','desc')->offset(0)->limit(12)->get();

        // 获取包含栏目id和名称的数组
        $cate_name = self::getCatesName();

    	return view('home.index.index', ['top_right'=>$top_right, 'click_articles'=>$click_articles, 'recommend_articles'=>$recommend_articles, 'special_articles'=>$special_articles, 'cate_data'=>$cate_data, 'banner_data'=>$banner_data, 'tagname_datas'=>$tagname_datas, 'friend_data'=>$friend_data, 'index_data'=>$index_data, 'cate_name'=>$cate_name]);
    }
}
