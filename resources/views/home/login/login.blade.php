<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>BLOG-登录</title>
	<link href="/admin/css/bootstrap.css" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" type="text/css" href="/home/css/login.css">
	<link rel="stylesheet" type="text/css" href="/home/css/font/iconfont.css">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="/layui-v2.4.5/layui/css/layui.css">
	<script src="/layui-v2.4.5/layui/layui.js"></script>

	@include('home.public.header')
	<script>
		// 登录弹框
		layui.use(['layer', 'form'], function(){
			var layer = layui.layer;
		});
	</script> 
</head>
<body>
	<div class="layout_box" >
		<div class="layout_top">
			<h4 class="layout_t_title">BLOG-登录</h4>
		</div>
		<div style="margin-left: 30px">
			@include('admin.public.message')
		</div>		
		<div class="layout_bottom" style="margin-top: 60px;">
			<div class="layout_bottom_xx">
				<form action="javascript:;">
					<input type="text" name="uname" class="username" placeholder="用户名">
					<input type="password" name="upass" class="password" placeholder="密码">
					<input type="submit" onclick="login()" class="btn" value="登录" style="width: 330px; height: 30px;">
				</form>
				<div class="other_login">
					<div class="zc_r r">
						<a class="zc a0" href="/home/login/register" target="_self" style="right: 13px; top: 5px; position: relative">立即注册</a>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="login_footer">
		<a style="color: black" class="foot_a" href="javascript:;">简体</a>
		<a class="foot_a" href="javascript:;">繁体</a>
		<a class="foot_a" href="javascript:;">English</a>
		<a class="foot_a" href="javascript:;">常见问题</a>
		<a style="border: 0;" class="foot_a" href="javascript:;">隐私政策</a>
		<p>xxxx公司版权所有-京ICP备10046444-京公网安备11010802020134号-京ICP证110507号
		</p>
	</div>
</body>
@include('admin.public.footer_static')
<script type="text/javascript">
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	function login()
	{
		let uname = $('.layout_bottom_xx .username').eq(0).val();
		let upass = $('.layout_bottom_xx .password').eq(0).val();
		$.post('/home/login/dologin', {uname:uname, upass:upass}, function (res) {
			if (res.msg == 'ok') {
				layer.msg(res.info);
				// 跳转主页
				window.location.href = '/';
			} else {
				layer.msg(res.info);
			}
		}, 'json');
	}
</script>
</html>