<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DB;
use Hash;

class UserController extends Controller
{
    /**
     * 用户信息界面
     */
    public function index()
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
      
        // 获取分类id为键, 分类名为值的数据
        $cate_name = IndexController::getCatesName();    
      
        return view('home.user.index', ['friend_data'=>$friend_data, 'tagname_datas'=>$tagname_datas, 'cate_name'=>$cate_name, 'click_articles'=>$click_articles, 'recommend_articles'=>$recommend_articles, 'special_articles'=>$special_articles, 'cate_data'=>$cate_data]);
    }
  
    
    /**
     * 用户修改信息
     */
    public function update(Request $request)
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
      
    	// 执行修改
    	$res = DB::table('users')->where('id', session('home_userinfo')->id)->update($data);

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

            // 更新session的数据
            $info = DB::table('users')->where('id', session('home_userinfo')->id)->first();
            session(['home_userinfo'=>$info]);

    		return redirect('home/user')->with('success', '修改成功');
    	} else {
			Storage::delete($path);
    		return back()->with('error', '修改失败');
    	}
    }
  
    
    /**
     * 用户修改密码
     */
    public function editPass(Request $request)
    {
        // 获取该管理员的信息
        $id = session('home_userinfo')->id;
        $info = DB::table('users')->where('id', $id)->first();

        // token值比较
        if ($info->token != session('home_userinfo')->token) {
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
            session(['home_userinfo'=>$info]);
            return back()->with('success', '密码修改成功');
        } else {
            return back()->with('error','密码修改失败');
        }
    }
  
  
}
