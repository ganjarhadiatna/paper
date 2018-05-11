@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
    var path = '{{ $sekPath }}';
    $(document).ready(function () {
        $('#explore-nav ul li').each(function () {
            $(this).removeClass('active');
        });
        $('#explore-nav ul #'+path).addClass('active');
    });
</script>
<div class="col-900px padding-20px">
    <div class="padding-20px">
        <h1 class="ctn-main-font ctn-big ctn-sek-color">
            Explore Now's
        </h1>
    </div>
    <div class="padding-bottom-20px">
        <div class="navigator nav-all nav-theme-4" id="explore-nav">
            <ul>
                <a href="{{ url('/explore/fresh') }}">
                    <li id="fresh">Fresh</li>
                </a>
                <a href="{{ url('/explore/populars') }}">
                    <li id="populars">Populars</li>
                </a>
                <a href="{{ url('/explore/trendings') }}">
                    <li id="trendings">Trendings</li>
                </a>
                @foreach ($topTags as $tg)
                    <a href="{{ url('/explore/news/'.$tg->idtags) }}">
                        <li id="tag-{{ $tg->idtags }}">{{ $tg->tag }}</li>
                    </a>
                @endforeach
            </ul>
        </div>
    </div>
</div>
<div>
	<div>
		@if (count($topStory) == 0)
			@include('main.post-empty')	
		@else
			<div class="post">
				@foreach ($topStory as $story)
					@include('main.post')
				@endforeach
			</div>
			{{ $topStory->links() }}
		@endif
	</div>
</div>
@endsection