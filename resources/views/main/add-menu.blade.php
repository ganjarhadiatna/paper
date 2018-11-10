<script type="text/javascript">
    $(document).on('click', function(event) {
		$('#add-menu').hide();
		$('#op-add').attr('key', 'hide');
	});
    $(document).ready(function() {
        $('#op-add').on('click', function(event) {
			var tr = $(this).attr('key');
			if (tr == 'hide') {
				event.stopPropagation();
				$('#add-menu').show();
				$(this).attr('key', 'open');
			} else {
				$('#add-menu').hide();
				$(this).attr('key', 'hide');
			}
		});
    });
</script>
<div class="add-menu" id="add-menu">
    <ul>
        <a href="{{ url('/compose/design') }}">
            <li class="bdr-bottom">
                <span class="ttl">Create Design</span>
            </li>
        </a>
        <a href="{{ url('/compose/paper') }}">
            <li>
                <span class="ttl">Create Paper</span>
            </li>
        </a>
    </ul>
</div>