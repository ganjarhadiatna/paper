<?php use App\ProfileModel; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Coca Code - Login</title>
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
                    <div class="ctn-main-font ctn-small ctn-center padding-20px">Login Here</div>
                </div>
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="block">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" type="text" class="txt txt-primary-color" name="email" value="{{ old('email') }}" placeholder="Email or Username" required autofocus>
                        </div>
                    </div>
                    <div class="block">
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input id="password" type="password" class="txt txt-primary-color" placeholder="Password" name="password" required>
                        </div>
                    </div>
                    <div>
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
                    <div class="block">
                        <div class="checkbox padding-15px">
                            <label class="btn btn-main4-color">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>
                    <div class="block">
                        <input type="submit" name="login" class="btn btn-main-color" value="Login">
                    </div>
                </form>
                <div class="block">
                    <a href="{{ route('password.request') }}">
                        <button class="btn btn-main4-color">
                            Forgot your Password?
                        </button>
                    </a>
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
                        <strong>Doesn't have an Account?</strong>
                    </div>
                    <a href="{{ url('/register') }}">
                        <input type="button" name="signup" class="btn btn-sekunder-color" value="Register Here">
                    </a>
                </div>
            </div>
            <div class="bot">
                <div class="padding-15px"></div>
            </div>
        </div>
    </div>
</body>
</html>
