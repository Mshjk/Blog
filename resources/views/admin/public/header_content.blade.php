<!-- 模态框开始 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">修改密码</h4>
      </div>
      <form action="/admin/user/editPass" method="post">
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

<div class="sticky-header header-section ">
	
	<div class="header-left">
		<!--toggle button start-->
		<button id="showLeftPush"><i class="fa fa-bars"></i></button>
		<!--toggle button end-->
		<!--logo -->
		<div class="logo">
			<a href="/admin/user">
				<h1>BLOG</h1>
				<span>AdminPanel</span>
			</a>
		</div>
		<!--//logo-->
		
		<!--search-box-->
		<div class="search-box">
			<form class="input">
				<input class="sb-search-input input__field--madoka" placeholder="Search..." type="search" id="input-31" />
				<label class="input__label" for="input-31">
					<svg class="graphic" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
						<path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
					</svg>
				</label>
			</form>
		</div>
		<!--//end-search-box-->

		<div class="clearfix"> </div>
	</div>

	<div class="header-right">
		<!--notification menu end -->
		<div class="profile_details">		
			<ul>
				<li class="dropdown profile_details_drop">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<div class="profile_img">	
							<span class="prfil-img"><img class="img-circle" src="/uploads/{{ session('admin_userinfo')->profile }}" alt="" width="50px" height="50px"> </span> 
							<div class="user-name">
								<p>{{ session('admin_userinfo')->uname }}</p>
								<span>Administrator</span>
							</div>
							<i class="fa fa-angle-down lnr"></i>
							<i class="fa fa-angle-up lnr"></i>
							<div class="clearfix"></div>	
						</div>	
					</a>
					<ul class="dropdown-menu drp-mnu">
						<li> <a href="/admin/user/edit/{{ session('admin_userinfo')->id }}/{{ session('admin_userinfo')->token }}"><i class="fa fa-user"></i> 用户</a> </li> 
						<li> <a href="javascript:;" onclick="editPass()"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;修改密码</a> </li>
						<li> <a href="/admin/logout"><i class="fa fa-sign-out"></i> 退出</a> </li>
					</ul>
				</li>
			</ul>
		</div>



		<script type="text/javascript">
			function editPass()
			{
				$('#myModal').modal('show');
			}
		</script>

		<div class="clearfix"> </div>				
	</div>

	<div class="clearfix"> </div>	
</div>