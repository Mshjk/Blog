<!DOCTYPE HTML>
<html>
<head>
@include('admin.public.header')
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<!-- 侧边栏开始 -->
		@include('admin.public.sidebar')
		<!-- 侧边栏结束 -->
		
		<!-- 头部开始 -->
		@include('admin.public.header_content')
		<!-- 头部结束 -->
		
		<!-- 内容开始-->
		<div id="page-wrapper">
			<div class="main-page">

				<!-- 表单出错显示 -->
				@if (count($errors) > 0)
				    <div class="alert alert-danger">
				        <ul>
				            @foreach ($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif

				<div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
					<div class="form-title">
						<h4>友情链接添加</h4>
					</div>
					<div class="form-body">
						@include('admin.public.message')
			            <form action="/admin/friend/update/{{ $info->id }}" method="post" enctype="multipart/form-data">
			            	{{ csrf_field() }}
			                <div class="form-group">
			                    <label for="name">链接名</label> <input type="text" class="form-control" name="name" value="{{ $info->name }}" id="name" placeholder="用户名">
			                </div>
			                <div class="form-group">
			                    <label for="url">链接地址</label> <input type="text" class="form-control" name="url" value="{{ $info->url }}" id="url" placeholder="用户名">
			                </div>

			                <button type="submit" class="btn btn-info">Submit</button>
			            </form>
			        </div>
				</div>
			</div>
			<div class="main-page" style="display: none">
				<div class="row calender widget-shadow">
					<h4 class="title">Calender</h4>
					<div class="cal1">
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<!-- 内容结束 -->

		<!-- 页脚开始 -->
		@include('admin.public.footer')
        <!-- 页脚结束 -->
	</div>
	<!-- 页脚静态资源开始 -->
	@include('admin.public.footer_static')
	<!-- 页脚静态资源结束 -->
</body>
</html>