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
				<li class="active" id="design">Designs</li>
			</a>
			<a href="{{ url('/tags/paper/'.$ctr) }}">
				<li id="papers">Papers</li>
			</a>
		</ul>
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