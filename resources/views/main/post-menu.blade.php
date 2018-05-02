<script type="text/javascript">
	function viewDesign(idpapers, idimage) {
		var server_post = '{{ url("/paper/") }}'+'/'+idpapers+'/design/'+idimage;
		window.location = server_post;
	}
	function editDesign(idpapers, idimage) {
		var server_post = '{{ url("/paper/") }}'+'/'+idpapers+'/design/'+idimage+'/edit';
		window.location = server_post;
	}
	function viewPost(idpapers, title='') {
		var server_post = '{{ url("/paper/") }}'+'/'+idpapers+'/'+title;
		window.location = server_post;
	}
	function editPost(idpapers) {
		var server_post = '{{ url("/paper/") }}'+'/'+idpapers+'/edit';
		window.location = server_post;
	}
	function organizedPost(idpapers) {
		var server_post = '{{ url("/paper/") }}'+'/'+idpapers+'/designs';
		window.location = server_post;
	}
	function opQuestionPost(idpapers) {
		opQuestion('open','Are you sure you want to delete this paper?', 'deletePost("'+idpapers+'")');
	}
	function deletePost(idpapers) {
		$.ajax({
			url: '{{ url("/paper/delete") }}',
			type: 'post',
			data: {'idpapers': idpapers},
			beforeSend: function() {
				opQuestion('hide');
				open_progress('Deleting your paper...');
			}
		})
		.done(function(data) {
			close_progress();
			if (data == 'success') {
				opAlert('open', 'This paper has been deleted, to take effect try refresh this page.');
			} else {
				opAlert('open', 'Failed to delete this paper.');
			}
			//console.log(data);
		})
		.fail(function(data) {
			opAlert('open', 'There is an error, please try again.');
			//console.log(data.responseJSON);
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
	function opPostPopup(stt, path, idpapers, iduser, title = '') {
		var id = '{{ Auth::id() }}';
		if (stt === 'open') {
			$('#'+path).show();
			if (id === iduser) {
				var menu = '\
				<li onclick="editPost('+idpapers+')">Edit</li>\
				<li onclick="opQuestionPost('+idpapers+')">Delete</li>\
				';
			} else {
				var menu = '\
				<li onclick="viewPost('+idpapers+')">View</li>\
				';
			}
			$('.content-popup .place-popup #val').html(menu);
		} else {
			$('#'+path).hide();
		}
	}
	function opPostSmallPopup(stt, path, idpapers, iduser, idimage) {
		var id = '{{ Auth::id() }}';
		if (stt === 'open') {
			$('#'+path).show();
			if (id == iduser) {
				var menu = '\
				<li onclick="pictZoom('+idimage+')">View</li>\
				<li onclick="addBookmark('+idimage+')">Save</li>\
				<li onclick="editDesign('+idpapers+','+idimage+')">Edit</li>\
				';
			} else {
				var menu = '\
				<li onclick="pictZoom('+idimage+')">View</li>\
				<li onclick="addBookmark('+idimage+')">Save</li>\
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