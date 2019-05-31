<!DOCTYPE HTML>
<html>
<head>
@include('admin.public.header')
<style type="text/css">
	.hidd {
		width: 100px;
		overflow: hidden; 
		text-overflow:ellipsis; 
		white-space: nowrap;
	}
</style>
</head> 
<body class="cbp-spmenu-push">
  
  	<!-- 模态框开始 -->
    <div class="modal fade aa" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">

            <h4 class="modal-title" id="myModalLabel">文章内容</h4>
          </div>
          <div class="modal-body">

          </div>
        </div>
      </div>
    </div>
    <!-- 模态框结束 -->
  
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
					<form class="form-inline" action="/admin/article" method="get"> 
						<div class="form-group">
							<label for="exampleInputName2">关键字: </label> 
							<input type="text" class="form-control" name="search" value="{{ $search }}" id="exampleInputName2" value="" placeholder="文章名"> 
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
						  <th>文章标题</th>
						  <th>作者</th>
						  <th>文章描述</th>
						  <th>创建时间</th>
						  <th>缩略图</th>
						  <th>点击量</th>
						  <th>点赞量</th>
						  <th>操作</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $k=>$v)
							<tr>
								<td>{{ $v->id }}</td>
								<td width="100px">
									<p title="{{ $v->title }}" class="hidd">{{ $v->title }}</p>
								</td>
								<td width="100px">
									{{ $v->auth }}
								</td>
								<td width="100px">
									<p class="hidd" title="{{ $v->desc }}">{{ $v->desc }}</p>
								</td>
								<td width="120px">{{ $v->ctime }}</td>
								<td><img src="/uploads/{{ $v->thumb }}" width="50px"></td>
								<td><kbd>{{ $v->num }}</kbd></td>
								<td><kbd style="background: deeppink">{{ $v->goodnum }}</kbd></td>
								
								<td>
									<div class="hidden_content" style="display: none">
										<span>{{ $v->title }}</span>
										<div>{!! $v->content !!}</div>
									</div>
									<a href="javascript:;" onclick="del({{$v->id}}, this)" class="btn btn-danger">删除</a>
									<a href="/admin/article/edit/{{ $v->id }}" class="btn btn-info">修改</a>
									<a href="javascript:;" onclick="shows(this)" class="btn btn-default">查看文章内容</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $data->appends(['search'=>$search])->links() }}
			</div>

			<script type="text/javascript">
				function shows(obj)
				{
					// 获取文章title 和 content
					let title = $(obj).parent().find('.hidden_content').find('span').first().html();
					let content = $(obj).parent().find('.hidden_content').find('div').first().html();

					// 赋值
					$('#myModal .modal-title').html(title);
					$('#myModal .modal-body').html(content);

					$('#myModal').modal('show');
				}
			</script>


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
		$.get('/admin/article/del', {id:id}, function(msg) {
			if (msg == 'ok') {
				$(obj).parent().parent().remove();
			} else {
				alert('删除失败');
			}
		}, 'html');
	}
</script>
</html>