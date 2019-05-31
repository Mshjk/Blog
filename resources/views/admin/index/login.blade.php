<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>BLOG-后台登录</title>
@include('admin.public.header')


<link rel="stylesheet" type="text/css" href="/admin/login/css/style.css">

<script type="text/javascript" src="/admin/login/js/jquery.min.js"></script>
<script type="text/javascript" src="/admin/login/js/vector.js"></script>

</head>
<body>

<div id="container">
	<div id="output">
		<div class="containerT">
			@include('admin.public.message')
			<h1>用户登录</h1>
			<form action="/admin/doLogin" method="post" class="form" id="entry_form">
				{{ csrf_field() }}
				<input type="text" name="uname" placeholder="用户名" id="entry_name" onfocus>
				<input type="password" name="upass" placeholder="密码" id="entry_password">
				<button type="submit" id="entry_btn">登录</button>
				<div id="prompt" class="prompt"></div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(function(){
        Victor("container", "output");   //登录背景函数
        $("#entry_name").focus();
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("#entry_btn").click();
            }
        });
    });
</script>
	<!-- 页脚静态资源开始 -->
	@include('admin.public.footer_static')
	<!-- 页脚静态资源结束 -->
</body>
</html>