@extends('layout.index')
@section('title',$title)
@section('path', $path)
@section('content')
<script type="text/javascript">
	var server = '{{ url("/") }}';
	function publish() {
		var fd = new FormData();
		var title = $('#title-story').val();
		var content = $('#write-story').val();
		var tags = $('#tags-story').val();

		fd.append('title', title);
		fd.append('content', content);
		fd.append('tags', tags);
		$.each($('#form-publish').serializeArray(), function(a, b) {
		   	fd.append(b.name, b.value);
		});

		$.ajax({
		  	url: '{{ url("/box/publish") }}',
			data: fd,
			processData: false,
			contentType: false,
			type: 'post',
			beforeSend: function() {
				open_progress('Uploading your Story...');
			}
		})
		.done(function(data) {
		   	if (data == 0) {
		   		opAlert('open', 'failed to publish story.');
		   		close_progress();
		   	} else {
		   		$('#cover').val('');
				$('#write-story').val('');
				opCreateStory('close');
				close_progress();
				window.location = '{{ url("/compose/box/") }}'+'/'+data+'/designs';
		   	}
		   	//console.log(data);
		})
		.fail(function(data) {
		  	opAlert('open', "there is an error, please try again.");
		   	//console.log(data.responseJSON);
		})
		.always(function() {
			close_progress();
		});

		return false;
	}
	$(document).ready(function() {
		$('#progressbar').progressbar({
			value: false,
		});
		$('#title-story').keyup(function(event) {
			var length = $(this).val().length;
			$('#title-lg').html(length);
		});
		$('#write-story').keyup(function(event) {
			var length = $(this).val().length;
			$('#desc-lg').html(length);
		});
	});
</script>
<form id="form-publish" method="post" action="javascript:void(0)" enctype="multipart/form-data" onsubmit="publish()">
	<div class="sc-header">
		<div class="sc-place pos-fix">
			<div class="col-700px">
				<div class="sc-grid sc-grid-3x">
					<div class="sc-col-1">
						<button class="btn btn-circle btn-primary-color btn-focus" onclick="goBack()" type="button">
							<span class="fa fa-lg fa-arrow-left"></span>
						</button>
					</div>
					<div class="sc-col-2">
						<h3 class="ttl-head ttl-sekunder-color">Add Box</h3>
					</div>
					<div class="sc-col-3 txt-right">
						<input type="submit" name="save" class="btn btn-main-color" value="Next" id="btn-publish">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="compose" id="create">
		<div class="main col-700px">
			<div class="create-body edit">
				<div class="create-mn">
					<div class="create-block">
						<!--progress bar-->
						<div class="loading mrg-bottom" id="progressbar"></div>
						<div class="block-field">
							<div class="pan">
								<div class="left">
									<p class="ttl">Title</p>
								</div>
								<div class="right">
									<div class="count">
										<span id="title-lg">0</span>/50
									</div>
								</div>
							</div>
							<div class="block-field">
								<input type="text" name="title" id="title-story" class="tg txt txt-main-color txt-box-shadow" placeholder="Such as Robot, Mobile Design etc." required="true" maxlength="50">
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
							<textarea name="write-story" id="write-story" class="txt edit-text txt-main-color txt-box-shadow ctn ctn-main ctn-sans-serif" placeholder="Descriptions" maxlength="250"></textarea>
						</div>
						<div class="padding-5px"></div>
						<div class="block-field place-tags">
							<div class="pan">
								<div class="left">
									<p class="ttl">Tags</p>
								</div>
								<div class="right"></div>
							</div>
							<div class="block-field">
								<input type="text" name="tags" id="tags-story" class="tg txt txt-main-color txt-box-shadow" placeholder="Tags1, Tags2, Tags N...">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection
