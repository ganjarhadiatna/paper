@extends('layout.index')
@section('title',$title)
@section('path',$path)
@section('content')
<script type="text/javascript">
	var id = '{{ Auth::id() }}';
	var server = '{{ url("/") }}';
</script>
@foreach ($getPaper as $dt)
@if ($dt->id == Auth::id())
	<div class="sc-header">
		<div class="sc-place pos-fix">
			<div class="col-800px">
				<div class="sc-grid sc-grid-3x">
					<div class="sc-col-1">
						@if ($dt->id == Auth::id())
							<button 
								class="btn btn-circle btn-main2-color btn-focus" 
								onclick="opQuestionPost('{{ $idpapers }}')">
								<span class="far fa-lg fa-trash-alt"></span>
							</button>
							<a href="{{ url('/paper/'.$idpapers.'/edit') }}">
								<button class="btn btn-circle btn-main2-color btn-focus mobile">
									<span class="fas fa-lg fa-pencil-alt"></span>
								</button>
							</a>
							<button 
								class="btn btn-circle btn-main2-color btn-focus" 
								onclick="opPostPopup('open', 'menu-popup', '{{ $idpapers }}', '{{ $dt->id }}')">
								<span class="fas fa-lg fa-ellipsis-h"></span>
							</button>
						@endif
					</div>
					<div class="sc-col-2 txt-center">
						<h3 class="ttl ttl-head-2 ttl-sekunder-color">
							Paper
						</h3>
					</div>
					<div class="sc-col-3 txt-right">
						@if ($dt->id == Auth::id())
							<a href="{{ url('/paper/'.$idpapers.'/designs') }}">
								<button class="btn btn-sekunder-color btn-focus">
									<span class="fas fa-lg fa-images"></span>
									<span class="mobile">Organized</span>
								</button>
							</a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
@endforeach
<div class="col-800px padding-bottom-20px">
	@foreach ($getPaper as $dt)
		<div>
			<h1 class="ctn-main-font ctn-bold ctn-standar ctn-sek-color padding-10px">{{ $dt->title }}</h1>
		</div>
		@if ($dt->description != "")
			<div>
				<div class="desc ctn-main-font ctn-bold ctn-16px ctn-sek-color">
					<?php echo $dt->description; ?>
				</div>
			</div>
		@endif
		<div>
			<div class="menu-val">
				<ul>
					<li>
						<div class="val">{{ $dt->views }}</div>
						<div class="ttl">Visited</div>
					</li>
					<li>
						<div class="val">{{ $dt->ttl_image }}</div>
						<div class="ttl">Designs</div>
					</li>
					<li>
						<div class="val">{{ $dt->ttl_watch }}</div>
						<div class="ttl">Watchs</div>
					</li>
					@if ($dt->id != Auth::id())
						<li class="right">
							@if (!is_int($watchStatus))
								<input
									type="button" 
									name="watch" 
									class="btn btn-sekunder-color" 
									id="add-watch-{{ $idpapers }}" 
									value="Watch" 
									onclick="opWatch('{{ $idpapers }}', '{{ url("/") }}', '{{ Auth::id() }}', '{{ $dt->id }}')">
							@else
								<input 
									type="button" 
									name="unwatch" 
									class="btn btn-main-color" 
									id="add-watch-{{ $idpapers }}" 
									value="Unwatch" 
									onclick="opWatch('{{ $idpapers }}', '{{ url("/") }}', '{{ Auth::id() }}', '{{ $dt->id }}')">
							@endif
						</li>
					@endif
					<li class="right">
						<a href="{{ url('/user/'.$dt->id) }}">
							<div class="image image-40px image-circle" 
							style="background-image: url({{ asset('/profile/photos/'.$dt->foto) }});"></div>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div>
			@if (count($tags) > 0)
				@foreach($tags as $tag)
					<?php 
						$replace = array('[',']','@',',','.','#','+','-','*','<','>','-','(',')',';','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
						$title = str_replace($replace, '', $tag->tag); 
					?>
					<a href="{{ url('/tags/'.$title) }}" class="frame-top-tag">
						<div>{{ $tag->tag }}</div>
					</a>
				@endforeach
			@endif
		</div>
	@endforeach
</div>
<div>
	<div class="padding-top-15px">
		@if (count($paperImage) == 0)
		<div class="frame-empty">
			<div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
			<div class="ttl padding-15px">
				Designs Empty.
			</div>
			@if ($id == Auth::id())
				<div class="desc ctn-main-font ctn-bold ctn-14px ctn-sek-color padding-bottom-20px">
					Try to adding one's.
				</div>
				<a href="{{ url('/paper/'.$idpapers.'/designs') }}">
					<button class="create btn btn-sekunder-color btn-radius width-all">
						<span class="fas fa-lg fa-plus"></span>
						<span>Add Designs</span>
					</button>
				</a>
			@endif
		</div>
		@else
			<div class="post">
				@foreach ($paperImage as $story)
					@include('main.post')
				@endforeach
			</div>
			{{ $paperImage->links() }}
		@endif
	</div>
</div>
@endsection
