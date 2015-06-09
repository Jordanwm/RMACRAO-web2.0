angular.module('app.controllers', ['app.services'])

.controller('YearsMainCtrl', function($scope, $modal, Years){
	$scope.years = Years.years;
	$scope.oneAtATime = true;

	$scope.addYear = function(){
		$scope.year = {};

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/yearModal.html',
			controller: 'YearCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(year){
			Years.createYear(year)
		});
	};

	$scope.editYear = function(year){
		$scope.year = angular.copy(year);
		$scope.year.year = new Date($scope.year.year + "-01-02");
		$scope.edit = true;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/yearModal.html',
			controller: 'YearCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(year){
			Years.updateYear(year);
		});
	};
	$scope.deleteYear = function(year){
		if (confirm("Are you sure you want to delete " + year.year + "?\n\nIt will delete all sessions, speakers, and exhibitors for this year.")){
			Years.deleteYear(year);
		}
	};

	$scope.activateYear = function(year){
		Years.activateYear(year);
	};

	$scope.info = "Click on a year to display its information."
	$scope.closeInfo = function() {
		$scope.info = null;
	};
})

.controller('YearCtrl', function($scope, $modalInstance){
	$scope.states = ['CO', 'NM', 'WY'];
	$scope.minDate = new Date();

	$scope.ok = function(){
		$modalInstance.close($scope.year)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
})

.controller('SessionsMainCtrl', function($scope, $modal, $modalStack, $filter, Sessions, Years){
	$scope.years = Years.years;
	$scope.days = Sessions.days;
	$scope.selectedDay = null;
	$scope.selectedSession = null;
	$scope.selectedEvent = null;

	$scope.selectDay = function(day){
		$scope.selectedDay = day;
		$scope.selectedSession = null;
		$scope.selectedEvent = null;
	};
	$scope.selectSession = function(session){
		$scope.selectedSession = session;
		$scope.selectedEvent = null;
	};
	$scope.selectEvent = function(event) {
		$scope.selectedEvent = event;
	};

	$scope.addDay = function(){
		$scope.day = {}
		$scope.edit = false;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/dayModal.html',
			controller: 'DayCtrl',
			scope: $scope,
			size: 'sm'
		});

		modalInstance.result.then(function(day){
			Sessions.createDay(day);
			$scope.selectedDay = null;
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
		});
	};
	$scope.editDay = function(day){
		$scope.day = angular.copy(day);
		$scope.edit = true;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/dayModal.html',
			controller: 'DayCtrl',
			scope: $scope,
			size: 'sm'
		});

		modalInstance.result.then(function(day){
			Sessions.updateDay(day);
			$scope.selectedDay = null;
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
			$scope.day = null;
			$scope.edit = false;
		});
	};
	$scope.deleteDay = function(day){
		if (confirm("Are you sure you want to delete " + $filter('date')(day.day, 'fullDate') + "?")){
			$modalStack.dismissAll('Delete');
			$scope.selectedDay = null;
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
			Sessions.deleteDay(day);
		}
	};

	$scope.addSession = function(){
		$scope.session = {};
		$scope.edit = false;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/sessionModal.html',
			controller: 'SessionCtrl',
			scope: $scope,
			size: 'sm'
		});

		modalInstance.result.then(function(session){
			Sessions.createSession($scope.selectedDay, session);
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
		});
	};
	$scope.editSession = function(session){
		$scope.edit = true;
		$scope.session = angular.copy(session);

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/sessionModal.html',
			controller: 'SessionCtrl',
			scope: $scope,
			size: 'sm'
		});

		modalInstance.result.then(function(session){
			Sessions.updateSession($scope.selectedDay, session)
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
			$scope.session = null;
			$scope.edit = false;
		});
	};
	$scope.deleteSession = function(session){
		if (confirm("Are you sure you want to delete " + session.name + "?")){
			$modalStack.dismissAll('Delete');
			$scope.selectedSession = null;
			$scope.selectedEvent = null;
			Sessions.deleteSession($scope.selectedDay, session);
		}
	};

	$scope.addEvent = function(){
		$scope.edit = false;
		$scope.event = {};
		$scope.event.presenters = [];
		$scope.event.facilitators = [];

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/eventModal.html',
			controller: 'EventCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(event){
			Sessions.createEvent($scope.selectedDay, $scope.selectedSession, event);
			$scope.selectedEvent = null;
		});
	};
	$scope.editEvent = function(event){
		$scope.edit = true;
		$scope.event = angular.copy(event);

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/eventModal.html',
			controller: 'EventCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(event){
			Sessions.updateEvent($scope.selectedDay, $scope.selectedSession, event);
			$scope.selectedEvent = null;
			$scope.event = null;
			$scope.edit = false;
		});
	};
	$scope.deleteEvent = function(event){
		if (confirm("Are you sure you want to delete " + event.title + "?")){
			$modalStack.dismissAll('Delete');
			$scope.selectedEvent = null;
			Sessions.deleteEvent($scope.selectedDay, $scope.selectedSession, event);
		}
	};
})

