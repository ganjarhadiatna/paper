<div class="frame-paper">
    <div class="top">
        <a href="{{ url('/paper/'.$bx->idpapers) }}">
            @if ($bx->ttl_image != 0)
                <div class="pl-icn pl-image">
                    <div class="bg-image" style="background-image: url({{ asset('/story/thumbnails/'.$bx->cover1) }})"></div>
                    <div class="bg-image" style="background-image: url({{ asset('/story/thumbnails/'.$bx->cover2) }})"></div>
                    <div class="bg-image" style="background-image: url({{ asset('/story/thumbnails/'.$bx->cover3) }})"></div>
                </div>
            @else
                <div class="pl-icn">
                    <span class="mn-icn fas fa-lg fa-box-open"></span>
                </div>
            @endif
        </a>
    </div>
    <div class="mid">
        <div class="padding-top-10px">
            <h3>
                <a href="{{ url('/paper/'.$bx->idpapers) }}" class="ctn-main-font ctn-sek-color">{{ $bx->title }}</a>
            </h3>
        </div>
        <div class="menu-val">
			<ul>
				<li>
					<div class="val">{{ $bx->views }}</div>
					<div class="ttl">Visited</div>
				</li>
				<li>
					<div class="val">{{ $bx->ttl_image }}</div>
					<div class="ttl">Designs</div>
				</li>
                <li class="right">
					<a href="{{ url('/user/'.$bx->id) }}">
						<div class="image image-40px image-circle" style="background-image: url({{ asset('/profile/photos/'.$bx->foto) }});"></div>
					</a>
				</li>
			</ul>
		</div>
    </div>
</div>