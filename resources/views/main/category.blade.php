<?php use App\TagModel; ?>
<script type="text/javascript">
	$(document).on('click', function(event) {
		$('#more-menu').hide();
		$('#nav-more-target').removeClass('active');
		$('#nav-more-target').attr('key', 'hide');
		setScrollMobile('show');
	});
	$(document).ready(function() {
		$('#nav-more-target').on('click', function(event) {
			var tr = $(this).attr('key');
			if (tr == 'hide') {
				event.stopPropagation();
				$('#more-menu').show();
				$('#notifications').hide();
				$(this).addClass('active');
				$(this).attr('key', 'open');
				setScrollMobile('hide');
			} else {
				$('#more-menu').hide();
				$(this).removeClass('active');
				$(this).attr('key', 'hide');
				setScrollMobile('show');
			}
		});

		$('#more-menu *').on('click', function(event) {
			event.stopPropagation();
			$('#more-menu').show();
			$('#notifications').hide();
			$('#nav-more-target').addClass('active');
			$('#nav-more-target').attr('key', 'open');
		});
	});
</script>
<div class="more-menu" id="more-menu">
	<div class="block">
		<div class="ttl-ctr">
			All Collections
		</div>
		<div class="place-collect">
			<div class="column-2">
				<ul class="mn">
					<li>
						<a href="{{ url('/') }}" id="home">
							Home Feeds
						</a>
					</li>
					<li>
						<a href="{{ url('/fresh') }}" id="fresh">
							Fresh
						</a>
					</li>
					<li>
						<a href="{{ url('/popular') }}" id="popular">
							Popular
						</a>
					</li>
					<li>
						<a href="{{ url('/trending') }}" id="trending">
							Trending
						</a>
					</li>
					@foreach (TagModel::AllTags() as $tag)
						<?php 
							$replace = array('[',']','@',',','.','#','+','-','*','<','>','-','(',')',';','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
							$title = str_replace($replace, '', $tag->tag); 
						?>
						<li>
							<a href="{{ url('/tags/'.$title) }}">
								{{ $tag->tag }}
							</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>