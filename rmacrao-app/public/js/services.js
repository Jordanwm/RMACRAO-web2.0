angular.module('app.services', [])

.factory('Years', function($http){
	var o = {};
	o.years = [];
	o.createYear = function(year){
		year.active = false;
		$http.post('/api/years', year).then(function(res){
			o.years.push(res.data);
		});
	};
	o.updateYear = function(year){
		$http.put('/api/years/' + year.id, year).then(function(res){
			o.years.forEach(function(y, i){
				if (y.id == res.data.id){
					o.years[i] = res.data;
				}
			});
		});
	};
	o.deleteYear = function(year){
		$http.delete('/api/years/' + year.id).then(function(res){
			o.years.forEach(function(y, i){
				if (y.id == res.data.id){
					o.years.splice(i,1);
				}
			});
		});
	};
	o.activateYear = function(year){
		$http.put('/api/years/' + year.id + '/activate', year).then(function(res){
			o.years.forEach(function(y, i){
				if (y.id == res.data.id){
					o.years[i].active = res.data.active;
				} else {
					o.years[i].active = false;
				}
			});
		});
	};
	o.getAll = function(){
		$http.get('/api/years').then(function(res){
			o.years = res.data;
		});
	};
	return o;
})

.factory('Sessions', function($http){
	var o = {};
	o.days = [];
	o.createDay = function(day){
		$http.post('/api/days', day).then(function(res){
			o.days.push(res.data);
		});
	};
	o.updateDay = function(day){
		$http.put('/api/days/' + day.id, day).then(function(res){
			o.days.forEach(function(d, i){
				if (d.id == res.data.id){
					o.days[i] = res.data
				}
			});
		});
	};
	o.deleteDay = function(day){
		$http.delete('/api/days/' + day.id).then(function(res){
			o.days.forEach(function(d, i){
				if (d.id == res.data.id){
					o.days.splice(i,1);
				}
			})
		});
	};
	o.createSession = function(day, session){
		$http.post('/api/days/' + day.id + '/sessions', session).then(function(res){
			o.days[o.days.indexOf(day)].sessions.push(res.data);
		});
	};
	o.updateSession = function(day, session){
		$http.put('/api/days/' + day.id + '/sessions/' + session.id, session).then(function(res){
			var index = o.days.indexOf(day);
			o.days[index].sessions.forEach(function(s,i){
				if (s.id == res.data.id) {
					o.days[index].sessions[i] = res.data;
				}
			});
		});
	};
	o.deleteSession = function(day, session) {
		$http.delete('/api/days/' + day.id + '/sessions/' + session.id).then(function(res){
			var index = o.days.indexOf(day);
			o.days[index].sessions.forEach(function(s, i){
				if( s.id == res.data.id){
					o.days[index].sessions.splice(i,1);
				}
			});
		});
	};
	o.createEvent = function(day, session, event) {
		$http.post('/api/days/' + day.id + '/sessions/' + session.id + '/events', event).then(function(res){
			var dayI = o.days.indexOf(day);
			var sessionI = o.days[dayI].sessions.indexOf(session);
			o.days[dayI].sessions[sessionI].events.push(res.data);
		});
	};
	o.updateEvent = function(day, session, event) {
		$http.put('/api/days/' + day.id + '/sessions/' + session.id + '/events/' + event.id, event).then(function(res){
			var dayI = o.days.indexOf(day);
			var sessionI = o.days[dayI].sessions.indexOf(session);
			o.days[dayI].sessions[sessionI].events.forEach(function(e,i){
				if (e.id == res.data.id) {
					o.days[dayI].sessions[sessionI].events[i] = res.data;
				}
			});
		});
	};
	o.deleteEvent = function(day, session, event) {
		$http.delete('/api/days/' + day.id + '/sessions/' + session.id + '/events/' + event.id).then(function(res){
			var dayI = o.days.indexOf(day);
			var sessionI = o.days[dayI].sessions.indexOf(session)
			o.days[dayI].sessions[sessionI].events.forEach(function(e, i){
				if( e.id == res.data.id){
					o.days[dayI].sessions[sessionI].events.splice(i,1);
				}
			});
		});
	};
	o.getAll = function(){
		return $http.get('/api/days').then(function(res){
			o.days = angular.copy(res.data);
		});
	};
	return o;
})

.factory('Speakers', function($http){
	var o = {};
	o.speakers = [];
	o.createSpeaker = function(speaker){
		$http.post('/api/speakers', speaker).then(function(res){
			o.speakers.push(res.data);
		});
	};
	o.updateSpeaker = function(speaker){
		$http.put('/api/speakers/' + speaker.id, speaker).then(function(res){
			o.speakers.forEach(function(s, i){
				if (s.id == res.data.id){
					o.speakers[i] = res.data;
				}
			});
		});
	};
	o.deleteSpeaker = function(speaker){
		$http.delete('/api/speakers/' + speaker.id).then(function(res){
			o.speakers.forEach(function(s, i){
				if (s.id == res.data.id){
					o.speakers.splice(i,1);
				}
			});
		});
	};
	o.getAll = function(){
		return $http.get('/api/speakers').then(function(res){
			o.speakers = res.data;
		});
	};
	return o;
});