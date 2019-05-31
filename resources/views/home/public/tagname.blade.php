@foreach($tagname_datas as $k=>$v)
	<a href="/home/lists/index?tagname_id={{ $v->id }}" style="background: {{ $v->bgcolor }}">{{ $v->tagname }}</a>
@endforeach