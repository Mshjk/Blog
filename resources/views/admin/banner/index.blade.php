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
						  <th>标题</th>
						  <th>图片</th>
						  <th>状态</th>
						  <th>操作</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $k=>$v)
							<tr>
								<td>{{ $v->id }}</td>
								<td><p style="width: 170px">{{ $v->title }}</p></td>
								<td>
									<img title="{{ $v->desc }}" src="/uploads/{{ $v->url }}" width="100px">
								</td>
								<td>
									@if ($v->status == 0)
										<kbd>未激活</kbd>
									@else
										<kbd style="background: skyblue">激活</kbd>
									@endif
								</td>
								<td>
									<a href="/admin/banner/edit/{{ $v->id }}" class="btn btn-danger">修改</a>
									<a href="javascript:;" onclick="del({{$v->id}}, this)" class="btn btn-info">删除</a>
									@if ($v->status == 0)
										<a href="/admin/banner/changeStatus?id={{$v->id}}&status={{$v->status}}" class="btn btn-success">激活</a>
									@else
										<a href="/admin/banner/changeStatus?id={{$v->id}}&status={{$v->status}}" class="btn btn-default">停止</a>
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
<script type="text/javascript">
	function del(id, obj)
	{
		if (!window.confirm('你确定删除吗?')) {
			return false;
		}
		$.get('/admin/banner/del', {id:id}, function(msg) {
			if (msg == 'ok') {
				$(obj).parent().parent().remove();
			} else {
				alert('删除失败');
			}
		}, 'html');
	}
</script>
</html>