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
			            <form action="/admin/article/update/{{$info->id}}" method="post" enctype="multipart/form-data">
			            	{{ csrf_field() }}
			                <div class="form-group">
			                    <label for="title">标题</label> <input type="text" class="form-control" name="title" value="{{$info->title}}" id="title" placeholder="标题">
			                </div>
			                <div class="form-group">
			                    <label for="auth">作者</label> <input type="text" class="form-control" name="auth" value="{{$info->auth}}" id="auth" placeholder="作者">
			                </div>
			                <div class="form-group">
			                    <label for="desc">文章描述</label> 
			                    <textarea name="desc" id="desc" class="form-control">{{ $info->desc }}</textarea>
			                </div>
			                <div class="form-group">
			                    <label for="tid">标签云</label>
			                    <select name="tid" class="form-control" id="tid">  
			                    	@foreach($tdata as $k=>$v)
			                    		<option value="{{ $v->id }}" {{ $info->tid == $v->id ? 'selected' : '' }}> {{ $v->tagname }} </option>
			                    	@endforeach
			                    </select>
			                </div>
			                <div class="form-group">
			                    <label for="cid">所属栏目</label>
			                    <select name="cid" class="form-control" id="cid">
			                    	@foreach($cdata as $k=>$v)
			                    		<option value="{{ $v->id }}" {{ $v->pid == 0 ? 'disabled' : ''}} {{ $info->cid == $v->id ? 'selected' : '' }}>{{ $v->cname }}</option>
			                    	@endforeach
			                    </select>
			                </div>
							<img class="img-thumbnail" src="/uploads/{{$info->thumb}}" width="85px" />
			                <div class="form-group">
			                    <label for="thumb">头像</label>
			                    <input type="hidden" name="yimg" value="{{$info->thumb}}"> 
			                    <input type="file" class="form-control" name="thumb" id="thumb">
			                </div>


			                <div class="form-group">
			                    <label for="container"></label>
			                    <!-- 百度编辑器容器 -->
							    <script style="height: 200px" id="container" name="content" type="text/plain">{!! $info->content !!}</script>
							    <!-- 百度编辑器容器 -->
			                </div>


			                <button type="submit" class="btn btn-default">Submit</button>
			            </form>

			            <!----------- 编辑器的加载 --------------->
					    <!-- 配置文件 -->
					    <script type="text/javascript" src="/utf8-php/ueditor.config.js"></script>
					    <!-- 编辑器源码文件 -->
					    <script type="text/javascript" src="/utf8-php/ueditor.all.js"></script>
					    <!-- 实例化编辑器 -->
					    <script type="text/javascript">
					        var ue = UE.getEditor('container', {
					        	toolbars: [
								    ['fullscreen', 'source', 'undo', 'redo', 'snapscreen', 'italic', 'bold', 'link', 'indent', 'simpleupload', 'insertimage', 'emotion', 'spechars']
								]	
					        });
					    </script>
			            <!------------- 编辑器的加载 ------------->

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