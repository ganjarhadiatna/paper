<script type="text/javascript">
	var iduser = '{{ Auth::id() }}';
	var server = '{{ url("/") }}';
	var off = 5;
	function getNotifStory(stt) {
		if (stt === 'new') {
			var limit = $('#offset-notif-story').val();
		} else {
			var limit = off;
		}
		var offset = $('#offset-notif-story').val();
		$.ajax({
			url: '{{ url("/notif/story") }}',
			type: 'post',
			data: {'limit': limit, 'offset': offset},
			dataType: 'json',
		})
		.done(function(data) {
			var dt = '';
			for (var i = 0; i < data.length; i++) {
				var server_foto = server+'/profile/thumbnails/'+data[i].foto;
				var server_cover = server+'/story/thumbnails/'+data[i].image;
				var server_design = server+'/paper/'+data[i].idpapers+'/design/'+data[i].idimage;
				var server_papers = server+'/paper/'+data[i].idpapers;
				var server_user = server+'/user/'+data[i].id;
				if (data[i].type == 'paper') {
					dt += '\
						<div class="frame-notif grid-2x">\
							<div class="notif-sid">\
								<a href="'+server_user+'">\
									<div class="image image-35px image-radius" style="background-image: url('+server_foto+');"></div>\
								</a>\
							</div>\
							<div class="notif-mid">\
								<div class="ntf-mid">\
									<div class="desc">\
										<a href="'+server_user+'"><strong>'+data[i].username+'</strong></a>\
										Watch on your paper <a href="'+server_papers+'"><strong>'+data[i].title+'</strong></a>.\
									</div>\
									<div class="desc date">\
										'+data[i].created+'\
									</div>\
								</div>\
							</div>\
						</div>';
				} else {
					if (data[i].type == 'bookmark') {
						var title = 'Saved a design from <a href="'+server_papers+'"><strong>'+data[i].title+'</strong></a>.';
					} else {
						var title = 'Comented "'+data[i].description+'" on your design.';
					}
					dt += '\
						<div class="frame-notif grid-2x">\
							<div class="notif-sid">\
								<a href="'+server_design+'">\
									<div class="image image-35px image-radius" style="background-image: url('+server_cover+');"></div>\
								</a>\
							</div>\
							<div class="notif-mid">\
								<div class="ntf-mid">\
									<div class="desc">\
										<a href="'+server_user+'"><strong>'+data[i].username+'</strong></a>\
										'+title+'\
									</div>\
									<div class="desc date">\
										'+data[i].created+'\
									</div>\
								</div>\
							</div>\
						</div>';
				}
			}
			if (stt === 'new') {
				$('#val-storys').html(dt);
			} else {
				$('#val-storys').append(dt);
				var x = parseInt(off) + parseInt($('#offset-notif-story').val());
				$('#offset-notif-story').val(x);
			}
			if (data.length >= off) {
				$('#btn-story').show();
			} else {
				$('#btn-story').hide();
			}
			console.log(data);
		})
		.fail(function(data) {
			console.log(data.responseJSON);
			opAlert('show', 'There is an error, please try again.');
		});
		
	}
	function cekNotif() {
		$.get('{{ url("/notif/cek") }}', function(data) {
			//console.log('notif: '+data);
			if (data != 0) {
				$('#main-notif-sign').show();
			} else {
				$('#main-notif-sign').hide();
			}
		});
	}
	function cekNotifStory() {
		$.get('{{ url("/notif/cek/story") }}', function(data) {
			if (data != 0) {
				$('#story-notif-sign').show();
			} else {
				$('#story-notif-sign').hide();
			}
		});
	}
	$(document).on('click', function(event) {
		$('#op-notif').removeClass('active');
		$('#op-notif').attr('key', 'hide');
		$('#notifications').hide();
		setScrollMobile('show');
	});
	$(document).ready(function() {
		$('#offset-notif-story').val(0);
		$('#place-storys').show();
		//getNotifStory('none');

		if (iduser) {
			setInterval('cekNotif()', 5000);
		}

		$('#op-notif').on('click', function(event) {
			var tr = $(this).attr('key');
			if (tr == 'hide') {
				event.stopPropagation();
				$('#op-notif').addClass('active');
				$('#notifications').show();
				$('#more-menu').hide();
				$(this).attr('key', 'open');
				cekNotifStory();
				if ($('#val-storys').html() == '') {
					getNotifStory('none');
				}
				setScrollMobile('hide');
			} else {
				$('#op-notif').removeClass('active');
				$('#notifications').hide();
				$(this).attr('key', 'hide');
				setScrollMobile('show');
			}
		});

		$('#notifications *').on('click', function(event) {
			event.stopPropagation();
		});

		$('#close-notif').on('click', function(event) {
			$('#op-notif').removeClass('active').attr('key', 'hide');;
			$('#notifications').hide();
			setScrollMobile('show');
		});

		$('#notif-nav ol li').on('click', function(event) {
			event.preventDefault();

			$('#notif-nav ol li').each(function(index, el) {
				$(this).removeClass('choose');
			});
			$(this).addClass('choose');
			$('.notifications .main .put .val').hide();

			if ($('#val-storys').html() == '') {
				getNotifStory('none');
			}
		});
	});
</script>
<div class="notifications thing-popup" id="notifications">
	<div class="main">
		<div class="ttl-ctr grid-2x">
			<div class="grid-1">
				Notifications
			</div>
			<div class="grid-2">
				<button class="btn btn-circle btn-primary-color desktop" id="close-notif">
					<span class="fa fa-lg fa-times"></span>
				</button>
			</div>
		</div>
		<div class="put">
			<div class="val">
				<input type="hidden" name="offset-notif-story" id="offset-notif-story" value="0">
				<div id="val-storys"></div>
				<div class="frame-more padding-15px" id="btn-story">
					<button class="btn btn-sekunder-color btn-radius" id="load-more-notif-story" onclick="getNotifStory('none')">
						<span class="Load More Comment">Load More</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>