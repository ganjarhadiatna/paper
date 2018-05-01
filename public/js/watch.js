function opWatch(idpapers, server, idmain, iduser) {
	if (idmain === '') {
		opAlert('open', 'Please login berfore you can watch this paper.');
	} else {
		var tr = $('#add-watch-'+idpapers).val();
		if (tr === 'Watch') {
			addWatch(idpapers, server, iduser);
		} else {
			removeWatch(idpapers, server, iduser);
		}
	}
}
function addWatch(idpapers, server, iduser) {
	$.ajax({
		url: server+'/watch/add',
		type: 'post',
		data: {'idpapers': idpapers, 'iduser': iduser},
		beforeSend: function() {
			$('#add-watch-'+idpapers).val('Wait..');
		}
	})
	.done(function(data) {
		if (data === 'success') {
			$('#add-watch-'+idpapers).val('Unwatch').attr('class', 'btn btn-main-color');
		} else {
			$('#add-watch-'+idpapers).val('Watch').attr('class', 'btn btn-sekunder-color');
			opAlert('open', 'Failed to Watch this user.');
		}
	})
	.fail(function(data) {
		$('#add-watch-'+idpapers).val('Watch').attr('class', 'btn btn-sekunder-color');
		opAlert('open', 'There is an error, please try again.');
		console.log(data.responseJSON);
	});
	
}
function removeWatch(idpapers, server, iduser) {
	$.ajax({
		url: server+'/watch/remove',
		type: 'post',
		data: {'idpapers': idpapers, 'iduser': iduser},
		beforeSend: function() {
			$('#add-watch-'+idpapers).val('Wait..');
		}
	})
	.done(function(data) {
		if (data === 'success') {
			$('#add-watch-'+idpapers).val('Watch').attr('class', 'btn btn-sekunder-color');
		} else {
			$('#add-watch-'+idpapers).val('Unwatch').attr('class', 'btn btn-main-color');
			opAlert('open', 'Failed to Unwatch this user.');
		}
	})
	.fail(function() {
		$('#add-watch-'+idpapers).val('Unwatch').attr('class', 'btn btn-main-color');
		opAlert('open', 'There is an error, please try again.');
	});
}