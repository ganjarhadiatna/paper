@extends('layout.index')
@section('title',$title)
@section('path',$path)
@section('content')
<script type="text/javascript">
	var id = '{{ Auth::id() }}';
	var server = '{{ url("/") }}';

	function getComment(idimage, stt) {
		var offset = $('#offset-comment').val();
		var limit = $('#limit-comment').val();
		if (stt == 'new') {
			var url_comment = '{{ url("/get/comment/") }}'+'/'+idimage+'/0/'+offset;
		} else {
			var url_comment = '{{ url("/get/comment/") }}'+'/'+idimage+'/'+offset+'/'+limit;
		}
		$.ajax({
			url: url_comment,
			dataType: 'json',
		})
		.done(function(data) {
			var dt = '';
			for (var i = 0; i < data.length; i++) {
				var server_foto = server+'/profile/thumbnails/'+data[i].foto;
				var server_user = server+'/user/'+data[i].id;
				if (data[i].id == id) {
					var op = '<span class="fa fa-lg fa-circle"></span>\
							<span class="del pointer" onclick="opQuestion('+"'open'"+','+"'Delete this comment ?'"+','+"'deleteComment("+data[i].idcomment+")'"+')" title="Delete comment.">Delete</span>';
				} else {
					var op = '';
				}
				dt += '\
					<div class="frame-comment comment-owner">\
						<div class="dt-1">\
							<a href="'+server_user+'" title="'+data[i].name+'">\
								<div class="image image-40px image-radius" style="background-image: url('+server_foto+')"></div>\
							</a>\
						</div>\
						<div class="dt-2">\
							<div class="desk comment-owner-radius">\
								<div class="comment-main">\
									<a href="'+server_user+'" title="'+data[i].name+'"><strong class="comment-name">'+data[i].name+'</strong></a>\
									<div>'+data[i].description+'</div>\
								</div>\
							</div>\
							<div class="tgl">\
								<span>'+data[i].created+'</span>\
								'+op+'\
							</div>\
						</div>\
					</div>\
				';
			}
			if (stt === 'new') {
				$('#place-comment').html(dt);
			} else {
				$('#place-comment').append(dt);

				var ttl = (parseInt($('#offset-comment').val()) + 5);
				$('#offset-comment').val(ttl);
			}
			if (data.length >= limit) {
				$('#frame-more-comment').show();
			} else {
				$('#frame-more-comment').hide();
			}
		})
		.fail(function(data) {
			console.log(data.responseJSON);
		});
		
	}
	function deleteComment(idcomment) {
		$.ajax({
			url: '{{ url("/delete/comment") }}',
			type: 'post',
			data: {'idcomment': idcomment},
		})
		.done(function(data) {
			if (data === 'success') {
				getComment('{{ $idimage }}', 'new');
			} else {
				opAlert('open', 'Deletting comment failed.');
			}
		})
		.fail(function(data) {
			console.log(data.responseJSON);
		}).
		always(function() {
			opQuestion('hide');
		});
	}
	function toComment() {
		var top = $('#tr-comment').offset().top;
		$('html, body').animate({scrollTop : (Math.round(top) - 100)}, 300);
		$('#comment-description').select();
	}
	$(document).ready(function() {
		$('#offset-comment').val(0);
		$('#limit-comment').val(5);
		getComment('{{ $idimage }}', 'add');

		$('#comment-publish').submit(function(event) {
			var idimage = '{{ $idimage }}';
			var desc = $('#comment-description').val();
			if (desc === '') {
				$('#comment-description').focus();
			} else {
				$.ajax({
					url: '{{ url("/add/comment") }}',
					type: 'post',
					data: {
						'description': desc,
						'idimage': idimage
					},
				})
				.done(function(data) {
					if (data === 'failed') {
						opAlert('open', 'Sending comment failed.');
						$('#comment-description').focus();
					} else {
						$('#comment-description').val('');
						//refresh comment
						getComment('{{ $idimage }}', 'new');
					}
				})
				.fail(function(data) {
					//console.log(data.responseJSON);
					opAlert('open', 'There is an error, please try again.');
				});
			}
		});

		$('#load-more-comment').on('click', function(event) {
			getComment('{{ $idimage }}', 'add');
		});

	});
