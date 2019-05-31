<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Hash;

class UserController extends Controller
{
    /**
     * 显示用户列表
     */
    public function index(Request $request)
    {
    	$search = $request->input('search', '');

    	// 获取用户数据
    	$data = DB::table('users')->where('uname','like',"%$search%")->orderBy('id','asc')->paginate(5);
    	return view('admin.user.index', ['data'=>$data,'search'=>$search]);
    }

    /**
     * 显示用户添加页面
     */
    public function create()
    {
    	return view('admin.user.create');  	
    }

    /**
     * 执行用户添加操作
     */
    public function store(Request $request)
    {
    	// 验证表单
    	$this->validate($request, [
	        'uname'   => 'required|regex:/^[a-zA-Z]{1}[\w]{5,17}$/',
	        'upass'	  => 'required|regex: /^[\w]{6,18}$/',
	        'reupass' => 'required|same:upass',
	    ], [
	    	'uname.required'   => '用户名必须填写',
	    	'upass.required'   => '密码必须填写',
	    	'reupass.required' => '确认密码必须填写',
	    	'reupass.same'	   => '两次密码不一致',
	    	'uname.regex'	   => '用户名必须以字母开头,6到18位的字母数字下划线组成',
	    	'upass.regex'	   => '密码格式错误',
	    ]);

    	// 执行文件上传
    	if ($request->hasFile('profile')) {
    		$path = $request->file('profile')->store(date('Ymd'));
    	} else {
    		$path = '';
    	}

    	$data['profile'] = $path;
    	$data['uname'] = $request->input('uname', '');
    	$data['upass'] = Hash::make($request->input('upass', ''));
    	$data['token'] = str_random(50);
    	$data['status'] = 1;
    	$data['ctime'] = date('Y-m-d H:i:s', time());
      
    	// 执行添加
    	$res = DB::table('users')->insert($data);
    	if ($res) {
    		return redirect('admin/user')->with('success', '添加成功');
    	} else {
            // 删除上传的图片
            Storage::delete($path);
    		return back()->with('error', '添加失败');
    	}
    }


    /**
     * 删除用户
     */
    public function del(Request $request)
    {
    	$id = $request->input('id', 0);
    	$token = $request->input('token', '');
    	// 获取数据库token
    	$utoken = DB::table('users')->select('token')->where('id', $id)->first();

    	// 验证token
    	if ($utoken->token != $token) {
    		echo 'err';
    		exit;
    	}
        // 获取要删除的用户的头像
        $info = DB::table('users')->where('id',$id)->first();
        $path = $info->profile;

    	$res = DB::table('users')->where('id',$id)->delete();

    	if ($res) {
            // 如果原图像不是默认图片 删除
            if ($path != 'no.png') {	
              Storage::delete($path);
            }
          
            // 删除该用户发表的评论
            DB::table('comments')->where('uid', $id)->delete();
          
    		echo 'ok';
    	} else {
    		echo 'error';
    	}
    }

    /**
     * 用户修改页面
     */
    public function edit($id, $token)
    {

    	// 查询用户信息
    	$info = DB::table('users')->where('id',$id)->first();

    	// 验证token
    	if ($info->token != $token) {
    		return back()->with('error', 'token验证失败');
    	}

    	// 显示页面
    	return view('admin.user.edit', ['info'=>$info]);
    }


    /**
     * 用户修改操作
     */
    public function update(Request $request, $id)
    {
    	// 验证数据
    	$this->validate($request, [
    		'uname' => 'required|regex:/^[a-zA-Z]{1}[\w]{5,17}$/',
    		'email' => 'required|regex:/^[\w]+@[\w]+\.[\w]+$/'
    	], [
    		'uname.required' => '用户名必须填写',
    		'uname.regex'    => '用户名必须以字母开头的6至18位',
    		'email.required' => '邮箱必须填写',
    		'email.regex'	 => '邮箱格式错误',
    	]);

    	// 创建变量判断是否删除原图片
    	$bool = false;
        // 判断修改的用户是否是登录的用户
        $user_bool = false;

        if ($request->input('yuname') == session('admin_userinfo')->uname) {
            $user_bool = true;
        }
      
    	// 执行文件上传
    	if ($request->hasFile('profile')) {
    		$path = $request->file('profile')->store(date('Ymd'));
	    	$data['profile'] = $path;
	    	$bool = true;
    	}

    	// 接收用户提交的数据
    	$data['uname'] = $request->input('uname', '');
    	$data['email'] = $request->input('email', '');
    	$data['token'] = str_random(50);
        $data['status'] = $request->input('status', 0);
      
    	// 执行修改
    	$res = DB::table('users')->where('id',$id)->update($data);

    	// 判断是否修改成功
    	if ($res) {
    		// 如果新图片上传成功
    		if ($bool) {
                // 如果原图片不是默认图片 不删除
                $yimg = $request->input('yimg', '');
                if ($yimg != 'no.png') {
                  // 删除原图片
                  
                  Storage::delete($yimg);
                }
    		}

            if ($user_bool) {
                // 更新session的数据
                $info = DB::table('users')->where('id',$id)->first();
                session(['admin_userinfo'=>$info]);
            }

    		return redirect('admin/user')->with('success', '修改成功');
    	} else {
			Storage::delete($path);
    		return back()->with('error', '修改失败');
    	}
    }


    /**
     * 后台管理员修改密码
     */
    public function editPass(Request $request)
    {
        // 获取该管理员的信息
        $id = session('admin_userinfo')->id;
        $info = DB::table('users')->where('id', $id)->first();

        // token值比较
        if ($info->token != session('admin_userinfo')->token) {
            return back()->with('error', 'token值不相同!!');
        }

        // 验证提交过来的数据
        $this->validate($request, [
            'upass'   => 'required|regex: /^[\w]{6,18}$/',
        ],[
            'upass.required' => '新密码不能为空',
            'upass.regex'    => '新密码格式错误',
        ]);


        // 对比提交过来的原密码
        $oldUpass = $request->input('oldUpass');
        if (!Hash::check($oldUpass, $info->upass)) {
            return back()->with('error', '原密码不符');
        }

        // 修改密码
        $data['upass'] = Hash::make($request->input('upass'));
        $data['token'] = str_random(50);
        $res = DB::table('users')->where('id', $id)->update($data);

        if ($res) {
            $info = DB::table('users')->where('id', $id)->first();
            session(['admin_userinfo'=>$info]);
            return back()->with('success', '密码修改成功');
        } else {
            return back()->with('error','密码修改失败');
        }
    }
}
