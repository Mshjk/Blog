<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DetialController extends Controller
{
	/**
	 * 获取上一条同栏目的文章
	 * @param  $id int
	 * @param  $cid int
	 * @return $res|false object|boolean 上条文章的数据或者false
	 */
	private function prev($id, $cid)
	{
		$res = DB::table('articles')->where('id', '<', $id)->where('cid', $cid)->orderBy('id', 'desc')->first();
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

	/**
	 * 获取下一条同栏目的文章
	 * @param  $id int
	 * @param  $cid int
	 * @return $res|false object|boolean 下条文章的数据或者false
	 */
	private function next($id, $cid)
	{
		$res = DB::table('articles')->where('id', '>', $id)->where('cid', $cid)->orderBy('id', 'desc')->first();
		if ($res) {
			return $res;
		} else {
			return false;
		}
	}

   
    /**
     * 获取文章相关文章
     * @param  $cid  int 文章类别id
     * @param  $id   int 当前文章id
     * @return $temp array
     */
    private static function relate($cid, $id)
    {
        // 预定义数组存取数据
        $temp = [];
        // 获取同个栏目下的文章
        $articles_title = DB::table('articles')->select('id', 'title')->where('cid', $cid)->where('id', '<>', $id)->orderBy('ctime', 'asc')->get();
        
        // 遍历操作文章数据, 使得文章id为键, 文章标题为值
        foreach ($articles_title as $k=>$v) {
            $temp[$v->id] = ['title'=>$v->title];
        }
      
        return $temp;
    }
  
      
    /**
     * 获取文章评论
     * @param  $aid      int    文章id
     * @return $comments object 文章评论数据
     */
     private function getComment($aid)
     {
        // 获取该文章下的评论
        $comments = DB::table('comments')->where('aid', $aid)->orderBy('ctime', 'asc')->get();
        
        // 预定义数组存取数据
        $temp = [];
       
        // 获取评论用户信息
        $users = DB::table('users')->select('id', 'uname', 'profile')->get();
        // 遍历操作用户数据, 使得用户id为键, 用户名 图片为值
        foreach ($users as $k=>$v) {
            $temp[$v->id] = ['uname'=>$v->uname, 'profile'=>$v->profile];
        }
        
        foreach ($comments as $k=>$v) {
            foreach ($temp as $kk=>$vv) {
                if ($v->uid == $kk) {
                    $comments[$k]->uname = $vv['uname'];
                    $comments[$k]->profile = $vv['profile'];
                }
            }
        }
       
        return $comments;
     }
  
       
    /**
     * 文章详情页
     * @param $id int 文章id
     */
	public function index($id)
	{
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
		
		// 修改文章的阅读量
		DB::update("update articles set num=num+1 where id=$id");

		// 获取文章详情
		$article_data = DB::table('articles')->where('id', $id)->first();
		// 文章的类别id
		$cid = $article_data->cid;
		
		// 获取文章类别的主栏目id
		$article_parent_cid = DB::table('cates')->select('pid')->where('id', $cid)->first();
		// 获取文章类别的主栏目名
		$article_parent_cname = DB::table('cates')->select('cname')->where('id', $article_parent_cid->pid)->first();

        // 获取相关文章
        $relate_articles = self::relate($cid, $id);
      
        // 获取分类id为键, 分类名为值的数据
        $cate_name = IndexController::getCatesName();
      
    	// 文章数据添加文章类别名
		foreach ($cate_data as $k=>$v) {
			foreach ($v->sub as $kk=>$vv) {
				if ($vv->id == $article_data->cid) {
					$article_data->cname = $vv->cname;
				}
			}
		}

		// 获取标签云数据
		$tagname_data = DB::table('tagnames')->where('id', $article_data->tid)->first();

        // 上一条 下一条同栏目文章
        $article_prev = self::prev($id, $cid);
        $article_next = self::next($id, $cid);

        // 获取文章评论
        $comments = self::getComment($id);

        // 楼层初始化
        $floor = 1;
      
		return view('home.detial.index', ['floor'=>$floor, 'comments'=>$comments, 'relate_articles'=>$relate_articles, 'click_articles'=>$click_articles, 'recommend_articles'=>$recommend_articles, 'special_articles'=>$special_articles, 'cate_name'=>$cate_name, 'article_parent_cname'=>$article_parent_cname, 'article_prev'=>$article_prev, 'article_next'=>$article_next, 'cate_data'=>$cate_data, 'article_data'=>$article_data, 'tagname_data'=>$tagname_data, 'tagname_datas'=>$tagname_datas, 'friend_data'=>$friend_data]);
	}


	/**
	 * 点赞功能
	 */
	public function goodnum(Request $request)
	{	
		// 获取文章id
		$id = $request->input('id', 0);
		
		// 检测用户是否给该文章点过赞
		// 检测用户是否登录
		if (!session('home_login')) {
			echo json_encode(['msg'=>'err_login', 'info'=>'请先登录']);
			exit;
		}
		// 获取用户id
		$uid = session('home_userinfo')->id;

		$tids = DB::table('users_articles')->where('tid', $id)->get();
		
		$tids_all = [];
		// 把给该文章点过赞的用户获取到数组里
		foreach ($tids as $k=>$v) {
			$tids_all[] = $v->uid;
		}

		// 判断该用户是否给该文章点过赞
		if (in_array($uid, $tids_all)) {
			echo json_encode(['msg'=>'err', 'info'=>'您已点过赞']);
			exit;	
		}
		// 文章点赞数加1
		$res = DB::update('update articles set goodnum=goodnum+1 where id=' . $id);
		// 点赞关系表更新
		DB::table('users_articles')->insert(['tid'=>$id, 'uid'=>$uid]);

		if ($res) {
			echo json_encode(['msg'=>'ok', 'info'=>'+1']);
			exit;
		} else {
			echo json_encode(['msg'=>'err', 'info'=>'点赞失败']);
			exit;
		}
	}
  
    
    /**
     * 文章评论
     */
    public function userComment(Request $request, $aid)
    {
        // 验证用户是否登录
        if (!session('home_login')) {
            echo json_encode(['msg'=>'err', 'info'=>'请先登录']);
            exit;
        }
      
        // 获取数据
        $data['uid'] = session('home_userinfo')->id;
        $data['comment'] = $request->input('comment', '');
        $data['aid'] = $aid;
        $data['ctime'] = date('Y-m-d H:i:s', time());
        
        // 判断comment是否为空
        if ($data['comment'] == null || $data['comment'] == '') {
            echo json_encode(['msg'=>'err', 'info'=>'评论不能为空']);
            exit;
        }
      
        // 插入数据
        $res = DB::table('comments')->insert($data);
        if ($res) {
            echo json_encode(['msg'=>'ok', 'info'=>'评论成功']);
            exit;
        } else {
            echo json_encode(['msg'=>'err', 'info'=>'发表评论失败']); 
            exit;
        }
    }
  
  
}
