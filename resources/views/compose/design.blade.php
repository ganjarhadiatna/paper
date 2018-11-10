@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
	var server = '{{ url("/") }}';
	function loadCover() {
		var OFReader = new FileReader();
		OFReader.readAsDataURL(document.getElementById("get-image").files[0]);
		OFReader.onload = function (oFREvent) {
			$("#review-design").css({'background-image': 'url('+oFREvent.target.result+')'}).html('');
		}
	}
	function publish() {
		var fd = new FormData();
		var image = $('#get-image')[0].files[0];
		var idpapers = $('#id-paper').val();
		var content = $('#write-design').val();
		var tags = $('#tags-design').val();
		if (!image) {
			opAlert('open', "Please browse your design first.");
		} else if (idpapers === '') {
			opAlert('open', "Please choose your paper first.");
		} else {
			fd.append('image', image);
			fd.append('idpapers', idpapers);
			fd.append('content', content);
			fd.append('tags', tags);
			$.each($('#form-publish').serializeArray(), function(a, b) {
				fd.append(b.name, b.value);
			});

			$.ajax({
				url: '{{ url("/design/publish") }}',
				data: fd,
				processData: false,
				contentType: false,
				type: 'post',
				beforeSend: function() {
					open_progress('Publishing your design...');
				}
			})
			.done(function(data) {
				if (data == 'no-image') {
					opAlert('open', "Please browse your design first.");
				} else if (data == 'no-paper') {
					opAlert('open', "You dont have to access this paper.");
				} else if (data == 'no-save') {
					opAlert('open', "Failed to publish, please try again.");
				} else {
					window.location = server+'/paper/'+idpapers+'/design/'+data;
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
		}

		return false;
	}
    function opPaper(stt) {
        if (stt == 'open') {
            $('#paper-popup').show();
        } else {
            $('#paper-popup').hide();
        }
    }
	$(document).ready(function() {
		$('#id-paper').val('');
		$('#get-image').val('');
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
<form id="form-publish" method="post" action="javascript:void(0)" enctype="multipart/form-data" onsubmit="publish()">
<div>
	<div class="sc-header">
		<div class="sc-place">
			<div class="col-700px">
				<div class="sc-grid sc-grid-3x" style="height: 45px;">
					<div class="sc-col-1"></div>
					<div class="sc-col-2">
						<h3 class="ttl-head ttl-sekunder-color">Create Design</h3>
					</div>
					<div class="sc-col-3 txt-right"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="compose" id="create">
		<div class="main col-600px">
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
								<div class="block-field" style="width: 100px;">
									<input type="file" 
										name="get-image" 
										accept="image/*" 
										class="hide-input-file" 
										id="get-image" 
										onchange="loadCover()">
									<label for="get-image" class="label-image">
										<div class="image image-100px image-radius image-pointer" id="review-design">
											<span class="icn fa fa-lg fa-image"></span>
										</div>
									</label>
								</div>
								<div class="ctn-main-font ctn-sek-color ctn-thin ctn-12px padding-top-10px">
									Click it to change or upload new design.
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
							<div class="ctn-main-font ctn-sek-color ctn-thin ctn-12px padding-bottom-10px">
									If you don't have paper, please create it first.
								</div>
							<div class="select place-paper" id="selected-paper">
								<input type="hidden" name="id-paper" id="id-paper" required="true">
                                <div class="main-select" onclick="opPaper('open')">
                                    <div class="grid-1 image image-40px image-radius" id="cover-paper">
                                        <span class="icn fa fa-lg fa-th-large"></span>
                                    </div>
                                    <div class="grid-2">
                                        <div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold" id="title-paper">
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
						<div class="padding-10px"></div>
						<div class="block-field" style="text-align: right;">
							<input type="button" class="btn btn-primary-color" value="Cancel" style="margin-right: 10px;" onclick="goBack()">
							<input type="submit" name="save" class="btn btn-main-color" value="Upload" id="btn-publish">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
@include('main.paper-list')
@endsection