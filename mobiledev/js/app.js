var app = angular.module('app',['ui.bootstrap', 'ui.router', 'app.controllers', 'app.services']);

app.run(function(Years){
	Years.getAll();
});

app.config(function($stateProvider, $urlRouterProvider) {

	$urlRouterProvider.otherwise("/");

	$stateProvider
		.state('home', {
			url: "/",
			templateUrl: "templates/home.html"
		})
		.state('years', {
			url: "/years",
			controller: "YearsMainCtrl",
			templateUrl: "templates/years.html"
		})
		.state('sessions',{
			url: "/sessions",
			controller: "SessionsMainCtrl",
			templateUrl: "templates/sessions.html",
			resolve: {
				sessionsPromise: function(Sessions){
					return Sessions.getAll();
				}
			}
		})
		.state('speakers',{
			url: "/speakers",
			controller: "SpeakersMainCtrl",
			templateUrl: "templates/speakers.html",
			resolve: {
				speakersPromise: function(Speakers){
					return Speakers.getAll();
				}
			}
		})
		.state('exhibitors',{
			url: "/exhibitors",
			controller: "ExhibitorsMainCtrl",
			templateUrl: "templates/exhibitors.html",
			resolve: {
				exhibitorsPromise: function(Exhibitors){
					return Exhibitors.getAll();
				}
			}
		})
		.state('maps', {
			url: "/maps",
			controller: "MapsMainCtrl",
			templateUrl: "templates/maps.html",
			resolve: {
				mapsPromise: function(Maps){
					return Maps.getAll();
				}
			}
		});
});