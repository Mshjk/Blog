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

<article> 
  <!--banner begin-->
 <div class="picsbox"> 
  <div class="banner">
    <div id="banner" class="fader">
      <!-- 轮播图 -->
      @foreach($banner_data as $k=>$v)
      <li class="slide" ><a href="javascript:;"><img src="/uploads/{{ $v->url }}"><span class="imginfo">{{ $v->title }}</span></a></li>
      @endforeach
      <!-- 轮播图 -->
      <div class="fader_controls">
        <div class="page prev" data-target="prev">&lsaquo;</div>
        <div class="page next" data-target="next">&rsaquo;</div>
        <ul class="pager_list">
        </ul>
      </div>
    </div>
  </div>
  <!--banner end-->
  
  <!--  -->
  <div class="toppic">
    @foreach ($top_right as $k=>$v)
    <li> <a href="/home/detial/index/{{ $k }}"> <i><img src="/uploads/{{ $v['thumb'] }}"></i>
      <h2>{{ $v['title'] }}</h2>
      <span>{{ $cate_name[$v['cid']] }}</span> </a> </li>
    @endforeach
  </div>
  <!--  -->

  <div class="blank"></div>
  
  <!--blogsbox begin-->
  <div class="blogsbox">
    <!-- 前12条文章列表开始 -->
    @foreach($index_data as $k=>$v)
    <div class="blogs" data-scroll-reveal="enter bottom over 1s" >
      <h3 class="blogtitle"><a href="/home/detial/index/{{ $v->id }}" target="_blank">{{ $v->title }}</a></h3>
      <span class="blogpic"><a href="/home/detial/index/{{ $v->id }}" title=""><img src="/uploads/{{ $v->thumb }}" alt=""></a></span>
      <a class="blogtext" href="/home/detial/index/{{ $v->id }}" title="{{ $v->desc }}">{{ $v->desc }}</a>
      <div class="bloginfo">
        <ul>
          <li class="author"><a href="javascript:;">{{ $v->auth }}</a></li>
          <li class="lmname"><a href="javascript:;">{{ $cate_name[$v->cid] }}</a></li>
          <li class="timer">{{ $v->ctime }}</li>
          <li class="view"><span>{{ $v->num }}</span>已阅读</li>
          <li class="like">{{ $v->goodnum }}</li>
        </ul>
      </div>
    </div>
    @endforeach
    <!-- 前12条文章列表结束 -->
  </div>
  <!--blogsbox end-->

  <!-- 右侧边栏开始 -->
  @include('home/public/slidebar')
  <!-- 右侧边栏结束 -->
</article>

<!-- 加载footer -->
@include('home.public.footer')
<!-- 加载footer -->
</body>
</html>
