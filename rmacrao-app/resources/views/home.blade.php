<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>RMACRAO WEB APP</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/styles.css">
</head>
<body>
	<nav class="navbar navbar-static-top navbar-inverse">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">RMACRAO</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					@if(!Auth::guest())
						<li><a href="app">App</a></li>
					@endif
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					@if(Auth::guest())
						<li><a href="/login">Login</a></li>
					@else
						<li><a>Welcome {{Auth::user()->email}}</a></li>
						<li><a href="/logout">Logout</a></li>
					@endif
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Welcome
					</div>
					<div class="panel-body">
						To use this application you will have to log in using google. To begin, click the login button in the top right hand corner.
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</body>
</html>
