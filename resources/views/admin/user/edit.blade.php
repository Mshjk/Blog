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
						<h4>用户修改</h4>
					</div>
					<div class="form-body">
						@include('admin.public.message')
			            <form action="/admin/user/update/{{ $info->id }}" method="post" enctype="multipart/form-data">
			            	{{ csrf_field() }}
			                <div class="form-group">
			                    <label for="uname">用户名</label> <input type="text" class="form-control" name="uname" value="{{ $info->uname }}" id="uname" placeholder="用户名">
                                <input type="hidden" name="yuname" value="{{$info->uname}}">
			                </div>
			                <div class="form-group">
			                    <label for="email">邮箱</label> <input type="text" class="form-control" name="email" value="{{ $info->email }}" id="email" placeholder="邮箱">
			                </div>
			                <div class="form-group form-control">
			                    <label for="status">激活状态</label>
			                    不激活:<input type="radio" name="status" value="0" {{ $info->status == 0 ? 'checked' : '' }}>
			                    激活:<input type="radio" name="status" value="1" {{ $info->status == 1 ? 'checked' : '' }}>
			                </div>
							<img class="img-thumbnail" src="/uploads/{{ $info->profile }}" style="width: 85px; height: 85px" />
			                <div class="form-group">
			                    <label for="profile">头像</label>
			                    <input type="hidden" name="yimg" value="{{ $info->profile }}"> 
			                    <input type="file" class="form-control" name="profile" id="profile">
			                </div>
			                <button type="submit" class="btn btn-default">Submit</button>
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