@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
    var server = '{{ url("/") }}';
	var idpapers = '{{ $idpapers }}';
	function removeCover() {
		$("#image-preview").attr('src','');
		$('.compose .main .create-body .create-block .cover-icon .img').hide();
		$('.compose .main .create-body .create-block .cover-icon .icn').show();
	}
	function loadCover() {
		var OFReader = new FileReader();
		OFReader.readAsDataURL(document.getElementById("cover").files[0]);
		OFReader.onload = function (oFREvent) {
			$("#image-preview").attr('src', oFREvent.target.result);
		}
		$('.compose .main .create-body .create-block .cover-icon .img').show();
		$('.compose .main .create-body .create-block .cover-icon .icn').hide();
	}
	function rvDone() {
		window.location = server+'/paper/'+idpapers;
	}
	function rvImage(url, idimage) {
		var review = '\
		<div class="frame-review" id="fr-'+idimage+'">\
			<div class="image image-all" style="background-image: url('+url+')"></div>\
			<div class="del">\
				<a href="'+server+'/paper/'+idpapers+'/design/'+idimage+'/edit">\
					<button class="btn btn-circle btn-primary-color"\
						title="edit this design">\
						<span class="fa fa-lg fa-pencil-alt"></span>\
					</button>\
				</a>\
				<button class="btn btn-circle btn-primary-color"\
					title="delete this design"\
					onclick="opQuestionDesign('+idimage+')">\
					<span class="fa fa-lg fa-trash-alt"></span>\
				</button>\
			</div>\
		</div>';
		return review;
	}
	function getImage() {
		var fd = new FormData();
		var image = $('#get-image')[0].files[0];
		var dt = '';
		
		fd.append('image', image);
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
				//add place review
				var img = '{{ asset("/story/thumbnails/") }}'+'/'+dt.filename;
				var idimage = dt.idimage;
				$('#frame-empty').hide();
				$('#place-review-image').append(rvImage(img, idimage));
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
	function delImage(idimage) {
		$.ajax({
	    	url: '{{ url("/paper/image/delete") }}',
			data: {'idimage':idimage},
			type: 'post',
			beforeSend: function() {
				opQuestion('hide');
				open_progress('Deleting your design...');
			}
	    })
	    .done(function(data) {
			if (data == 1) {
				$('#place-review-image').find('#fr-'+idimage).remove();
			} else if (data == 22) {
				opAlert('open', 'Failed to delete, please check your file.');
			} else if (data == 99) {
				opAlert('open', 'Failed to delete, please try again later.');
			} else {
				opAlert('open', 'There is an erro, please try again.');
			}
			//console.log(data);
	    })
	    .fail(function(data) {
	    	opAlert('open', 'We can not delete your picture, please try again.');
			//console.log(data.responseJSON);
	    })
		.always(function() {
			close_progress();
		});
	}
	function opQuestionDesign(idimage) {
		opQuestion('open','Are you sure you want to delete this design ?', 'delImage("'+idimage+'")');
	}
    $(document).ready(function() {
		$('#progressbar').progressbar({
			value: false,
		});
    });
</script>
<div>
    <div class="sc-header">
        <div class="sc-place pos-fix">
            <div class="col-1000px">
                <div class="sc-grid sc-grid-2x">
                    <div class="sc-col-1">
						<div class="padding-top-10px">
							<form id="form-publish"
								method="post"
								action="javascript:void(0)"
								enctype="multipart/form-data"
								>
								<input type="file" name="get-image" accept="image/*" class="hide-input-file" id="get-image" onchange="getImage()">
							</form>
							<label for="get-image">
								<span class="btn btn-sekunder-color btn-focus">
									<span class="fa fa-lg fa-plus"></span>
									<span>Add Design</span>
								</span>
							</label>
						</div>
					</div>
                    <div class="sc-col-2 txt-right">
                        <input type="button" name="save" class="btn btn-main-color" value="Done" id="btn-publish" onclick="rvDone()">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="compose" id="create">
        <div class="main col-1000px">
            <div class="create-body edit">
                <div class="create-mn">
					<div class="create-block">
						<!--progress bar-->
						<div class="loading mrg-bottom" id="progressbar"></div>
						<div class="place-review-image" id="place-review-image">
							@if (count($image) == 0)
								<div class="frame-empty" id="frame-empty">
									<div class="icn fa fa-lg fa-images btn-main-color"></div>
									<div class="ttl padding-15px">There is no picture, try upload one.</div>
								</div>
							@else
								@foreach ($image as $dt)
									<div class="frame-review" id="fr-{{ $dt->idimage }}">
										<div class="image image-all"
											style="background-image: url({{ asset('/story/thumbnails/'.$dt->image) }})"></div>
										<div class="del">
											<a href="{{ url('/paper/'.$idpapers.'/design/'.$dt->idimage.'/edit') }}">
												<button class="btn btn-circle btn-primary-color"
													title="edit this design">
													<span class="fa fa-lg fa-pencil-alt"></span>
												</button>
											</a>
											<button class="btn btn-circle btn-primary-color"
												title="delete this design"
												onclick="opQuestionDesign({{ $dt->idimage }})">
												<span class="fa fa-lg fa-trash-alt"></span>
											</button>
										</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection