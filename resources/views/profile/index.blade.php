<script type="text/javascript">
	$(document).ready(function() {
		$('#post-nav ul li').each(function(index, el) {
			$(this).removeClass('active');
			$('#{{ $nav }}').addClass('active');
		});
	});
</script>
@foreach ($profile as $p)
<div class="sc-header">
	<div class="sc-place pos-fix">
		<div class="col-small">
			<div class="sc-grid sc-grid-3x">
				<div class="sc-col-1">
					@if (Auth::id() == $p->id)
						<a href="{{ url('/me/setting') }}">
							<button class="btn btn-circle btn-primary-color btn-focus">
								<span class="fas fa-lg fa-cog"></span>
							</button>
						</a>
						<a href="{{ url('/me/setting/profile') }}">
							<button class="btn btn-circle btn-primary-color btn-focus">
								<span class="fas fa-lg fa-pencil-alt"></span>
							</button>
						</a>
					@else
						<h3 class="ttl-head-2 ttl-sekunder-color">
							{{ $p->username }}
						</h3>
					@endif
				</div>
				<div class="sc-col-2 txt-center">
					<h3 class="ttl ttl-head-2 ttl-sekunder-color">
						Profile
					</h3>
				</div>
				<div class="sc-col-3 txt-right">
					@if (Auth::id() == $p->id)
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
						<a href="{{ route('logout') }}" 
							onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
							<button class="btn btn-main2-color">
								<span class="fas fa-lg fa-power-off"></span>
								<span class="">Logout</span>
							</button>
						</a>
					@else
						@if (!is_int($statusFolow))
							<input type="button" name="follow" class="btn btn-sekunder-color" id="add-follow-{{ $p->id }}" value="Follow" onclick="opFollow('{{ $p->id }}', '{{ url("/") }}', '{{ Auth::id() }}')">
						@else
							<input type="button" name="follow" class="btn btn-main3-color" id="add-follow-{{ $p->id }}" value="Unfollow" onclick="opFollow('{{ $p->id }}', '{{ url("/") }}', '{{ Auth::id() }}')">
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<div class="padding-20px">
	<div class="frame-profile">
		<div class="profile">
			<div class="foto">
				<div class="image image-150px image-circle" id="place-picture" style="background-image: url({{ asset('/profile/photos/'.$p->foto) }});"></div>
			</div>
			<div class="info">
				<div class="user-name ctn-main-font ctn-standar" id="edit-name">{{ $p->name }}</div>
				<div>
					<p id="edit-about"><strong>{{ $p->username }}</strong></p>
				</div>
				<div>
					<p id="edit-about">{{ $p->about }}</p>
				</div>
				<div class="other">
					<a class="link" href="{{ $p->website }}" target="_blank">{{ $p->website }}</a>
				</div>
				<div>
					<div class="other mrg-bottom">
						<ul>
							<li>
								<a href="{{ url('/user/'.$p->id.'/following') }}">
									<div class="val">{{ $p->ttl_following }}</div>
									<div class="ttl">Following</div>
								</a>
							</li>
							<li>
								<a href="{{ url('/user/'.$p->id.'/followers') }}">
									<div class="val">{{ $p->ttl_followers }}</div>
									<div class="ttl">Followers</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="padding-bottom-20px">
	<div class="navigator nav-3x nav-theme-3 col-400px" id="post-nav">
		<ul>
			<a href="{{ url('/user/'.$p->id.'/boxs') }}">
				<li id="boxs">Boxs</li>
			</a>
			<a href="{{ url('/user/'.$p->id.'/designs') }}">
				<li id="design">Designs</li>
			</a>
			<a href="{{ url('/user/'.$p->id.'/saved') }}">
				<li id="saved">Saved</li>
			</a>
		</ul>
	</div>
</div>
@endforeach