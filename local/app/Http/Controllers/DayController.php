<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Year;
use App\Day;
use App\Session;
use App\Event;
use App\Presenter;
use App\Facilitator;

class DayController extends Controller {

	private function activeYear() {
		return Year::where('active', '=', '1')->first();
	}

	private function getDay($id) {
		$year = $this->activeYear();
		if ($year == null) {
			return null;
		} else {
			$day = Day::with('sessions', 'sessions.events', 'sessions.events.presenters','sessions.events.facilitators')->where('year_id', '=', $year->id)->find($id);
			return $day;
		}
	}

	public function getDays() 
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$days = Day::with('sessions', 'sessions.events', 'sessions.events.presenters','sessions.events.facilitators')->where('year_id', '=', $year->id)->get();
			return json_encode($days);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeDay(Request $request)
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$day = Day::create($request->input());

			$day->year()->associate($year);
			$day->save();

			return json_encode($this->getDay($day->id));
		}
	}

	public function storeSession(Request $request, $id)
	{
		$day = Day::findOrFail($id);
		$session = Session::create($request->input());

		$day->sessions()->save($session);

		return json_encode(\App\Session::with('events')->find($session->id));
	}

	public function storeEvent(Request $request, $dayId, $sessionId)
	{
		$data = $request->input();
		$presenters = [];
		$facilitators = [];
		
		$event = Event::create($data);
		
		foreach( $data['presenters'] as $presenter) {
			$p = Presenter::create($presenter);
			$p->event_id = $event->id;
			$p->save();
		}

		foreach( $data['facilitators'] as $facilitator) {
			$f = Facilitator::create($facilitator);
			$f->event_id = $event->id;
			$f->save();
		}

		$session = Session::findOrFail($sessionId);
		$session->events()->save($event);

		$e = Event::with('presenters', 'facilitators')->find($event->id);

		return json_encode($e);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateDay(Request $request, $id)
	{
		$day = $this->getDay($id);
		$day->day = $request->input('day');
		$day->save();

		return json_encode($this->getDay($day->id));
	}

	public function updateSession(Request $request, $dayId, $sessionId)
	{
		$session = Session::findOrFail($sessionId);
		$session->fill($request->input());
		$session->save();

		return json_encode(Session::with('events','events.presenters', 'events.facilitators')->find($session->id));
	}

	public function updateEvent(Request $request, $dayId, $sessionId, $eventId)
	{
		$data = $request->except('facilitators', 'presenters');
		
		$event = Event::findOrFail($eventId);
		$event->fill($data);
		$event->save();
		
		$data = $request->only('facilitators', 'presenters');

		// Store the presenters information
		$indexes = [];
		foreach($data['presenters'] as $presenter) {
			if (isset($presenter['id'])) {
				$p = Presenter::find($presenter['id']);
				$p->fill($presenter);
				$p->save();
				array_push($indexes, $p->id);
			} else {
				$p = Presenter::create($presenter);
				$event->presenters()->save($p);
				array_push($indexes, $p->id);
			}
		}

		// Get updated event with new presenters.
		$presenters = $event->presenters()->get();

		foreach($presenters as $key => $presenter) {
			$found = false;
			foreach($indexes as $i) {
				if ($presenter->id == $i){
					$found = true;
					break;
				}
			}
			if (!$found) {
				$presenters[$key]->delete();
			}
		}

		// Store the facilitators information
		$indexes = [];
		foreach($data['facilitators'] as $facilitator) {
			if (isset($facilitator['id'])) {
				$p = Facilitator::find($facilitator['id']);
				$p->fill($facilitator);
				$p->save();
				array_push($indexes, $p->id);
			} else {
				$p = Facilitator::create($facilitator);
				$event->facilitators()->save($p);
				array_push($indexes, $p->id);
			}
		}

		// Get updated event with new facilitators.
		$facilitators = $event->facilitators()->get();

		foreach($facilitators as $key => $facilitator) {
			$found = false;
			foreach($indexes as $i) {
				if ($facilitator->id == $i){
					$found = true;
					break;
				}
			}
			if (!$found) {
				$facilitators[$key]->delete();
			}
		}
		
		$e = Event::with('presenters', 'facilitators')->find($eventId);

		return json_encode($e);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroyDay($id)
	{
		$day = Day::findOrFail($id);
		foreach($day->sessions() as $session){
			foreach($session->events() as $event){
				$event->presenters()->delete();
				$event->facilitators()->delete();
			}
			$session->events()->delete();
		}
		$day->sessions()->delete();
		$day->delete();

		return json_encode($day);
	}

	public function destroySession($dayId, $sessionId)
	{
		$session = Session::findOrFail($sessionId);
		foreach($session->events() as $event){
			$event->presenters()->delete();
			$event->facilitators()->delete();
		}

		$session->events()->delete();
		$session->delete();

		return json_encode($session);
	}

	public function destroyEvent($dayId, $sessionId, $eventId)
	{
		$event = Event::findOrFail($eventId);
		$event->presenters()->delete();
		$event->facilitators()->delete();
		$event->delete();

		return $event;
	}

}
