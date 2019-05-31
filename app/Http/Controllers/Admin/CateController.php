<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use DB;

class CateController extends Controller
{
    /**
     * 检测cate_redis_data是否存在, 存在则删除
     */
    private static function checkRedis()
    {
        if (Redis::exists('cate_redis_data')) {
        	Redis::del('cate_redis_data');
        }
    }
  
	/**
     * 数据排序操作
     * @return $data Object
     */
	public static function getCates()
	{
		$data = DB::table('cates')->select('*',DB::raw('concat(path,",",id) as paths'))->orderBy('paths', 'asc')->get();
		
		return $data;
	}

    /**
     * 子类数据前加|----
     * @param  $data Object
     * @return $data Object
     */
    public static function getStr($data)
    {
        foreach($data as $k=>$v) {
            if (substr_count($v->path, ',') > 0) {
                $data[$k]->cname = str_repeat("|-----", substr_count($v->path, ',')) . $v->cname;
            }
        }
        return $data;
    }

    /**
     * 栏目列表
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        // 预定义变量
        $info = [];
        // 如果执行了查询操作
        if ($request->filled('search')) {
            $info = DB::table('cates')->select('id')->where([
                ['cname', 'like', "%$search%"],
                ['pid', '=', 0]
            ])->get();
        }
        // 如果查询的内容不为空
        if (count($info) > 0) {
            $list = [];
            // 获取查询的数据的id
            foreach($info as $v) {
                $list[] = $v->id;
            }
            // 用,拼接
            $list = implode(',', $list);
            // 查询这些id的主分类以及这些主分类的子分类
            $data = DB::select("select *,concat(path,',',id) as paths from cates where id in($list) or pid in($list) order by paths asc");
        } else {
            $data = self::getCates();
        }
        // 子类栏目前添加|----
        $data = self::getStr($data);

    	return view('admin.cate.index', ['data' => $data, 'search' => $search]);
    }

    /**
     * 栏目添加页面
     */
    public function create(Request $request)
    {
    	$id = $request->input('id', '');
    	return view('admin.cate.create', ['data' => self::getStr(self::getCates()), 'id' => $id]);
    }

    /**
     * 栏目添加操作
     */
    public function store(Request $request)
    {
        self::checkRedis(); 
       
    	$this->validate($request, [
    		'cname' => 'required',
    	], [
    		'cname.required' => '栏目名不能为空',
    	]);

    	// 获取pid
    	$pid = $request->input('pid');

    	// 获取路径
    	if ($pid == 0) {
    		$path = 0;
    	} else {
    		// 获取父级数据
    		$parent_data = DB::table('cates')->where('id', $pid)->first();
    		$path = $parent_data->path . ',' . $parent_data->id;
    	}

    	$data['pid'] = $pid;
    	$data['cname'] = $request->input('cname', '');
    	$data['path'] = $path;

    	// 添加数据
    	$res = DB::table('cates')->insert($data);
    	if ($res) {
    		return redirect('/admin/cate/index')->with('success', '添加成功');
    	} else {
    		return back()->with('error', '添加失败');
    	}
    }
}