.controller('DayCtrl', function($scope, $modalInstance){
	$scope.ok = function(){
		$modalInstance.close($scope.day)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
})

.controller('SessionCtrl', function($scope, $modalInstance){
	if (!$scope.session.stime) {
		var d = new Date($scope.selectedDay.day);
		d.setHours(8);
		d.setMinutes(0);

		$scope.session.stime = d;
		$scope.session.ftime = d;
	}


	$scope.ok = function(){
		$modalInstance.close($scope.session)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
})

.controller('EventCtrl', function($scope, $modalInstance){
	$scope.addPresenter = function(){
		$scope.addEditP = true;
		$scope.addEditF = false;
		$scope.presenter = {name:"", title:""};
		$scope.facilitator = null;
		$scope.presenterForm.$setPristine();
		$scope.presenterForm.$setUntouched();
	};
	$scope.editPresenter = function(presenter){
		$scope.addEditP = true;
		$scope.addEditF = false;
		$scope.editP = presenter;
		$scope.editF = null;
		$scope.facilitator = null;
		$scope.presenter = angular.copy(presenter);
	};
	$scope.submitPresenter = function(){
		$scope.addEditP = false;

		if ($scope.editP) {
			index = $scope.event.presenters.indexOf($scope.editP);
			$scope.event.presenters[index] = $scope.presenter;
		} else {
			$scope.event.presenters.push($scope.presenter);
		}

		$scope.editP = null;
		$scope.presenter = null;
	};
	$scope.removePresenter = function(presenter){
		if (confirm("Are you sure you want to remove this presenter?")){
			$scope.event.presenters.splice($scope.event.presenters.indexOf(presenter),1);
		}
	};
	$scope.closeP = function(){
		$scope.addEditP = false;
		$scope.editP = null;
		$scope.presenter = null;
		$scope.errors = [];
	};

	$scope.addFacilitator = function(){
		$scope.addEditF = true;
		$scope.addEditP = false;
		$scope.facilitator = {};
		$scope.presenter = null;
		$scope.facilitatorForm.$setPristine();
		$scope.facilitatorForm.$setUntouched();
	};
	$scope.editFacilitator = function(facilitator){
		$scope.addEditF = true;
		$scope.addEditP = false;
		$scope.editF = facilitator;
		$scope.editP = null;
		$scope.facilitator = angular.copy(facilitator);
		$scope.presenter = null;
	};
	$scope.submitFacilitator = function(){
		$scope.addEditF = false;

		if ($scope.editF) {
			index = $scope.event.facilitators.indexOf($scope.editF);
			$scope.event.facilitators[index] = $scope.facilitator;
		} else {
			$scope.event.facilitators.push($scope.facilitator);
		}

		$scope.editF = null;
		$scope.facilitator = null;
	};
	$scope.removeFacilitator = function(facilitator){
		if (confirm("Are you sure you want to remove this facilitator?")){
			$scope.event.facilitators.splice($scope.event.facilitators.indexOf(facilitator),1);
		}
	};
	$scope.closeF = function(){
		$scope.addEditF = false;
		$scope.editF = null;
		$scope.facilitator = null;
		$scope.errors = [];
	};

	$scope.ok = function(){
		$modalInstance.close($scope.event)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
})

.controller('SpeakersMainCtrl', function($scope, $modal, Speakers, Years){
	$scope.years = Years.years;
	$scope.speakers = Speakers.speakers;
	$scope.oneAtATime = true;

	$scope.addSpeaker = function(){
		$scope.speaker = {};

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/speakerModal.html',
			controller: 'SpeakerCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(speaker){
			Speakers.createSpeaker(speaker);
		});
	};

	$scope.editSpeaker = function(speaker){
		$scope.speaker = angular.copy(speaker);
		$scope.edit = true;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/speakerModal.html',
			controller: 'SpeakerCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(speaker){
			Speakers.updateSpeaker(speaker);
		});
	};
	$scope.deleteSpeaker = function(speaker){
		if (confirm("Are you sure you want to delete " + speaker.name + "?")){
			Speakers.deleteSpeaker(speaker);
		}
	};

	$scope.info = "Click on a name to display their information."
	$scope.closeInfo = function() {
		$scope.info = null;
	};
})

