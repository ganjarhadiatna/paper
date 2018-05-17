@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
	var server = '{{ url("/") }}';
	function publish() {
		var fd = new FormData();
		var idimage = $('#id-design').val();
		var idpaper = $('#id-paper').val();
		var content = $('#write-design').val();
		var tags = $('#tags-design').val();

		fd.append('idimage', idimage);
		fd.append('idpaper', idpaper);
		fd.append('content', content);
		fd.append('tags', tags);
		$.each($('#form-publish').serializeArray(), function(a, b) {
		   	fd.append(b.name, b.value);
		});

		$.ajax({
		  	url: '{{ url("/design/edit") }}',
			data: fd,
			processData: false,
			contentType: false,
			type: 'post',
			beforeSend: function() {
				open_progress('Updating your design...');
			}
		})
		.done(function(data) {
		   	if (data == 'failed') {
		   		opAlert('open', 'failed to saving design, your design still the same with previous content.\
                   To fix problem try with edit content design.');
		   		close_progress();
		   	} else {
				close_progress();
				//window.location = server+'/paper/{{ $idpapers }}/design/{{ $idimage }}';
				goBack();
		   	}
		   	//console.log(data);
		})
		.fail(function(data) {
		  	opAlert('open', "there is an error, please try again.");
			console.log(data.responseJSON);
		})
		.always(function() {
			close_progress();
		});

		return false;
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
				opAlert('open', 'Design has been deleted, please refresh to take effect.');
				//window.location = server+'/paper/{{ $idpapers }}';
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
	function opPaper(stt) {
        if (stt == 'open') {
            $('#paper-popup').show();
        } else {
            $('#paper-popup').hide();
        }
    }
	$(document).ready(function() {
		$('#progressbar').progressbar({
			value: false,
		});
		$('#write-design').keyup(function(event) {
			var length = $(this).val().length;
			$('#desc-lg').html(length);
		});
		$('.paper-list').on('click', function(e) {
			var id = $(this).attr('key');
			var cover = $('#frame-small-paper-'+id).find('.grid-1').attr('style');
			var title = $('#frame-small-paper-'+id).find('.grid-2').find('.ttl').attr('key');

            $('#place-paper').each(function () {
                $('.frame-small-paper').removeClass('selected');
            });
            $(this).addClass('selected');
            $('#paper-popup').hide();
			if (cover) {
				$('#cover-paper').attr({'style': cover}).html('');
			} else {
				$('#cover-paper').attr({'style': ''}).html('<span class="icn fa fa-lg fa-th-large"></span>');
			}
			$('#title-paper').html(title);
			$('#id-paper').val(id);
        });
	});
</script>
@foreach ($getImage as $ds)
<form id="form-publish" method="post" action="javascript:void(0)" enctype="multipart/form-data" onsubmit="publish()">
	<div class="sc-header">
		<div class="sc-place pos-fix">
			<div class="col-700px">
				<div class="sc-grid sc-grid-3x">
					<div class="sc-col-1">
						<button 
							class="btn btn-circle btn-primary-color btn-focus" 
							onclick="goBack()" 
							type="button">
							<span class="fa fa-lg fa-arrow-left"></span>
						</button>
					</div>
					<div class="sc-col-2">
						<h3 class="ttl-head ttl-sekunder-color">Edit Design</h3>
					</div>
					<div class="sc-col-3 txt-right">
						<input type="submit" name="save" class="btn btn-main-color" value="Save" id="btn-publish">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="compose" id="create">
		<div class="main col-700px">
			<div class="create-body">
				<div class="create-mn">
					<div class="create-block no-pad">
						<!--progress bar-->
						<div class="loading mrg-bottom" id="progressbar"></div>
						<input type="hidden" name="idpapers" id="id-design" required="required" value="{{ $idimage }}">
						<div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Design</p>
								</div>
							</div>
							<div class="place-design">
								<div class="block-field">
									<div 
										class="image image-100px image-radius image-pointer"
										style="background-image: url({{ asset('/story/thumbnails/'.$image) }})"></div>
								</div>
							</div>
                        </div>
						<div class="padding-10px"></div>
						<div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Descriptions</p>
								</div>
								<div class="right">
									<div class="count">
										<span id="desc-lg">0</span>/250
									</div>
								</div>
							</div>
							<textarea
                                name="write-design" 
                                id="write-design" 
								class="txt edit-text txt-main-color txt-box-shadow ctn ctn-main ctn-sans-serif" 
								placeholder="About your design"
                                maxlength="250"><?php echo $ds->description; ?></textarea>
						</div>
						<div class="padding-10px"></div>
						<div class="block-field">
						@foreach ($selectedPaper as $pp)
							<div class="pan">
								<div class="left">
									<p class="ttl">Paper</p>
								</div>
							</div>
							<div class="ctn-main-font ctn-sek-color ctn-thin ctn-12px padding-bottom-10px">
								Move to other paper.
							</div>
							<div class="select place-paper" id="selected-paper">
								<input type="hidden" name="id-paper" id="id-paper" required="true" value="{{ $pp->idpapers }}">
                                <div class="main-select" onclick="opPaper('open')">
                                    <div 
										class="grid-1 image image-40px image-radius" 
										id="cover-paper"
										style="background-image: url({{ asset('/story/thumbnails/'.$pp->cover) }})">
                                    </div>
                                    <div class="grid-2">
                                        <div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold" id="title-paper">
                                            {{ $pp->title }}
                                        </div>
                                    </div>
                                    <div class="grid-3">
                                        <button class="btn btn-circle btn-primary-color" type="button" onclick="opPaper('open')">
                                            <span class="fa fa-lg fa-arrow-right"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
						@endforeach
                        </div>
						<div class="padding-10px"></div>
						<div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Tags</p>
								</div>
							</div>
							<div class="place-tags">
								<div class="block-field">
									<input 
                                        type="text" 
                                        name="tags" 
                                        id="tags-design" 
                                        class="tg txt txt-main-color txt-box-shadow" 
                                        placeholder="Tags1, Tags2, Tags N..."
										value="{{ $tags }}">
								</div>
							</div>
						</div>
						<div class="padding-10px"></div>
						<div class="btn btn-primary-color btn-text-left btn-no-pad" onclick="opQuestionDesign({{ $idimage }})">
							<div class="pan">
								<div class="left">
									<p class="ttl">Delete Design</p>
								</div>
							</div>
							<div class="ctn-main-font ctn-sek-color ctn-thin ctn-12px">
								It will delete this design permanently, you can't undo this sections.
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endforeach
@include('main.paper-list')
@endsection