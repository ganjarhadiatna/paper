@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
	var server = '{{ url("/") }}';
	function publish() {
		var fd = new FormData();
		var idimage = $('#id-design').val();
		var content = $('#write-design').val();
		var tags = $('#tags-design').val();

		fd.append('idimage', idimage);
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
        $('.frame-small-paper').on('click', function(e) {
            $('#place-paper').each(function () {
                $('.frame-small-paper').removeClass('selected');
            });
            $(this).addClass('selected');
            $('#paper-popup').hide();
        });
	});
</script>
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
						<h3 class="ttl-head ttl-sekunder-color">Add Design</h3>
					</div>
					<div class="sc-col-3 txt-right">
						<input type="submit" name="save" class="btn btn-main-color" value="Upload" id="btn-publish">
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
                        <div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Design</p>
								</div>
							</div>
							<div class="place-design">
								<div class="block-field">
									<input 
                                        type="text" 
                                        name="image" 
                                        id="image-design" 
                                        class="tg txt txt-main-color txt-box-shadow" 
                                        placeholder="Design"
										value="">
								</div>
							</div>
                        </div>
                        <div class="padding-5px"></div>
                        <div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Paper</p>
								</div>
							</div>
							<div class="select place-paper" id="selected-paper">
                                <div class="main-select" onclick="opPaper('open')">
                                    <div class="grid-1 image image-40px image-radius">
                                        <span class="icn fa fa-lg fa-th-large"></span>
                                    </div>
                                    <div class="grid-2">
                                        <div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold">
                                            Choose Paper
                                        </div>
                                    </div>
                                    <div class="grid-3">
                                        <button class="btn btn-circle btn-primary-color" type="button" onclick="opPaper('open')">
                                            <span class="fa fa-lg fa-arrow-right"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="padding-5px"></div>
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
                                maxlength="250"></textarea>
						</div>
						<div class="padding-5px"></div>
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
										value="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="content-popup" id="paper-popup">
    <div class="place-select col-700px middle">
        <div class="sc-header">
            <div class="sc-place">
                <div>
                    <div class="sc-grid sc-grid-2x">
                        <div class="sc-col-1">
                            <div class="ctn-main-font ctn-16px ctn-sek-color ctn-bold ctn-middle">Choose Paper</div>
                        </div>
                        <div class="sc-col-2 txt-right">
                            <button 
                                class="btn btn-circle btn-primary-color btn-focus" 
                                onclick="opPaper('hide')"
                                type="button">
                                <span class="fas fa-lg fa-times"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="place" id="place-paper">
            @foreach ($papers as $pp)
                <div class="frame-small-paper" key="{{ $pp->idpapers }}">
                    @if (!is_null($pp->cover))
                        <div class="grid-1 image image-40px image-radius"
                            style="background-image: url({{ asset('/story/thumbnails/'.$pp->cover) }})"></div>
                    @else
                        <div class="grid-1 image image-40px image-radius">
                            <span class="icn fa fa-lg fa-th-large"></span>
                        </div>
                    @endif
                    <div class="grid-2">
                        <div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold">
                            {{ $pp->title }}
                        </div>
                    </div>
                    <div class="grid-3">
                        <button class="chk btn btn-circle btn-primary-color" type="button">
                            <span class="fa fa-lg fa-check"></span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection