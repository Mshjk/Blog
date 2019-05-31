<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章详情页</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="/layui-v2.4.5/layui/css/layui.css">
@include('home.public.header')

<script src="/layui-v2.4.5/layui/layui.js"></script>
<script>
  // 登录弹框
  layui.use(['layer', 'form'], function(){
    var layer = layui.layer;
  });
</script> 
</head>
<body>
<!-- 加载header_content开始 -->
@include('home.public.header_content')
<!-- 加载header_content结束 -->

<article>
  <h1 class="t_nav"><span>您现在的位置是：首页 > {{ $article_parent_cname->cname }} > {{ $article_data->cname }}</span><a href="/" class="n1">网站首页</a><a href="javascript:;" class="n2">{{ $article_data->cname }}</a></h1>
  <div class="infosbox">
    <div class="newsview">
      <h3 class="news_title">{{ $article_data->title }}</h3>
      <div class="bloginfo">
        <ul>
          <li class="author"><a href="javascript:;">{{ $article_data->auth }}</a></li>
          <li class="lmname"><a href="javascript:;">{{ $article_data->cname }}</a></li>
          <li class="timer">{{ $article_data->ctime }}</li>
          <li class="view">{{ $article_data->num }}已阅读</li>
          <li class="like">{{ $article_data->goodnum }}</li>
        </ul>
      </div>
      <div class="tags">
        <a href="/home/lists/index?tagname_id={{ $tagname_data->id }}" style="background: {{ $tagname_data->bgcolor }}">{{ $tagname_data->tagname }}</a> &nbsp; 
      </div>
      <div class="news_about"><strong>简介</strong>{{ $article_data->desc }}</div>
      <div class="news_con"> 
        {!! $article_data->content !!}
      </div>
    </div>
    <div class="share">
      <p class="diggit"><a id="zan" href="JavaScript:;" onclick="goodnum({{ $article_data->id }})"> 很赞哦！ </a></p>
      <p class="dasbox"><a href="javascript:void(0)" onClick="dashangToggle()" class="dashang" title="打赏，支持一下">打赏本站</a></p>
      <div class="hide_box"></div>
      <div class="shang_box"> <a class="shang_close" href="javascript:void(0)" onclick="dashangToggle()" title="关闭">关闭</a>
        <div class="shang_tit">
          <p>感谢您的支持，我会继续努力的!</p>
        </div>
        <div class="shang_payimg"> <img src="images/alipayimg.jpg" alt="扫码支持" title="扫一扫"> </div>
        <div class="pay_explain">扫码打赏，你说多少就多少</div>
        <div class="shang_payselect">
          <div class="pay_item checked" data-id="alipay"> <span class="radiobox"></span> <span class="pay_logo"><img src="images/alipay.jpg" alt="支付宝"></span> </div>
          <div class="pay_item" data-id="weipay"> <span class="radiobox"></span> <span class="pay_logo"><img src="images/wechat.jpg" alt="微信"></span> </div>
        </div>

        <script type="text/javascript">
          // 点赞
          function goodnum(id)
          {
            let like = $('.like').first();
            $.get('/home/detial/goodnum', {id:id}, function (res) {
              if (res.msg == 'ok') {
                layer.msg(res.info);
                like.html(parseInt(like.html()) + 1);
                $('#zan').html('已点赞');
              } else if(res.msg == 'err_login') {
                layer.msg(res.info);
              } else {
                layer.msg(res.info);
                $('#zan').html('已点赞');
              }
            }, 'json');
          }
        </script>

        <script type="text/javascript">
    $(function(){
    	$(".pay_item").click(function(){
    		$(this).addClass('checked').siblings('.pay_item').removeClass('checked');
    		var dataid=$(this).attr('data-id');
    		$(".shang_payimg img").attr("src","/home/images/"+dataid+"img.jpg");
    		$("#shang_pay_txt").text(dataid=="alipay"?"支付宝":"微信");
    	});
    });
    function dashangToggle(){
    	$(".hide_box").fadeToggle();
    	$(".shang_box").fadeToggle();
    }
    </script> 
      </div>
    </div>
    <div class="nextinfo">
      @if ($article_prev)
      <p>上一篇：<a href="/home/detial/index/{{ $article_prev->id }}">{{ $article_prev->title }}</a></p>
      @else
      <p>上一篇: 没有了</p>
      @endif

      @if ($article_next)
      <p>下一篇：<a href="/home/detial/index/{{ $article_next->id }}">{{ $article_next->title }}</a></p>
      @else
      <p>下一篇: 没有了</p>
      @endif
    </div>
    <div class="otherlink">
      <h2>相关文章</h2>
      <ul>
        @foreach ($relate_articles as $k=>$v)
        <li><a href="/home/detial/index/{{ $k }}" title="{{ $v['title'] }}">{{ $v['title'] }}</a></li>
        @endforeach
      </ul>
    </div>
    <div class="news_pl">
      <h2>文章评论</h2>
      <ul>
        <div class="gbko"> </div>
      </ul>
      <ul>
        @foreach ($comments as $k=>$v)
        <div class="gbko" style="position: relative;"> 
          <div style="font-size: 12px; position: absolute; left: 2px; top: -10px;"><img src="/uploads/{{ $v->profile }}" class="img-circle" style="width: 40px; height: 40px; display: inline-block">&nbsp;&nbsp;&nbsp;
            {{ $floor++ }}楼  <span style="margin-left: 50px">{{ $v->uname }}</psan>  <span style="margin-left: 50px">发表时间: {{ $v->ctime }}</span></div>
          <div style="word-break: break-all; margin-top: 30px;">{{ $v->comment }}</div>
        </div>
        <hr style="border-color: #767676">
        @endforeach
      </ul>
      <textarea id="comment" name="comment" cols="99" rows="5"></textarea>
      <button onclick="setComment({{ $article_data->id }})" class="btn btn-info" style="float:right; width: 150px">评论</button>
    </div>
  </div>
  
<script type="text/javascript">
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  
  function setComment(aid)
  {
      
      let comments = $('#comment').eq(0);
      comment = comments.val();
      $.post("/home/detial/userComment/"+aid, {comment}, function(res) {
            if (res.msg == 'ok') {
                layer.msg(res.info);
                comments.val('');
                setTimeout(window.location.reload(), 1000);
            } else {
           	    layer.msg(res.info);
            }
      }, 'json');
  }
</script>
  
  
  <!-- 右侧边栏开始 -->
  @include('/home/public/slidebar')
  <!-- 右侧边栏结束 -->
</article>
<footer>
  <p>Design by <a href="http://www.yangqq.com" target="_blank">Jackken个人博客</a> <a href="/">蜀ICP备11002373号-1</a></p>
</footer>
<a href="#" class="cd-top">Top</a>

</body>

</html>
