<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use DB;

class FriendController extends Controller
{
    /**
     * 检测friend_redis_data是否存在, 存在则删除
     */
    private static function checkRedis()
    {
        if (Redis::exists('friend_redis_data')) {
        	Redis::del('friend_redis_data');
        }
    }
  
  
    /**
     * 友情链接列表页
     */
    public function index()
    {
    	// 获取友情链接数据
    	$data = DB::table('friends')->orderBy('id','asc')->paginate(5);
    	return view('admin.friend.index', ['data'=>$data]);
    }


    /**
     * 友情链接添加页
     */
    public function create()
    {
    	return view('admin.friend.create');
    }

    /**
     * 友情链接添加操作
     */
    public function store(Request $request)
    {	
      	self::checkRedis();
      
    	// 验证数据
    	$this->validate($request, [
    		'name' => 'required|regex:/^.{1,35}$/',
    		'url'  => 'required',
    	], [
    		'name.required' => '链接名不能为空',
    		'name.regex'    => '链接名长度有误',
    		'url.required'  => '链接地址不能为空',
    	]);
    	$data['name'] = $request->input('name','');
    	$data['url'] = $request->input('url','');

    	// 执行添加
    	$res = DB::table('friends')->insert($data);

    	if ($res) {
    		return redirect('admin/friend/index')->with('success', '添加成功');
    	} else {
    		return back()->with('error', '添加失败');
    	}
    }


    /**
     * 修改页面
     */
    public function edit($id)
    {
    	// 查询友情链接信息
    	$info = DB::table('friends')->where('id',$id)->first();

    	return view('admin.friend.edit',['info'=>$info]);
    }


    /**
     * 修改操作
     */
    public function update(Request $request, $id)
    {
      	self::checkRedis();
      
    	// 验证数据
    	$this->validate($request, [
    		'name' => 'required|regex:/^.{1,35}$/',
    		'url'  => 'required',
    	], [
    		'name.required' => '链接名不能为空',
    		'name.regex'    => '链接名长度有误',
    		'url.required'  => '链接地址不能为空',
    	]);

    	// 接收数据
    	$data['name'] = $request->input('name', '');
    	$data['url'] = $request->input('url', '');

    	// 修改
    	$res = DB::table('friends')->where('id', $id)->update($data);
    	// 判断是否修改成功
    	if ($res) {
    		return redirect('admin/friend/index')->with('success', '修改成功');
    	} else {
    		return back()->with('error', '修改失败');
    	}
    }


    /**
     * 删除友情链接
     */
    public function del(Request $request)
    {
      	self::checkRedis();
      
        $id = $request->input('id', 0);

        $res = DB::table('friends')->where('id',$id)->delete();

        if ($res) {
            echo 'ok';
        } else {
            echo 'error';
        }
    }
}
