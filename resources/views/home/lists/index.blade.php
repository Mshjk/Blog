<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>列表页-Jackken</title>
@include('home.public.header')
<!--[if lt IE 9]>
<script src="js/modernizr.js"></script>
<![endif]-->
</head>
<body>
<!-- 加载header_content开始 -->
@include('home.public.header_content')
<!-- 加载header_content结束 -->

<div class="pagebg sh"></div>
<div class="container">
  <h1 class="t_nav"><span>不要轻易放弃。学习成长的路上，我们长路漫漫，只因学无止境。 </span><a href="/" class="n1">网站首页</a>
  <a href="javascript:;" class="n2">
    @if ($cid != 0)
    {{ $cate_name[$cid] }}
    @elseif ($tid != 0)
    {{ $tagname_data->tagname }}
    @endif
  </a></h1>
  <!--blogsbox begin-->
  <div class="blogsbox">
    <!------------ 文章列表开始 ------------->
    @foreach($article_data as $k=>$v)
      <div class="blogs" data-scroll-reveal="enter bottom over 1s" >
        <h3 class="blogtitle"><a href="/home/detial/index/{{ $v->id }}" target="_blank">{{ $v->title }}</a></h3>
        <span class="blogpic"><a href="/home/detial/index/{{ $v->id }}" title=""><img src="/uploads/{{ $v->thumb }}" alt=""></a></span>
        <p class="blogtext">{{ $v->desc }} </p>
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
    <!------------ 文章列表结束 ------------->

    <!-- 文章列表分页按钮开始 -->
    {{ $article_data->appends(Request::except('page'))->links() }} 
    <!-- 文章列表分页按钮结束 -->
  </div>
  <!--blogsbox end-->
  <!-- 右侧边栏开始 -->
  @include('home.public.slidebar')
  <!-- 右侧边栏结束 -->
</div>

@include('home.public.footer')
</body>
</html>