.controller('SpeakerCtrl', function($scope, $modalInstance){
	if (!$scope.speaker.stime) {
		var d = new Date();
		d.setHours(8);
		d.setMinutes(0);

		$scope.speaker.stime = d;
		$scope.speaker.ftime = d;
	}

	$scope.ok = function(){
		$modalInstance.close($scope.speaker)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
})

.controller('ExhibitorsMainCtrl', function($scope, $modal, Exhibitors, Years){
	$scope.years = Years.years;
	$scope.exhibitors = Exhibitors.exhibitors;
	$scope.oneAtATime = true;

	$scope.addExhibitor = function(){
		$scope.exhibitor = {};
		$scope.exhibitor.staff = [];

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/exhibitorModal.html',
			controller: 'ExhibitorCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(exhibitor){
			Exhibitors.createExhibitor(exhibitor);
		});
	};

	$scope.editExhibitor = function(exhibitor){
		$scope.exhibitor = angular.copy(exhibitor);
		$scope.edit = true;

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: 'templates/exhibitorModal.html',
			controller: 'ExhibitorCtrl',
			scope: $scope,
			size: 'lg'
		});

		modalInstance.result.then(function(exhibitor){
			Exhibitors.updateExhibitor(exhibitor);
		});
	};
	$scope.deleteExhibitor = function(exhibitor){
		if (confirm("Are you sure you want to delete " + exhibitor.name + "?")){
			Exhibitors.deleteExhibitor(exhibitor);
		}
	};

	$scope.info = "Click on a name to display their information."
	$scope.closeInfo = function() {
		$scope.info = null;
	};
})

.controller('ExhibitorCtrl', function($scope, $modalInstance){
	$scope.states = ['AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'];

	$scope.addStaff = function(){
		$scope.addEditS = true;
		$scope.staff = {name:"", title:""};
		$scope.staffForm.$setPristine();
		$scope.staffForm.$setUntouched();
	};
	$scope.editStaff = function(staff){
		$scope.addEditS = true;
		$scope.editS = staff;
		$scope.staff = angular.copy(staff);
	};
	$scope.submitStaff = function(){
		$scope.addEditS = false;

		if ($scope.editS) {
			index = $scope.exhibitor.staff.indexOf($scope.editS);
			$scope.exhibitor.staff[index] = $scope.staff;
		} else {
			$scope.exhibitor.staff.push($scope.staff);
		}

		$scope.editS = null;
		$scope.staff = null;
	};
	$scope.removeStaff = function(staff){
		if (confirm("Are you sure you want to remove this staff member?")){
			$scope.exhibitor.staff.splice($scope.exhibitor.staff.indexOf(staff),1);
		}
	};
	$scope.closeS = function(){
		$scope.addEditS = false;
		$scope.editS = null;
		$scope.staff = null;
	};

	$scope.ok = function(){
		$modalInstance.close($scope.exhibitor)
	};
	$scope.cancel = function(){
		$modalInstance.dismiss('cancel')
	};
});