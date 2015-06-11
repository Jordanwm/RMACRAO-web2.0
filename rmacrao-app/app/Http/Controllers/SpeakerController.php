<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Year;
use App\Speaker;

use File;

class SpeakerController extends Controller {

	private function activeYear() {
		return Year::where('active', '=', '1')->first();
	}

	private function getSpeaker($id) {
		$year = $this->activeYear();
		if ($year == null) {
			return null;
		} else {
			$speaker = Speaker::where('year_id', '=', $year->id)->find($id);
			return $speaker;
		}
	}

	public function getSpeakers() 
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$speakers = Speaker::where('year_id', '=', $year->id)->get();
			return json_encode($speakers);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$speaker = Speaker::create($request->input());

			$speaker->year()->associate($year);
			$speaker->save();

			return json_encode($this->getSpeaker($speaker->id));
		}
	}

	public function storeImage(Request $request) {
		//Image uploading
		$speaker = Speaker::find($request->input('id'));

		if ($speaker->img_path) {
			File::delete(public_path() . $speaker->img_path);
		}
		
		$imageName = 'speaker_' . $speaker->id . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

		$request->file('image')->move(base_path() . '/public/images/uploads/', $imageName);
		$speaker->img_path = '/images/uploads/' . $imageName;

		$speaker->save();

		return json_encode($this->getSpeaker($speaker->id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$speaker = $this->getSpeaker($id);
		$speaker->fill($request->input());
		$speaker->save();

		return json_encode($this->getSpeaker($speaker->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$speaker = Speaker::findOrFail($id);
		if ($speaker->img_path){
			File::delete(public_path() . $speaker->img_path);
		}
		$speaker->delete();

		return $speaker;
	}

}
