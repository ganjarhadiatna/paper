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
				window.location = server+'/paper/{{ $idpapers }}/design/{{ $idimage }}';
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
	$(document).ready(function() {
		$('#progressbar').progressbar({
			value: false,
		});
		$('#write-design').keyup(function(event) {
			var length = $(this).val().length;
			$('#desc-lg').html(length);
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
						<button 
							class="btn btn-circle btn-primary-color btn-focus" 
							onclick="opQuestionDesign({{ $idimage }})" 
							type="button">
							<span class="fa fa-lg fa-trash-alt"></span>
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
                                maxlength="250"><?php echo $ds->description; ?></textarea>
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
                                        placeholder="Tags1, Tags2, Tags N..." >
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@endforeach
@endsection