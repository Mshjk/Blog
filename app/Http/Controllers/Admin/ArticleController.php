<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use DB;

class ArticleController extends Controller
{
    /**
     * 检测click_redis_articles recommend_redis_articles special_redis_articles是否存在, 存在则删除
     */
    private static function checkRedis()
    {
        if (Redis::exists('click_redis_articles')) {
        	Redis::del('click_redis_articles');
        }
        if (Redis::exists('recommend_redis_articles')) {
        	Redis::del('recommend_redis_articles');
        }
      	if (Redis::exists('special_redis_articles')) {
        	Redis::del('special_redis_articles');
        }
    }  
  
  
    /**
     * 文章列表页
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

    	// 获取文章数据
    	$data = DB::table('articles')->where('title','like',"%$search%")->orderBy('id','asc')->paginate(3);
    	return view('admin.article.index', ['data'=>$data, 'search'=>$search]);
    }


    /**
     * 文章添加页
     */
    public function create()
    {
    	// 获取标签云数据
    	$tdata = DB::table('tagnames')->get();
    	// 获取栏目数据
    	$cdata = CateController::getCates();
    	$cdata = CateController::getStr($cdata);

    	return view('admin.article.create', ['tdata'=>$tdata, 'cdata'=>$cdata]);
    }

    /**
     * 文章添加操作
     */
    public function store(Request $request)
    {
        self::checkRedis();
      
    	// 检测数据
    	$this->validate($request, [
    		'title'   => 'required|regex:/^.{1,128}$/',
    		'auth'    => 'required|regex:/^.{1,32}$/',
    		'desc'    => 'required|regex:/^.{1,255}$/',
    		'tid'     => 'required',
    		'cid'     => 'required',
    		'content' => 'required',
    	], [
    		'title.required' 	=> '标题不能为空',
    		'title.regex'    	=> '标题字数过多',
			'auth.required'  	=> '作者名不能为空',
    		'auth.regex'     	=> '作者名字数过多',
    		'desc.required'  	=> '描述不能为空',
    		'desc.regex'     	=> '描述字数过多',
    		'tid.required'	 	=> '标签云不能为空',
    		'cid.required'	 	=> '所属栏目不能为空',
    		'content.required'  => '文章内容不能为空',
    	]);

    	// 检测是否上传文章图,有则上传服务器,没有则返回
    	if ($request->hasFile('thumb')) {
    		$thumb = $request->file('thumb')->store(date('Ymd'));
    	} else {
    		return back()->with('error', '请选择图片');
    	}

    	// 获取数据
    	$data = $request->except(['_token', 'thumb', '/admin/article/store']);
    	$data['thumb'] = $thumb;
    	$data['ctime'] = date('Y-m-d H:i:s', time());
    	$data['num'] = mt_rand(1024,2048);
    	$data['goodnum'] = mt_rand(512,1024);

    	// 上传数据
    	$res = DB::table('articles')->insert($data);
    	if ($res) {
    		return redirect('admin/article/index')->with('success', '添加成功');
    	} else {
    		// 删除上传的图片
    		Storage::delete($thumb);
    		return back()->with('error', '添加失败');
    	}
    }


    /**
     * 文章删除
     */
    public function del(Request $request)
    {
        self::checkRedis();
      
        $id = $request->input('id', 0);

        // 获取要删除的文章的缩略图
        $info = DB::table('articles')->where('id',$id)->first();
        $path = $info->thumb;

        $res = DB::table('articles')->where('id',$id)->delete();

        if ($res) {
            // 删除该文章下的评论
            DB::table('comments')->where('aid', $id)->delete();
            Storage::delete($path);
            echo 'ok';
        } else {
            echo 'error';
        }
    }


    /**
     * 文章修改页面
     * @param $id int 
     */
    public function edit($id)
    {  
        // 查询文章信息
        $info = DB::table('articles')->where('id',$id)->first();
        // 获取标签云数据
        $tdata = DB::table('tagnames')->get();
        // 获取栏目数据
        $cdata = CateController::getCates();
        $cdata = CateController::getStr($cdata);

        // 显示页面
        return view('admin.article.edit', ['info'=>$info, 'tdata'=>$tdata, 'cdata'=>$cdata]);
    }

    /**
     * 文章修改操作
     * @param $request Request
     * @param $id      int
     */
    public function update(Request $request, $id)
    {
        self::checkRedis();
      
        // 验证数据
        $this->validate($request, [
            'title'   => 'required|regex:/^.{1,128}$/',
            'auth'    => 'required|regex:/^.{1,32}$/',
            'desc'    => 'required|regex:/^.{1,255}$/',
            'tid'     => 'required',
            'cid'     => 'required',
            'content' => 'required',
        ], [
            'title.required'    => '标题不能为空',
            'title.regex'       => '标题字数过多',
            'auth.required'     => '作者名不能为空',
            'auth.regex'        => '作者名字数过多',
            'desc.required'     => '描述不能为空',
            'desc.regex'        => '描述字数过多',
            'tid.required'      => '标签云不能为空',
            'cid.required'      => '所属栏目不能为空',
            'content.required'  => '文章内容不能为空',
        ]);

        // 创建变量判断是否删除原图片
        $bool = false;

        // 接收用户提交的数据
        $data['title']   = $request->input('title');
        $data['auth']    = $request->input('auth');
        $data['desc']    = $request->input('desc');
        $data['tid']     = $request->input('tid');
        $data['cid']     = $request->input('cid');
        $data['content'] = $request->input('content');
        // 执行文件上传
        if ($request->hasFile('thumb')) {
            $path = $request->file('thumb')->store(date('Ymd'));
            $data['thumb'] = $path;
            $bool = true;
        }

        // 执行修改
        $res = DB::table('articles')->where('id',$id)->update($data);

        // 判断是否修改成功
        if ($res) {
            // 如果新图片上传成功
            if ($bool) {
                // 删除原图片
                $yimg = $request->input('yimg', '');
                Storage::delete($yimg);
            }

            return redirect('admin/article/index')->with('success', '修改成功');
        } else {
            Storage::delete($path);
            return back()->with('error', '修改失败');
        }
    }
}
