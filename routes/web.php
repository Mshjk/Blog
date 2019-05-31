<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// 后台 页面
// 后台 登录页
Route::get('admins', 'Admin\IndexController@login')->name('admin_login');
// 后台登录操作
Route::post('admin/doLogin', 'Admin\IndexController@doLogin');
// 后台 退出登录
Route::get('admin/logout', 'Admin\IndexController@logout');
// 后台 路由
Route::group(['prefix'=>'admin', 'middleware'=>'login'], function() {

	// 后台 用户操作 
	// 后台 用户列表
	Route::get('user', 'Admin\UserController@index');
	// 后台 用户添加
	Route::get('user/create', 'Admin\UserController@create');
	Route::post('user/store', 'Admin\UserController@store');
	// 后台 删除用户
	Route::get('user/del', 'Admin\UserController@del');
	// 后台 修改用户
	Route::get('user/edit/{id}/{token}', 'Admin\UserController@edit');
	Route::post('user/update/{id}', 'Admin\UserController@update');
	// 后台 管理员修改密码
	Route::post('user/editPass', 'Admin\UserController@editPass');

	// 后台 栏目操作
	// 后台 栏目列表
	Route::get('cate/index', 'Admin\CateController@index');
	// 后台 栏目添加
	Route::get('cate/create', 'Admin\CateController@create');
	Route::post('cate/store', 'Admin\CateController@store');


	// 后台 轮播图操作
	// 后台 轮播图列表页
	Route::get('banner/index', 'Admin\BannerController@index');
	// 后台 轮播图添加
	Route::get('banner/create', 'Admin\BannerController@create');
	Route::post('banner/store', 'Admin\BannerController@store');
	// 后台 轮播图改变激活状态
	Route::get('banner/changeStatus', 'Admin\BannerController@changeStatus');
	// 后台 轮播图删除
	Route::get('banner/del', 'Admin\BannerController@del');
	// 轮播图修改
	Route::get('banner/edit/{id}', 'Admin\BannerController@edit');
	Route::post('banner/update/{id}', 'Admin\BannerController@update');


	// 后台 标签云操作
	// 后台 标签云列表页
	Route::get('tagname/index', 'Admin\TagnameController@index');
	// 后台 标签云添加
	Route::get('tagname/create', 'Admin\TagnameController@create');
	Route::post('tagname/store', 'Admin\TagnameController@store');
	// 后台 标签云删除
	Route::get('tagname/del', 'Admin\TagnameController@del');


	// 后台 文章操作
	// 后台 文章列表页
	Route::get('article/index', 'Admin\ArticleController@index');
	// 后台 文章添加
	Route::get('article/create', 'Admin\ArticleController@create');
	Route::post('article/store', 'Admin\ArticleController@store');
	// 后台 文章删除
	Route::get('article/del', 'Admin\ArticleController@del');
	// 后台 文章修改
	Route::get('article/edit/{id}', 'Admin\ArticleController@edit');
	Route::post('article/update/{id}', 'Admin\ArticleController@update');

	// 后台 友情链接
	// 后台 友情链接列表
	Route::get('friend', 'Admin\FriendController@index');
	// 后台 友情链接添加
	Route::get('friend/create', 'Admin\FriendController@create');
	Route::post('friend/store', 'Admin\FriendController@store');
	// 后台 友情链接删除
	Route::get('friend/del', 'Admin\FriendController@del');
	// 后台 友情链接修改
	Route::get('friend/edit/{id}', 'Admin\FriendController@edit');
	Route::post('friend/update/{id}', 'Admin\FriendController@update');
  
    // 后台 评论
    // 后台 评论列表
    Route::get('comment/index', 'Admin\CommentController@index');
    // 后台 评论删除
    Route::get('comment/del', 'Admin\CommentController@del');
});


// 前台 页面
// 前台 登录
Route::get('/home/login/login', 'Home\LoginController@login');
Route::post('/home/login/dologin', 'Home\LoginController@doLogin');
// 前台 退出登录
Route::get('/home/login/logout', 'Home\LoginController@logout');
// 前台 注册
Route::get('/home/login/register', 'Home\LoginController@register');
Route::post('/home/login/doregister', 'Home\LoginController@doRegister');
// 前台 首页
Route::get('/', 'Home\IndexController@index');
// 前台 路由
Route::group(['prefix'=>'home'], function() {
	// 前台 文章列表页
	Route::get('/lists/index', 'Home\ListsController@index');

	// 前台 文章详情页
	Route::get('/detial/index/{aid}', 'Home\DetialController@index');
	Route::get('/detial/goodnum', 'Home\DetialController@goodnum');
  
    // 前台 文章评论
    Route::post('/detial/userComment/{aid}', 'Home\DetialController@userComment');
  
    // 前台 用户信息
    Route::get('/user', 'Home\UserController@index');
    // 前台 用户修改信息
    Route::post('/user/update/', 'Home\UserController@update');
    // 前台 用户修改密码
    Route::post('/user/editPass', 'Home\UserController@editPass');
});
