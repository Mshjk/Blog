<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>BLOG-注册</title>
	<link rel="stylesheet" type="text/css" href="/home/css/register.css">
	<link rel="stylesheet" type="text/css" href="/home/css/font/iconfont.css">
	@include('home.public.header')
</head>
<body>
	<div class="layout_box">
		<form action="/home/login/doregister" method="post">
			{{ csrf_field() }}
			<div class="layout_top">
				<h4 class="layout_t_title">注册BLOG</h4>
			</div>

			<!-- 表单出错显示 -->
			@if (count($errors) > 0)
			    <div class="alert alert-danger" style="width: 303px; margin-left: 290px;">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif

			<div class="layout_bottom" style="margin-top: 20px">
				<div style="margin-left: 30px">
					@include('admin.public.message')
				</div>
				<div style="width: 100%; height: 56px;">
					<input type="text" class="sj" name="uname" placeholder="请输入用户名"> <br />
					<input type="password" class="sj" name="upass" placeholder="请输入密码">
					<input type="password" class="sj" name="reupass" placeholder="请确认密码">
                  	<input type="text" class="sj" name="code" placeholder="验证码" style="width: 40%; display: inline-block; float: left; margin-left: 31px"><img src="{{captcha_src()}}" onclick="this.src='{{captcha_src()}}'+Math.random()" style="display: inline-block; border-radius: 5px; margin-left: 10px; margin-top: 10px;">
					<div class="other_login">
						<div class="zc_r r">
							<a class="zc a0" href="/home/login/login" target="_self" style=" top: 5px; position: relative">去登录</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<input type="submit" class="btn" value="注册" style="width: 303px; height: 30px; margin-left: 30px">
			</div>
			<div class="tk">
				注册帐号即表示您同意并愿意遵守 <a href="javascript:;">用户协议</a>和 <a href="javascript:;">隐私政策</a>
			</div>
		</form>
	</div>
	<div class="register_footer">
		<a style="color: black" class="foot_a" href="javascript:;">简体</a>
		<a class="foot_a" href="javascript:;">繁体</a>
		<a class="foot_a" href="javascript:;">English</a>
		<a class="foot_a" href="javascript:;">常见问题</a>
		<a style="border: 0;" class="foot_a" href="">隐私政策</a>
		<p>xxxx公司版权所有-京ICP备10046444-京公网安备11010802020134号-京ICP证110507号
		</p>
	</div>
</body>
@include('admin.public.footer_static')
</html>