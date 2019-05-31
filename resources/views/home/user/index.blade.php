<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>首页_Jackken个人博客</title>
@include('home.public.header')
</head>
<body>
<!-- 加载header_content开始 -->
@include('home.public.header_content')
<!-- 加载header_content结束 -->
  
<!-- 模态框开始 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">修改密码</h4>
      </div>
      <form action="/home/user/editPass" method="post">
          {{ csrf_field() }}
          <div class="modal-body">
                <label for="oldUpass">原密码: </label><input type="password" name="oldUpass" id="oldUpass">
                <label for="upass">新密码: </label><input type="password" name="upass" id="upass">
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="确认修改">
          </div>
      </form>
    </div>
  </div>
</div>
<!-- 模态框结束 -->  
  
  
<article> 
  <!-- 用户信息表单开始 -->

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

    <div>
        @include('admin.public.message')
        <form action="/home/user/update" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="uname">用户名</label> <input type="text" class="form-control" name="uname" value="{{ session('home_userinfo')->uname }}" id="uname" placeholder="用户名">
                <input type="hidden" name="yuname" value="{{ session('home_userinfo')->uname }}">
            </div>
            <div class="form-group">
                <label for="email">邮箱</label> <input type="text" class="form-control" name="email" value="{{ session('home_userinfo')->email }}" id="email" placeholder="邮箱">
            </div>
            <img class="img-thumbnail" src="/uploads/{{ session('home_userinfo')->profile }}" style="width: 85px; height: 85px" />
            <div class="form-group">
                <label for="profile">头像</label>
                <input type="hidden" name="yimg" value="{{ session('home_userinfo')->profile }}"> 
                <input type="file" class="form-control" name="profile" id="profile">
            </div>
            <button type="submit" class="btn btn-default">修改信息</button>
        </form>
        <br />
        <button class="btn btn-danger" onclick="showModal()">修改密码</button>
  	</div>
    <script type="text/javascript">
    	function showModal()
        {
            $('#myModal').modal('show');
        }
    </script>
  <!-- 用户信息表单结束 -->
  
  <!-- 右侧边栏开始 -->
  @include('home/public/slidebar')
  <!-- 右侧边栏结束 -->
</article>

<!-- 加载footer -->
@include('home.public.footer')
<!-- 加载footer -->
</body>
</html>
