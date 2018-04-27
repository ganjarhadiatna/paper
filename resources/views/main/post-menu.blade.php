<script type="text/javascript">
	function viewDesign(idboxs, idimage) {
		var server_post = '{{ url("/box/") }}'+'/'+idboxs+'/design/'+idimage;
		window.location = server_post;
	}
	function viewPost(idboxs, title='') {
		var server_post = '{{ url("/box/") }}'+'/'+idboxs+'/'+title;
		window.location = server_post;
	}
	function editPost(idboxs) {
		var server_post = '{{ url("/box/") }}'+'/'+idboxs+'/edit';
		window.location = server_post;
	}
	function organizedPost(idboxs) {
		var server_post = '{{ url("/box/") }}'+'/'+idboxs+'/designs';
		window.location = server_post;
	}
	function opQuestionPost(idboxs) {
		opQuestion('open','Are you sure you want to delete this box?', 'deletePost("'+idboxs+'")');
	}
	function deletePost(idboxs) {
		$.ajax({
			url: '{{ url("/box/delete") }}',
			type: 'post',
			data: {'idboxs': idboxs},
			beforeSend: function() {
				opQuestion('hide');
				open_progress('Deleting your design...');
			}
		})
		.done(function(data) {
			close_progress();
			if (data == 'success') {
				opAlert('open', 'This box has been deleted, to take effect try refresh this page.');
			} else {
				opAlert('open', 'Failed to delete this box.');
			}
			console.log(data);
		})
		.fail(function(data) {
			opAlert('open', 'There is an error, please try again.');
			console.log(data.responseJSON);
		})
		.always(function() {
			close_progress();
		});
		
	}
	function opCommentPopup(stt, path, idcomment, title = '') {
		var id = '{{ Auth::id() }}';
		if (stt === 'open') {
			$('#'+path).show();
			if (id === iduser) {
				var menu = '<li onclick="opQuestion('+"'open'"+','+"'Delete this comment ?'"+','+"'deleteComment("+idcomment+")'"+')">Delete Comment</li>';
			} else {
				var menu = '<li onclick="opQuestion('+"'open'"+','+"'Delete this comment ?'"+','+"'deleteComment("+idcomment+")'"+')">Delete Comment</li>';
			}
			$('.content-popup .place-popup #val').html(menu);
		} else {
			$('#'+path).hide();
		}
	}
	function opPostPopup(stt, path, idboxs, iduser, title = '') {
		var id = '{{ Auth::id() }}';
		if (stt === 'open') {
			$('#'+path).show();
			if (id === iduser) {
				var menu = '\
				<li onclick="organizedPost('+idboxs+')">Organized Designs</li>\
				<li onclick="viewPost('+idboxs+')">View Boxs</li>\
				<li onclick="editPost('+idboxs+')">Edit Boxs</li>\
				<li onclick="opQuestionPost('+idboxs+')">Delete Boxs</li>\
				';
			} else {
				var menu = '\
				<li onclick="viewPost('+idboxs+')">View Box</li>\
				<li>Report</li>\
				';
			}
			$('.content-popup .place-popup #val').html(menu);
		} else {
			$('#'+path).hide();
		}
	}
	function opPostSmallPopup(stt, path, idboxs, iduser, idimage) {
		var id = '{{ Auth::id() }}';
		if (stt === 'open') {
			$('#'+path).show();
			if (id === iduser) {
				var menu = '\
				<li onclick="viewDesign('+idboxs+','+idimage+')">View Design</li>\
				<li onclick="addBookmark('+idimage+')">Save Design</li>\
				<li onclick="pictZoom('+idimage+')">Zoomed</li>\
				';
			} else {
				var menu = '\
				<li onclick="viewDesign('+idboxs+','+idimage+')">View Design</li>\
				<li onclick="addBookmark('+idimage+')">Save Design</li>\
				<li onclick="pictZoom('+idimage+')">Zoomed</li>\
				<li>Report</li>\
				';
			}
			$('.content-popup .place-popup #val').html(menu);
		} else {
			$('#'+path).hide();
		}
	}
	$(document).ready(function() {
		$('#menu-popup').on('click', function(event) {
			event.preventDefault();
			opPostPopup('close', 'menu-popup');
		});
	});
</script>
<div class="content-popup" id="menu-popup">
	<div class="place-popup">
		<ul>
		    <div id="val"></div>
		    <li class="btm" onclick="opPostPopup('close', 'menu-popup')">Exit</li>
		</ul>
	</div>
</div>