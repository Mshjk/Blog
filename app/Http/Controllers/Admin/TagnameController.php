<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use DB;

class TagnameController extends Controller
{
    /**
     * 检测tagname_redis_data是否存在, 存在则删除
     */
    private static function checkRedis()
    {
        if (Redis::exists('tagname_redis_data')) {
        	Redis::del('tagname_redis_data');
        }
    }
  
    /**
     * 标签云列表页
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');

    	// 获取数据
    	$data = DB::table('tagnames')->orderBy('id','asc')->where('tagname','like',"%$search%")->orderBy('id','asc')->paginate(3);
    	return view('admin.tagname.index', ['data'=>$data, 'search'=>$search]);
    }


    /**
     * 标签云创建页面
     */
    public function create()
    {
    	return view('admin.tagname.create');
    }

    /**
     * 创建操作
     */
    public function store(Request $request)
    {
        self::checkRedis();
      
    	// 验证数据
    	$this->validate($request, [
    		'tagname' => 'required|regex:/^.{1,15}$/',
    		'bgcolor' => 'required',
    	], [
    		'tagname.required' => '标签云名不能为空',
    		'tagname.regex'	   => '标签云名长度为1-5位汉字或15位以下的非汉字字符',
    		'bgcolor.required' => '标签云背景不能为空'
    	]);

    	// 接收数据
    	$data['tagname'] = $request->input('tagname');
    	$data['bgcolor'] = $request->input('bgcolor', '#333');

    	$res = DB::table('tagnames')->insert($data);
    	if ($res) {
    		return redirect('admin/tagname/index')->with('success', '添加成功');
    	} else {
    		return back()->with('error', '添加失败');
    	}
    }

    /**
     * 标签云删除
     */
    public function del(Request $request)
    {
        self::checkRedis();
      
        $id = $request->input('id', 0);

        // 查看该标签云下是否有文章
        $res = DB::table('articles')->where('tid',$id)->first();
        if (!empty($res->title)) {
            echo 'error';
            die;
        }

        $res = DB::table('tagnames')->where('id',$id)->delete();

        if ($res) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }
}
