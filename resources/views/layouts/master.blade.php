<html lang="{{ app()->getLocale() }}">
    <head>
		<meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
        <title>Yello | Call Tracking by Leadlabs | @yield('title')</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		<script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="/"><img src="{{ asset('img/yello.png') }}"></a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="{{ route('dashboard') }}">Dashboard</a></li>
						<li><a href="">Add Number</a></li>
					</ul>
				</div>
			</div>
        </nav>
        <div class="errors">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
		<div class="container">
        	@yield('content')
		</div>
		<footer>
			<div class="container">
				&copy; @php echo date('Y'); @endphp Leadlabs, LLC. All rights Reserved. <a>Privacy Policy</a> | <a>Terms</a>
			</div>
		</footer>
    </body>
</html>
