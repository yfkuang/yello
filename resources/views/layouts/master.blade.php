<html lang="{{ app()->getLocale() }}">
    <head>
		<meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">-->
        <title>Yello | @yield('title')</title>
    </head>
    <body>
        <nav class="navbar-custom navbar-default">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="{{ route('index') }}"><img src="{{ asset('img/yello.png') }}"></a>
				</div>
				@if($uid)
				<ul class="nav navbar-nav">
					<li><a href="{{ route('dashboard') }}">Dashboard</a></li>
					<li><button class="nav-addNumber" data-toggle="modal" data-target="#addNumber">Add Number&nbsp;&nbsp;<i class="fas fa-plus-circle" style="color: #7CD164"></i></button></li>
					<li>
						<a href="{{ route('manage_numbers') }}"><i class="fas fa-cog"></i></a>
					</li>
					<li>{{ Form::open(['route' => 'login', 'method' => 'POST', 'class' => 'logout-form']) }}{!! Form::hidden('tokenID') !!}{!! Form::button('Logout', ['class' => 'btn btn-primary logout']) !!}{{ Form::close() }}</li>
				</ul>
				@endif
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
		
		<!--Content-->
		<div class="container">
        	@yield('content')
		</div>
		
		<!--Add Number-->
		<div class="modal fade" id="addNumber" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Add Number</h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						@include('available_numbers.addNumber')
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		
		<footer>
			<div class="container">
				&copy; @php echo date('Y'); @endphp Leadlabs, LLC. All rights Reserved. <a href="#">Privacy</a> | <a href="#">Terms</a>
			</div>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
		<script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
