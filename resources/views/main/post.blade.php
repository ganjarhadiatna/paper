<div class="frame-post">
	<div class="mid">
		<div class="mid-tool">
			<div class="bot-tool">
				<div class="nts">
					<button 
						class="zoom btn btn-circle btn-sekunder-color btn-no-border" 
						onclick="pictZoom({{ $story->idimage }})"
						style="float: left; margin-right: 5px;">
						<span class="fas fa-lg fa-search-plus"></span>
					</button>
					@if ($story->id == Auth::id())
						<a href="{{ url('/paper/'.$story->idpapers.'/design/'.$story->idimage.'/edit') }}">
							<button 
								class="zoom btn btn-circle btn-sekunder-color btn-no-border"
								style="margin-right: 5px;">
								<span class="fas fa-lg fa-pencil-alt"></span>
							</button>
						</a>
					@endif
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
			<div>
				<a href="{{ url('/paper/'.$story->idpapers.'/design/'.$story->idimage) }}">
					<div class="cover"></div>
					<img src="{{ asset('/story/thumbnails/'.$story->cover) }}"
					alt="pict"
					id="pict-{{ $story->idimage }}"
					key="{{ $story->idpapers }}">
				</a>
			</div>
			<div class="oth-tool">
				<div class="grid grid-2x">
					<div class="grid-1"></div>
					<div class="grid-2 right">
						<button class="icn btn btn-circle btn-black-color btn-no-focus"
							onclick="opPostSmallPopup('open', 'menu-popup', '{{ $story->idpapers }}', '{{ $story->id }}', '{{ $story->idimage }}')">
							<span class="fa fa-lg fa-ellipsis-h"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mid">
		<div class="top-tool">
			<div class="desc ctn-main-font">
				{{ $story->description }}
			</div>
		</div>
	</div>
</div>