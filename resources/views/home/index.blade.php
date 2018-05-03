@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
	$(document).ready(function() {
		$('#header').addClass('hed-mobile');
		$('#main-search').addClass('mid-mobile');
		$(window).scroll(function(event) {
			var hg = $('#header').height();
			var top = $(this).scrollTop();
			if (top > hg) {
				$('#main-search').addClass('hide');
			} else {
				$('#main-search').removeClass('hide');
			}
		});
	});
</script>
<div>
	@if (count($topStory) == 0)
	<div class="frame-empty">
		<div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
		<div class="ttl padding-15px">Home Feeds Still Empty</div>
		<div class="padding-5px"></div>
		<a href="{{ url('/compose/design') }}">
			<button class="btn btn-sekunder-color btn-radius">
				<span class="fa fa-lg fa-plus"></span>
				<span>Add your First Design</span>
			</button>
		</a>
	</div>
	@else
		<div class="post">
			@foreach ($topStory as $story)
				@include('main.post')
			@endforeach
		</div>
		{{ $topStory->links() }}
	@endif
</div>
@endsection