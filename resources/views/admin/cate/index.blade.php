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
				<!-- 搜索框开始 -->
				<div class="form-body" data-example-id="simple-form-inline">
					<form class="form-inline" action="/admin/cate" method="get"> 
						<div class="form-group">
							<label for="exampleInputName2">主栏目名: </label> 
							<input type="text" class="form-control" name="search" id="exampleInputName2" value="{{ $search }}" placeholder="主栏目名"> 
						</div> 
						<button type="submit" class="btn btn-info">搜索</button>
					</form> 
				</div>
				<!-- 搜索框结束 -->

				<!-- 导入提示消息 -->
				@include('admin.public.message')
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
				<!-- 表单出错显示 -->					

				<table class="table">
					<thead>
						<tr>
						  <th>id</th>
						  <th>栏目名</th>
						  <th>父级id</th>
						  <th>栏目路径</th>
						  <th>操作</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $v)
						<tr>
							<td>{{ $v->id }}</td>
							<td>
								@if($v->pid == 0)
									<span style="font-weight: bold">{{ $v->cname }}</span>
								@else
									{{ $v->cname }}
								@endif
							</td>
							<td>{{ $v->pid }}</td>
							<td>{{ $v->path }}</td>
							<td>
								@if($v->pid == 0)
									<a href="/admin/cate/create?id={{$v->id}}" class="btn btn-info">添加子栏目</a>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<!-- 显示页码 -->

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