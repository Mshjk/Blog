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
						<h4>轮播图添加</h4>
					</div>
					<div class="form-body">
						@include('admin.public.message')
			            <form action="/admin/banner/store" method="post" enctype="multipart/form-data">
			            	{{ csrf_field() }}
			                <div class="form-group">
			                    <label for="title">轮播图标题</label> <input type="text" class="form-control" name="title" value="{{old('title')}}" id="title" placeholder="轮播图标题">
			                </div>
			                <div class="form-group">
			                    <label for="desc">轮播图描述</label> <input type="text" class="form-control" name="desc" value="{{old('desc')}}" id="desc" placeholder="轮播图描述">
			                </div>
			                <div class="form-group">
			                    <label for="url">url</label>
			                    <input type="file" name="url" id="url" class="form-control">
			                </div>
			                <div class="form-group form-control">
			                    <label for="status">状态</label>&nbsp;&nbsp;&nbsp;
			                   	未开启: <input type="radio" name="status" value="0">&nbsp;&nbsp;
			                    开启: <input type="radio" name="status" value="1">
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