<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>RMACRAO WEB APP</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/styles.css">
</head>
<body ng-app="app">
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
					<li><a ui-sref="home">Home</a></li>
					<li><a ui-sref="years">Years</a></li>
					<li><a ui-sref="maps">Maps</a></li>
					<li><a ui-sref="sessions">Sessions</a></li>
					<li><a ui-sref="speakers">Speakers</a></li>
					<li><a ui-sref="exhibitors">Exhibitors</a></li>
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
		<ui-view></ui-view>
	</div>
	
	<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.16/angular.min.js"></script>
	<script src="http://angular-ui.github.io/ui-router/release/angular-ui-router.min.js"></script>
	<script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.13.0.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="/js/app.js"></script>
	<script src="/js/controllers.js"></script>
	<script src="/js/services.js"></script>
</body>
</html>
