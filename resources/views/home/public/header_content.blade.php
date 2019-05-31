<header> 
  <!--menu begin-->
  <div class="menu">
    <nav class="nav" id="topnav">
      <h1 class="logo"><a href="/">Jackken</a></h1>
      <!-- 栏目显示开始 -->
      @foreach ($cate_data as $k=>$v)
      <li class="myli"><a href="javascript:;">{{ $v->cname }}</a>
        <ul class="sub-nav">
          @foreach($v->sub as $kk=>$vv)
          <li><a href="/home/lists/index?cid={{ $vv->id }}">{{ $vv->cname }}</a></li>
          @endforeach
        </ul>
      </li>
      @endforeach
      <!-- 栏目显示结束 -->

      <!--search begin-->
      <div id="search_bar" class="search_bar">
        <form  id="searchform" action="/home/lists/index" method="get" name="searchform">
          <input class="input" placeholder="想搜点什么呢..." type="text" name="title" id="keyboard">
          <input type="hidden" value="搜索" />
          <button type="submit"><span class="search_ico"></span></button>
        </form>
      </div>
      <!--search end--> 
        <!-- 登录注册开始 -->
        @if (!session('home_login'))
        <div class="header_login" style="position: relative; right: -612px;">
          <a href="/home/login/login" style="color: #fff;">登录 </a>
          <span>|</span>
          <a href="/home/login/register" style="color: #fff;"> 注册</a>
        </div>
        @else
        <div class="header_login" style="position: relative; right: -520px;">
          <span style="color: #fff">你好!</span><a href="/home/user" style="color: skyblue;"> {{ session('home_userinfo')->uname }}</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="color: skyblue" href="/home/login/logout">退出</a>
        </div>
        @endif
        <!-- 登录注册结束 -->


    </nav>
  </div>
  <!--menu end--> 
  <!--mnav begin-->
  <div id="mnav">
    <h2><a href="http://www.yangqq.com" class="mlogo">Jackken博客</a>
    <span class="navicon"></span></h2>
    <dl class="list_dl">
      @foreach ($cate_data as $k=>$v)
      <dt class="list_dt"> <a href="javascript:;">{{ $v->cname }}</a> </dt>
      <dd class="list_dd">
        <ul>
          @foreach($v->sub as $kk=>$vv)
          <li><a href="/home/lists/index?cid={{ $vv->id }}">{{ $vv->cname }}</a></li>
          @endforeach
        </ul>
      </dd>
      @endforeach
    </dl>
  </div>
  <!--mnav end--> 
</header>