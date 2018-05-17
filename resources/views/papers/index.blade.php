@extends('layout.index')
@section('title',$title)
@section('path',$path)
@section('content')
<script type="text/javascript">
	var id = '{{ Auth::id() }}';
	var server = '{{ url("/") }}';
	var idpapers = '{{ $idpapers }}';
	function rvImage(url, image, idimage, padBottom) {
		var srv_design = server+'/paper/'+idpapers+'/design/'+idimage;
		var srv_design_edit = server+'/paper/'+idpapers+'/design/'+idimage+'/edit';
		var review = '\
		<div class="frame-post">\
			<div class="mid">\
				<div class="mid-tool">\
					<div class="bot-tool">\
						<div class="nts"></div>\
						<div class="bok">\
							<button class="btn btn-main-color btn-no-border" key="'+idimage+'" onclick="addBookmark('+idimage+')">\
								<span class="bookmark-'+idimage+' far fa-lg fa-bookmark" id="bookmark-'+idimage+'"></span>\
							</button>\
						</div>\
					</div>\
					<div class="oth-tool">\
						<div class="grid grid-2x">\
							<div class="grid-1">\
								<button class="icn btn btn-circle btn-black-color btn-no-focus"\
									onclick="pictZoom('+idimage+')">\
									<span class="fas fa-lg fa-search-plus"></span>\
								</button>\
								<a href="'+srv_design_edit+'">\
									<button class="icn btn btn-circle btn-black-color btn-no-focus">\
										<span class="fas fa-lg fa-pencil-alt"></span>\
									</button>\
								</a>\
							</div>\
							<div class="grid-2 right">\
								<button class="icn btn btn-circle btn-black-color btn-no-focus"\
									onclick="opPostSmallPopup('+"'open'"+', '+"'menu-popup'"+', '+idpapers+', '+id+', '+idimage+')">\
									<span class="fa fa-lg fa-ellipsis-h"></span>\
								</button>\
							</div>\
						</div>\
					</div>\
					<div class="place-pict" style="padding-bottom: '+padBottom+'%">\
						<a href="'+srv_design+'">\
							<div class="cover"></div>\
							<img \
								class="place-pict-image"\
								src="'+url+'"\
								alt="pict"\
								id="pict-'+idimage+'"\
								key="'+idpapers+'">\
						</a>\
					</div>\
				</div>\
			</div>\
			<div class="mid">\
				<div class="top-tool">\
					<div class="desc ctn-main-font"></div>\
				</div>\
			</div>\
		</div>';
		return review;
	}
	function getImage() {
		var fd = new FormData();
		//var image = $('#get-image')[0].files[0];
		var dt = '';
		//console.log(image);
		
		var ctn = $('#get-image')[0].files.length;
		for (let i = 0; i < ctn; i++) {
			fd.append('image[]', $('#get-image')[0].files[i]);
		}
		fd.append('idpapers', idpapers);
		$.each($('#form-image').serializeArray(), function(a, b) {
	    	fd.append(b.name, b.value);
	    });

	    $.ajax({
	    	url: '{{ url("/paper/image/upload") }}',
			data: fd,
			processData: false,
			contentType: false,
			type: 'post',
			beforeSend: function() {
				open_progress('Uploading your design...');
			}
	    })
	    .done(function(data) {
			if (data == 'success') {
				opAlert('open', 'Uploading file sucess.');
			} else if (data == 'no-file') {
				opAlert('open', 'Please choose your file.');
			} else if (data == 'no-token') {
				opAlert('open', 'Please login with your account.');
			} else if (data == 'failed-saving') {
				opAlert('open', 'Uploading failed, please try again later.');
			} else {
				dt = JSON.parse(data);
				for (var i = 0; i < dt.length; i++) {
					//add place review
					var img = '{{ asset("/story/thumbnails/") }}'+'/'+dt[i].filename;
					var idimage = dt[i].idimage;
					var padBottom = ((dt[i].height / dt[i].width) * 100);
					$('#frame-empty').hide();
					$('#place-design').prepend(rvImage(img, dt[i].filename, idimage, padBottom));
				}
			}
			//console.log(data);
	    })
	    .fail(function(data) {
	    	opAlert('open', 'We can not upload your Picture, please try again.');
			//console.log(data.responseJSON);
	    })
		.always(function() {
			close_progress();
		});
	}
</script>
@foreach ($getPaper as $dt)
	<div class="sc-header">
		<div class="sc-place pos-fix">
			<div class="col-800px">
				<div class="sc-grid sc-grid-2x">
					<div class="sc-col-1">
						<button class="btn btn-circle btn-primary-color btn-focus" onclick="goBack()" type="button">
							<span class="fa fa-lg fa-arrow-left"></span>
						</button>
						<button 
							class="btn btn-circle btn-primary-color btn-focus" 
							onclick="opPostPopup('open', 'menu-popup', '{{ $idpapers }}', '{{ $dt->id }}')"
							title="menu">
							<span class="fas fa-lg fa-ellipsis-h"></span>
						</button>
						@if ($dt->id == Auth::id())
							<a href="{{ url('/paper/'.$idpapers.'/edit') }}">
								<button class="btn btn-circle btn-primary-color btn-focus" title="edit paper">
									<span class="fas fa-lg fa-pencil-alt"></span>
								</button>
							</a>
						@endif
					</div>
					<div class="sc-col-2 txt-right">
						@if ($dt->id == Auth::id())
							<div>
								<form id="form-publish"
									method="post"
									action="javascript:void(0)"
									enctype="multipart/form-data"
									>
									<input 
										type="file" 
										name="get-image" 
										accept="image/*" 
										class="hide-input-file" 
										id="get-image" 
										onchange="getImage()"
										multiple>
								</form>
								<label for="get-image">
									<div class="btn btn-sekunder-color btn-focus" title="add design">
										<span class="fa fa-lg fa-plus"></span>
										<span>Designs</span>
									</div>
								</label>
							</div>
						@else
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
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
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
					<a href="{{ url('/tags/paper/'.$title) }}" class="frame-top-tag">
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
		<div class="frame-empty" id="frame-empty">
			<div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
			<div class="ttl padding-15px">
				Designs Empty.
			</div>
		</div>
		@endif
		<div class="post" id="place-design">
			@foreach ($paperImage as $story)
				@include('main.post')
			@endforeach
		</div>
		{{ $paperImage->links() }}
		
	</div>
</div>
@endsection
