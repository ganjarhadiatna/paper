<?php use App\ProfileModel; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Coca Code</title>
	<meta charset=utf-8>
    <meta name=description content="">
    <meta name=viewport content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ICON -->
    <link href="{{ asset('/img/C/6.png') }}" rel='SHORTCUT ICON'/>

	<!-- SASS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/css/fontawesome-all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('sass/main.css') }}">

	<!-- JS -->
	<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
</head>
<body>
	<div class="frame-guess">
		<div class="grid-2x">
			<div class="grid-1">
				<div class="bl detail col-400px">
					<ul>
						<li>
							<div class="icn fas fa-lg fa-box-open"></div>
							<div class="ttl ctn-main-font ctn-18px">Put your designs on a boxs.</div>
						</li>
						<li>
							<div class="icn fas fa-lg fa-lightbulb"></div>
							<div class="ttl ctn-main-font ctn-18px">Collecting much ideas.</div>
						</li>
						<li>
							<div class="icn fas fa-lg fa-users"></div>
							<div class="ttl ctn-main-font ctn-18px">Creat relations and hired.</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="grid-2">
				<div class="bl sign col-400px">
					<div class="image image-all" style="background-image: url('{{ asset('/img/CocaCode/3.png') }}')"></div>
					<div class="ttl ctn-main-font ctn-20px ctn-sek-color padding-20px">
						It's a place for Designers.
					</div>
					<div class="padding-10px"></div>
					<div class="banner">
						<div class="cover">
							<div class="title">
								<div class="ttl ctn-main-font ctn-14px ctn-bold ctn-sek-color padding-20px">
									Join Pictlr Today.
								</div>
								<div class="frame-info width-all">
									<a href="{{ url('/login') }}">
										<button class="mrg-bottom create btn btn-sekunder-color" id="compose">
											<span class="ttl-post">Login</span>
										</button>
									</a>
									<a href="{{ url('/register') }}">
										<button class="btn btn-main-color" id="compose">
											<span class="ttl-post">Register</span>
										</button>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="bottom">
			<ul class="ctn-main-font ctn-14px">
				<li>
					<a href="{{ url('/') }}">Home Feeds</a>
				</li>
				<li>
					<a href="{{ url('/fresh') }}">Fresh</a>
				</li>
				<li>
					<a href="{{ url('/trending') }}">Trending</a>
				</li>
				<li>
					<a href="{{ url('/popular') }}">Populars</a>
				</li>
				<li>
					<a href="#">About Us</a>
				</li>
				<li>
					<a href="#">Privacy</a>
				</li>
				<li>
					<a href="#">Terms</a>
				</li>
				<li>
					<a href="#">Policy</a>
				</li>
				<li>
					<a href="#">FAQ</a>
				</li>
				<li>
					<a href="#">Jobs</a>
				</li>
				<li>
					<a href="#">Help</a>
				</li>
			</ul>
		</div>
	</div>
</body>
</html>