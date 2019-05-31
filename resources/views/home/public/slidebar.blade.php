  <div class="sidebar">
    <div class="zhuanti">
      <h2 class="hometitle">特别推荐</h2>
      <ul>
        @foreach ($special_articles as $k=>$v)
        <li> <i><img src="/uploads/{{ $v->thumb }}"></i>
          <p>{{ $v->title }} <span><a href="/home/detial/index/{{ $k }}">{{ $cate_name[$v->cid] }}</a></span> </p>
        </li>
        @endforeach
      </ul>
    </div>
    <div class="tuijian">
      <h2 class="hometitle">推荐文章</h2>
      <ul class="sidenews">
        @foreach ($recommend_articles as $k=>$v)
        <li> <i><img src="/uploads/{{ $v->thumb }}"></i>
          <p><a href="/home/detial/index/{{ $k }}">{{ $v->title }}</a></p>
          <span>{{ $v->ctime }}</span> </li>
        @endforeach
      </ul>
    </div>
    <div class="tuijian">
      <h2 class="hometitle">点击排行</h2>
      @foreach ($click_articles as $k=>$v)
      <ul class="tjpic">
        <i><img src="/uploads/{{ $v->thumb }}"></i>
        <p><a href="/home/detial/index/{{ $k }}">{{ $v->title }}</a></p>
      </ul>
      @endforeach
    </div>
    <div class="cloud">
      <h2 class="hometitle">标签云</h2>
      <ul>
        <!-- 显示标签云 -->
        @include('home.public.tagname')
        <!-- 显示标签云 -->
      </ul>
    </div>
    <div class="links">
      <h2 class="hometitle">友情链接</h2>
      <ul>
        @foreach($friend_data as $k=>$v)
        <li><a href="{{ $v->url }}" target="_blank">{{ $v->name }}</a></li>
        @endforeach
      </ul>
    </div>
    <div class="guanzhu" id="follow-us">
      <h2 class="hometitle">关注我们 么么哒！</h2>
      <ul>
        <li class="sina"><a href="javascript:;" target="_blank"><span>新浪微博</span>Jackken博客</a></li>
        <li class="tencent"><a href="javascript:;" target="_blank"><span>腾讯微博</span>Jackken博客</a></li>
        <li class="qq"><a href="javascript:;" target="_blank"><span>QQ号</span>111</a></li>
        <li class="email"><a href="javascript:;" target="_blank"><span>邮箱帐号</span>aaa@qq.com</a></li>

      </ul>
    </div>
  </div>