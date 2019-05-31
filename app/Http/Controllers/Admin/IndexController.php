<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;

class IndexController extends Controller
{
    /**
     * 显示后台登录页面
     */
    public function login()
    {
    	// 加载后台登录页面
    	return view('admin.index.login');
    }

    /**
     * 登录操作
     */
    public function doLogin(Request $request)
    {
    	// 接收数据
    	$uname = $request->input('uname', '');
    	$upass = $request->input('upass', '');

    	// 获取数据
    	$info = DB::table('users')->where('uname', $uname)->first();

    	// 如果用户名正确
    	if (empty($info)) {
    		return back()->with('error', '用户名或密码错误');
    	}

    	// 哈希 验证密码
    	if (!Hash::check($upass, $info->upass)) {
    		return back()->with('error', '用户名或密码错误');
    	}

        // 验证权限
        if ($info->type != 1) {
            return back()->with('error', '用户无权登入');
        }

    	// 验证通过 登录
    	session(['admin_login'=>true]);
    	session(['admin_userinfo'=>$info]);

    	// 跳转
    	return redirect('admin/user/index');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
    	session(['admin_login'=>false, 'admin_userinfo'=>'']);

    	return redirect('admins')->with('success', '退出成功');
    }
}
