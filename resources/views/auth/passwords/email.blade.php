<?php use App\ProfileModel; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Pictlr - Login</title>
	<meta charset=utf-8>
    <meta name=description content="">
    <meta name=viewport content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ICON -->
    <link href="{{ asset('/img/P/5.png') }}" rel='SHORTCUT ICON'/>

	<!-- SASS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/css/fontawesome-all.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('sass/main.css') }}">

	<!-- JS -->
	<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
</head>
<body>
    <div class="frame-sign">
        <div class="mid">
            <div class="block">
                <div class="padding-20px">
                    <div class="padding-20px">
                        <h1 class="ctn-main-font ctn-small ctn-center padding-20px">Reset Password</h1>
                    </div>
                </div>
            </div>
            <div class="block">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="block">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input id="email" type="email" class="txt txt-primary-color" placeholder="Your Email Address" name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="padding-10px"></div>
                <div class="block">
                    <div class="form-group">
                        <button type="submit" class="btn btn-main-color">
                            Send Password Reset Link
                        </button>
                    </div>
                </div>
            </form>
            <div class="padding-20px">
                <div class="padding-bottom">
                    <span class="fa fa-lg fa-circle"></span>
                    <span class="fa fa-lg fa-circle"></span>
                    <span class="fa fa-lg fa-circle"></span>
                </div>
            </div>
            <div class="block center">
                <a href="{{ url('/login') }}">
                    <input type="button" name="login" class="btn btn-sekunder-color" value="Login">
                </a>
                <div class="padding-5px"></div>
                <a href="{{ url('/register') }}">
                    <input type="button" name="signup" class="btn btn-sekunder-color" value="Register Here">
                </a>
            </div>
        </div>
        <div class="bot">
            <div class="padding-15px"></div>
        </div>
    </div>
</body>
</html>
