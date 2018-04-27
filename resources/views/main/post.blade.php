<div class="frame-post">
	<div class="mid">
		<div class="bot-tool">
			<div class="nts">
				<button class="zoom btn btn-circle btn-sekunder-color btn-no-border" onclick="pictZoom({{ $story->idimage }})">
					<span class="fas fa-lg fa-search-plus"></span>
				</button>
			</div>
			<div class="bok">
				<button class="btn btn-main-color btn-no-border" key="{{ $story->idimage }}" onclick="addBookmark('{{ $story->idimage }}')">
					@if (is_int($story->is_save))
						<span class="bookmark-{{ $story->idimage }} fas fa-lg fa-bookmark" id="bookmark-{{ $story->idimage }}"></span>
					@else
						<span class="bookmark-{{ $story->idimage }} far fa-lg fa-bookmark" id="bookmark-{{ $story->idimage }}"></span>
					@endif
				</button>
			</div>
		</div>
		<div class="mid-tool">
			<a href="{{ url('/box/'.$story->idboxs.'/design/'.$story->idimage) }}">
				<div class="cover"></div>
				<img src="{{ asset('/story/thumbnails/'.$story->cover) }}" alt="pict" id="pict-{{ $story->idimage }}" key="{{ $story->idboxs }}">
			</a>
		</div>
	</div>
	<div class="mid">
		<div class="top-tool">
			<div class="grid grid-2x">
				<div class="grid-1">
					<div class="desc ctn-main-font">
						Design on {{ $story->title }}
					</div>
				</div>
				<div class="grid-2 right">
					<button class="icn btn btn-circle btn-primary-color btn-focus"
						onclick="opPostSmallPopup('open', 'menu-popup', '{{ $story->idboxs }}', '{{ $story->id }}', '{{ $story->idimage }}')">
						<span class="fa fa-lg fa-ellipsis-h"></span>
					</button>
					<!--
					<a href="{{ url('/user/'.$story->id) }}">
						<div class="image image-35px image-circle" style="background-image: url({{ asset('/profile/thumbnails/'.$story->foto) }});"></div>
					</a>
					-->
				</div>
			</div>
		</div>
	</div>
</div>