</script>
@foreach ($getPaper as $paper)
<div class="sc-header">
	<div class="sc-place">
		<div class="col-600px">
			<div class="sc-grid sc-grid-2x">
				<div class="sc-col-1">
					<!--
					<button class="btn btn-circle btn-primary-color btn-focus" onclick="goBack()" type="button">
						<span class="fa fa-lg fa-arrow-left"></span>
					</button>
					-->
					<button class="btn btn-circle btn-primary-color btn-focus"
						onclick="opPostSmallPopup('open', 'menu-popup', '{{ $idpapers }}', '{{ $paper->id }}', '{{ $idimage }}')">
						<span class="fas fa-lg fa-ellipsis-h"></span>
					</button>
					<button class="btn btn-circle btn-primary-color mobile" onclick="pictZoom({{ $idimage }})">
						<span class="fas fa-lg fa-search-plus"></span>
					</button>
					@if ($paper->id == Auth::id())
						<a href="{{ url('/paper/'.$idpapers.'/design/'.$idimage.'/edit') }}">
							<button class="btn btn-circle btn-primary-color">
								<span class="fas fa-lg fa-pencil-alt"></span>
							</button>
						</a>
					@endif
				</div>
				<div class="sc-col-2 txt-right">
					<button class="btn btn-main-color btn-no-border" onclick="addBookmark('{{ $idimage }}')">
						@if (is_int($check))
							<span class="bookmark-{{ $idimage }} fas fa-lg fa-bookmark" id="bookmark-{{ $idimage }}"></span>
						@else
							<span class="bookmark-{{ $idimage }} far fa-lg fa-bookmark" id="bookmark-{{ $idimage }}"></span>
						@endif
						<span>Save</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="place-story">
	<div class="main">
		<div class="place">
			<div class="frame-story col-600px" id="main-story">
				<div class="grid-1">
					<div class="mid padding-10px">
					@foreach ($getImage as $ds)
						<div class="pict" style="padding-bottom: {{ (($ds->height / $ds->width) * 100) }}%">
							<img 
								class="place-pict-image"
								data-src="{{ asset('/story/covers/'.$ds->image) }}" 
								id="pict-{{ $idimage }}">
						</div>
					@endforeach
					</div>
				</div>
				<div class="grid-2">
					<div class="bot">
						@foreach ($getImage as $ds)
							@if (count($ds->description) != 0)
								<div class="ctn-main-font ctn-16px padding-top-10px">{{ $ds->description }}</div>
							@endif
						@endforeach
						@if (count($tags) > 0)
						<div class="padding-10px">
							@foreach($tags as $tag)
								<?php 
									$replace = array('[',']','@',',','.','#','+','-','*','<','>','-','(',')',';','&','%','$','!','`','~','=','{','}','/',':','?','"',"'",'^');
									$title = str_replace($replace, '', $tag->tag); 
								?>
								<a href="{{ url('/tags/design/'.$title) }}" class="frame-top-tag">
									<div>{{ $tag->tag }}</div>
								</a>
							@endforeach
						</div>
						@endif
					</div>
					<div class="bot">
						<div class="profile padding-10px">
							<div class="foto">
								<a href="{{ url('/user/'.$paper->id) }}">
									<div class="image image-40px image-circle" style="background-image: url({{ asset('/profile/thumbnails/'.$paper->foto) }});"></div>
								</a>
							</div>
							<div class="info">
								<div class="name">
									<div>
										Design on <a href="{{ url('/paper/'.$paper->idpapers) }}">{{ $paper->title }}</a>
									</div>
									<div>
										By <a href="{{ url('/user/'.$paper->id) }}">{{ $paper->username }}</a>
									</div>
								</div>
							</div>
							<div class="tool">
								<a href="{{ url('/paper/'.$paper->idpapers) }}">
									<input type="button" name="visit" class="btn btn-sekunder-color" value="Visit">
								</a>
							</div>
						</div>
					</div>
					<!--
					<div class="pos mid bdr-bottom" key="more design">
						<div class="ctn-main-font ctn-14px ctn-sek-color ctn-bold padding-bottom-15px">More designs</div>
						<div class="place-search-tag">
							<div class="st-lef">
								<div class="btn btn-circle btn-sekunder-color btn-no-border hg-100px" onclick="toLeft()">
									<span class="fa fa-lg fa-angle-left"></span>
								</div>
							</div>
							<div class="st-mid" id="ctnTag">
								@if (count($getAllImage) != 0)
									@foreach ($getAllImage as $img)
										<a href="{{ url('/paper/'.$img->idpapers.'/design/'.$img->idimage) }}">
											<div class="image image-100px image-radius"
												style="background-image: url({{ asset('/story/thumbnails/'.$img->image) }})"></div>
										</a>
									@endforeach
									@if (count($getAllImage) >= 8)
										<a href="{{ url('/paper/'.$img->idpapers) }}">
											<div class="image image-100px image-radius image-more">
												<span class="ttl">More</span>
											</div>
										</a>
									@endif
								@endif
							</div>
							<div class="st-rig">
								<div class="btn btn-circle btn-sekunder-color btn-no-border hg-100px" onclick="toRight()">
									<span class="fa fa-lg fa-angle-right"></span>
								</div>
							</div>
						</div>
					</div>
					-->
					<div class="bot place-comment padding-top-10px">
						<div class="loved top-comment" id="tr-comment">
						<div class="ctn-main-font ctn-14px ctn-sek-color ctn-bold">Comments</div>
							@if (Auth::id())
							<form method="post" action="javascript:void(0)" id="comment-publish">
								<div class="comment-head">
									<div>
										<input type="text" class="txt txt-primary-color" id="comment-description" placeholder="Type comment here..">
									</div>
								</div>
							</form>
							@endif
							<div class="comment-content" id="place-comment"></div>
						</div>
						<div class="frame-more" id="frame-more-comment">
							<input type="hidden" name="offset" id="offset-comment" value="0">
							<input type="hidden" name="limit" id="limit-comment" value="0">
							<button class="btn btn-sekunder-color btn-radius" id="load-more-comment">
								<span class="Load More Comment">Load More</span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="frame-story col-600px padding-bottom-20px">
				
			</div>
		</div>
	</div>
</div>
@endforeach
<div class="col-full">
	<div class="padding-10px">
		<h2 class="ctn-main-font ctn-small ctn-sek-color ctn-bold ctn-center">Related Designs</h2>
	</div>
</div>
<div class="padding-20px">
	<div>
		<div class="post">
			@foreach ($newStory as $story)
				@include('main.post')
			@endforeach
		</div>
	</div>
</div>
@endsection
