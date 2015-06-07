<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Year;
use App\Day;
use App\Speaker;
use App\Exhibitor;

class YearController extends Controller {

	public function getYears() 
	{
		return json_encode(Year::all());
	}

	public function activateYear($id) {
		$years = Year::all();

		foreach($years as $year){
			if ($year->id == $id) {
				$year->active = true;
				$year->save();
			} else {
				$year->active = false;
				$year->save();
			}
		}

		return json_encode(Year::find($id));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$year = Year::create($request->input());

		if (Year::count() == 1){
			$year->active = true;
			$year->save();
		}

		return json_encode(Year::find($year->id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$year = Year::find($id);
		$year->fill($request->input());

		return json_encode(Year::find($year->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$year = Year::findOrFail($id);
		$year->delete();

		return json_encode($year);
	}

}
