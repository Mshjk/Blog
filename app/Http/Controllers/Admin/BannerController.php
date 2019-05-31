<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;
use DB;

class BannerController extends Controller
{
    /**
     * 检测banner_redis_data是否存在, 存在则删除
     */
    private static function checkRedis()
    {
        if (Redis::exists('banner_redis_data')) {
        	Redis::del('banner_redis_data');
        }
    }
  
  
	/**
     * 改变激活状态
     */
	public function changeStatus(Request $request)
	{
		$id = $request->input('id');
		$status = $request->input('status');
		if ($status == 0) {
			$data['status'] = 1;
		} else {
			$data['status'] = 0;
		}

		$res = DB::table('banners')->where('id', $id)->update($data);

		if ($res) {
			return back();
		} else {
			return back()->with('error', '改变状态失败');
		}
	}

    /**
     * 轮播图列表页
     */
    public function index()
    {
    	$data = DB::table('banners')->get();
    	return view('admin.banner.index', ['data'=>$data]);
    }

    /**
     * 轮播图添加页
     */
    public function create()
    {
    	return view('admin.banner.create');
    }

    /**
     * 轮播图添加操作
     */
    public function store(Request $request)
    {
        self::checkRedis();
      
    	// 检测文件上传
    	if ($request->hasFile('url')) {
    		$url = $request->file('url')->store(date('Ymd'));
    	} else {
    		return back()->with('error', '请选择图片');
    	}

    	// 接收数据
    	$data['title'] = $request->input('title', '');
    	$data['desc'] = $request->input('desc', '');
    	$data['url'] = $url;
    	$data['status'] = $request->input('status');
    	
    	// 执行添加到数据库
    	$res = DB::table('banners')->insert($data);
    	if ($res) {
    		return redirect('admin/banner/index')->with('success', '添加成功');
    	} else {
            Storage::delete($url);
    		return back()->with('error', '添加失败');
    	}
    }


    /**
     * 轮播图删除
     */
    public function del(Request $request)
    {
      	self::checkRedis();
      	
        $id = $request->input('id', 0);

        // 获取要删除的轮播图的图片
        $info = DB::table('banners')->where('id',$id)->first();
        $path = $info->url;

        $res = DB::table('banners')->where('id',$id)->delete();

        if ($res) {
            Storage::delete($path);
            echo 'ok';
        } else {
            echo 'error';
        }
    }


    /**
     * 轮播图修改页面
     * @param $id int
     */
    public function edit($id)
    {
        // 获取数据
        $info = DB::table('banners')->where('id',$id)->first();

        return view('admin.banner.edit', ['info'=>$info]);
    }

    /**
     * 轮播图修改操作
     * @param $request Request
     * @param $id      int
     */
    public function update(Request $request, $id)
    {	
      	self::checkRedis();
      
        // 创建变量判断是否删除原图片
        $bool = false;

        // 执行文件上传
        if ($request->hasFile('url')) {
            $path = $request->file('url')->store(date('Ymd'));
            $data['url'] = $path;
            $bool = true;
        }

        // 接收数据
        $data['title'] = $request->input('title', '');
        $data['desc'] = $request->input('desc', '');    

        // 执行修改
        $res = DB::table('banners')->where('id',$id)->update($data);

        // 判断是否修改成功
        if ($res) {
            // 如果新图片上传成功
            if ($bool) {
                // 删除原图片
                $yimg = $request->input('yimg', '');
                Storage::delete($yimg);
            }

            return redirect('admin/banner/index')->with('success', '修改成功');
        } else {
            Storage::delete($path);
            return back()->with('error', '修改失败');
        }
    }
}
