@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<div class="sc-header padding-20px">
	<div class="sc-place">
		<div class="sc-block">
			<div class="sc-col-1">
				<h1 class="ttl-head ctn-main-font ctn-big">
					{{ $ctr }}
				</h1>
			</div>
		</div>
	</div>
</div>
<div class="padding-20px">
	<div class="navigator nav-2x nav-theme-1 col-400px" id="post-nav">
		<ul>
			<a href="{{ url('/tags/design/'.$ctr) }}">
				<li id="design">Designs</li>
			</a>
			<a href="{{ url('/tags/paper/'.$ctr) }}">
				<li class="active" id="papers">Papers</li>
			</a>
		</ul>
	</div>
</div>
<div class="padding-bottom-10px"></div>
<div>
    @if (count($topPaper) == 0)
        <div class="frame-empty">
            <div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
            <div class="ttl padding-15px">Paper empty</div>
        </div>
    @else
        <div class="post-flex">
            @foreach ($topPaper as $bx)
                @include('main.paper')
            @endforeach
        </div>
        {{ $topPaper->links() }}
    @endif
</div>
@endsection