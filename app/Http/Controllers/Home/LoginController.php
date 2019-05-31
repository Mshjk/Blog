<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;

class LoginController extends Controller
{
    /**
     * 显示前台登录界面
     */
    public function login()
    {
    	return view('home.login.login');
    }

    /**
     * 前台登录操作
     */
    public function doLogin(Request $request)
    {

    	// 接收数据
    	$uname = $request->input('uname', '');
    	$upass = $request->input('upass', '');

    	// 获取数据
    	$info = DB::table('users')->where('uname', $uname)->where('status', 1)->first();

    	// 验证用户名
    	if (empty($info)) {
            echo json_encode(['msg'=>'err', 'info'=>'用户名或密码错误']);
    		die;
    	}

    	// 哈希 验证密码
    	if (!Hash::check($upass, $info->upass)) {
            echo json_encode(['msg'=>'err', 'info'=>'用户名或密码错误']);
            die;
    	}

    	// 验证通过 登录
    	session(['home_login'=>true]);
    	session(['home_userinfo'=>$info]);

        echo json_encode(['msg'=>'ok', 'info'=>'登录成功']);
    }


    /**
     * 用户注册界面
     */
    public function register()
    {
    	return view('home.login.register');
    }

    /**
     * 用户注册操作
     */
    public function doRegister(Request $request)
    {
    	// 验证表单
        $this->validate($request, [
            'uname'   => 'required|regex:/^[a-zA-Z]{1}[\w]{5,17}$/',
            'upass'   => 'required|regex: /^[\w]{6,18}$/',
            'reupass' => 'required|same:upass',
            'code'    => 'required|captcha',
        ], [
            'uname.required'   => '用户名必须填写',
            'upass.required'   => '密码必须填写',
            'reupass.required' => '确认密码必须填写',
            'reupass.same'     => '两次密码不一致',
            'uname.regex'      => '用户名必须以字母开头,6到18位的字母数字下划线组成',
            'upass.regex'      => '密码格式错误',
            'code.required' => '验证码不能为空',
            'code.captcha'  => '验证码错误',
        ]);
      
        $data['uname'] = $request->input('uname', '');
        $data['upass'] = Hash::make($request->input('upass', ''));
        $data['token'] = str_random(50);
        $data['status'] = 1;
        $data['ctime'] = date('Y-m-d H:i:s', time());
        $data['profile'] = 'no.png';  
      
        // 执行添加
        $res = DB::table('users')->insert($data);
        if ($res) {
            return redirect('/home/login/login')->with('success', '注册成功');
        } else {
            return back()->with('error', '注册失败');
        }
    }


    /**
     * 退出登录
     */
    public function logout()
    {
        session(['home_login'=>false, 'home_userinfo'=>'']);

        return redirect('/');
    }
}
