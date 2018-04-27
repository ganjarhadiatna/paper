<?php use App\ProfileModel; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Coca Code - Register</title>
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
    <div class="frame-home">
        <div class="frame-sign">
            <div class="mid">
                <div class="block">
                    <div class="ctn-main-font ctn-small  ctn-center padding-20px">Register</div>
                </div>
                <div class="block center">
                    <div class="padding-bottom-10px">
                        <strong>Please put your data on all the fields.</strong>
                    </div>
                </div>
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                    <div class="block">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input id="name" type="text" class="txt txt-primary-color" placeholder="Full Name" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>
                    <div class="block">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" type="email" class="txt txt-primary-color" placeholder="Your Email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="block">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" type="password" class="txt txt-primary-color" placeholder="Create Password" name="password" required>
                        </div>
                    </div>
                    <div class="block">
                        <div class="form-group">
                            <input id="password-confirm" type="password" class="txt txt-primary-color" placeholder="Confirm Password" name="password_confirmation" required>
                        </div>
                    </div>
                    <div>
                        <div>
                            @if ($errors->has('name'))
                            <div class="block">
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            </div>
                            @endif
                            @if ($errors->has('email'))
                            <div class="block">
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            </div>
                            @endif
                            @if ($errors->has('password'))
                            <div class="block">
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="padding-10px"></div>
                    <div class="block">
                        <input type="submit" name="signup" class="btn btn-main-color" value="Register">
                    </div>
                    <div class="padding-20px">
                        <div class="padding-bottom">
                            <span class="fa fa-lg fa-circle"></span>
                            <span class="fa fa-lg fa-circle"></span>
                            <span class="fa fa-lg fa-circle"></span>
                        </div>
                    </div>
                    <div class="block center">
                        <div class="padding-bottom-10px">
                            <strong>Has been have an account?</strong>
                        </div>
                        <a href="{{ url('/login') }}">
                            <input type="button" name="login" class="btn btn-sekunder-color" value="Login">
                        </a>
                    </div>
                </form>
            </div>
            <div class="bot">
                <div class="padding-15px"></div>
            </div>
        </div>
    </div>
</body>
</html>
