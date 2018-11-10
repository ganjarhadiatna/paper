@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
@include('profile.index')
<div>
	@if (count($userStory) == 0)
		@include('main.post-empty')	
	@else
		<div class="post">
			@foreach ($userStory as $story)
				@include('main.post')
			@endforeach
		</div>
		{{ $userStory->links() }}
	@endif
</div>
@endsection