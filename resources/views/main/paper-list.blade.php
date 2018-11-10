<div class="content-popup" id="paper-popup">
    <div class="place-select middle">
        <div class="sc-header">
            <div class="sc-place">
                <div>
                    <div class="sc-grid sc-grid-2x">
                        <div class="sc-col-1">
                            <div class="ctn-main-font ctn-16px ctn-sek-color ctn-bold ctn-middle">Choose Paper</div>
                        </div>
                        <div class="sc-col-2 txt-right">
                            <button 
                                class="btn btn-circle btn-primary-color btn-focus" 
                                onclick="opPaper('hide')"
                                type="button">
                                <span class="fas fa-lg fa-times"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="place" id="place-paper">
		@if (count($papers) == 0)
			<div class="frame-empty">
				<div class="icn fa fa-lg fa-thermometer-empty btn-main-color"></div>
				<div class="ttl padding-15px">Paper empty</div>
			</div>
		@else
            @foreach ($papers as $pp)
                <div class="frame-small-paper paper-list" id="frame-small-paper-{{ $pp->idpapers }}" key="{{ $pp->idpapers }}">
                    @if (!is_null($pp->cover))
                        <div class="grid-1 image image-40px image-radius"
                            style="background-image: url({{ asset('/story/thumbnails/'.$pp->cover) }})"></div>
                    @else
                        <div class="grid-1 image image-40px image-radius">
                            <span class="icn fa fa-lg fa-th-large"></span>
                        </div>
                    @endif
                    <div class="grid-2">
                        <div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold" key="{{ $pp->title }}">
                            {{ $pp->title }}
                        </div>
                    </div>
                    <div class="grid-3">
                        <button class="chk btn btn-circle btn-primary-color" type="button">
                            <span class="fa fa-lg fa-check"></span>
                        </button>
                    </div>
                </div>
            @endforeach
		@endif
		</div>
		<div>
			<div class="frame-small-paper">
				<div class="grid-1">
					<a href="{{ url('/compose/paper') }}">
						<button class="chk btn btn-circle btn-main-color" type="button">
							<span class="fa fa-lg fa-plus"></span>
						</button>
					</a>
				</div>
				<div class="grid-2">
					<div class="ttl ctn-main-font ctn-14px ctn-sek-color ctn-bold">
						Create Paper
					</div>
				</div>
				<div class="grid-3"></div>
            </div>
        </div>
    </div>
</div>