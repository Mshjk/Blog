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
					<form class="form-inline" action="/admin/tagname" method="get"> 
						<div class="form-group">
							<label for="exampleInputName2">关键字: </label> 
							<input type="text" class="form-control" name="search" id="exampleInputName2" value="{{ $search }}" placeholder="标签云名"> 
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
						  <th>标签云名</th>
						  <th>颜色</th>
						  <th>操作</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $k=>$v)
							<tr>
								<td>{{ $v->id }}</td>
								<td>{{ $v->tagname }}</td>
								<td><div style="width: 50px; height: 30px; background: {{ $v->bgcolor }}"></div></td>
								<td>
									<a href="javascript:;" onclick="del({{$v->id}}, this)" class="btn btn-danger">删除</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
					{{ $data->appends(['search'=>$search])->links() }}
				

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
		$.get('/admin/tagname/del', {id:id}, function(msg) {
			if (msg == 'ok') {
				$(obj).parent().parent().remove();
			} else {
				alert('删除失败');
			}
		}, 'html');
	}
</script>
</html>