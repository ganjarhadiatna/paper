<script type="text/javascript">
	$(document).ready(function() {
		$('#post-nav ul li').each(function(index, el) {
			$(this).removeClass('active');
			$('#{{ $nav }}').addClass('active');
		});
	});
</script>
@foreach ($profile as $p)
<div>
	<div class="frame-profile">
		<div class="profile col-700px">
			<div class="foto">
				<div class="image image-140px image-circle" id="place-picture" style="background-image: url({{ asset('/profile/photos/'.$p->foto) }});"></div>
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
					<a class="link ctn-main-font ctn-sek-color ctn-link" href="{{ $p->website }}" target="_blank">{{ $p->website }}</a>
				</div>
				<div>
					<div class="menu-val">
						<ul>
							<li>
								<a href="{{ url('/user/'.$p->id.'/papers') }}">
									<div class="val">{{ $p->ttl_papers }}</div>
									<div class="ttl">Papers</div>
								</a>
							</li>
							<li>
								<a href="{{ url('/user/'.$p->id.'/designs') }}">
									<div class="val">{{ $p->ttl_designs }}</div>
									<div class="ttl">Designs</div>
								</a>
							</li>
							<li>
								<a href="{{ url('/user/'.$p->id.'/saved') }}">
									<div class="val">{{ $p->ttl_saved }}</div>
									<div class="ttl">Saved</div>
								</a>
							</li>
							<li class="right">
								@if (Auth::id() == $p->id)
									<a href="{{ url('/compose') }}">
										<button class="btn btn-circle btn-primary-color btn-focus">
											<span class="fas fa-lg fa-plus"></span>
										</button>
									</a>
									<a href="{{ url('/me/setting') }}">
										<button class="btn btn-circle btn-primary-color btn-focus">
											<span class="fas fa-lg fa-cog"></span>
										</button>
									</a>
								@endif
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
			<a href="{{ url('/user/'.$p->id.'/papers') }}">
				<li id="papers">Papers</li>
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