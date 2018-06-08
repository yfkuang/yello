<html lang="{{ app()->getLocale() }}">
    <head>
		<meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <title>Yello | Call Tracking by Leadlabs | @yield('title', 'Call tracking')</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		<script src="{{ asset('js/app.js') }}"></script>
    </head>
    <body>
        <div class="container-fluid">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">Call tracking</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                </ul>
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
        @yield('content')
        </div>
    </body>
</html>
