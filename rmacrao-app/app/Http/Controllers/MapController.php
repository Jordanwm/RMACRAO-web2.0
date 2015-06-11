<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Year;
use App\Map;

use File;

class MapController extends Controller {

	private function activeYear() {
		return Year::where('active', '=', '1')->first();
	}

	private function getMap($id) {
		$year = $this->activeYear();
		if ($year == null) {
			return null;
		} else {
			$map = Map::where('year_id', '=', $year->id)->find($id);
			return $map;
		}
	}

	public function getMaps() 
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$maps = Map::where('year_id', '=', $year->id)->get();
			return json_encode($maps);
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
			$map = Map::create($request->input());

			Log::Info($request->file('image'));

			$imageName = 'map_' . $map->id . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

			$request->file('image')->move(base_path() . '/public/images/uploads/', $imageName);

			$map->img_path = '/images/uploads/' . $imageName;

			$map->year()->associate($year);
			$map->save();

			return json_encode($this->getMap($map->id));
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$map = $this->getMap($id);
		
		$map->fill($request->input());

		if ($request->file('image')){
			if ($map->img_path) {
				File::delete(public_path() . $map->img_path);
			}

			$imageName = 'map_' . $map->id . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
			$request->file('image')->move(base_path() . '/public/images/uploads/', $imageName);
			$map->img_path = '/images/uploads/' . $imageName;
		}

		$map->save();

		return json_encode($this->getMap($map->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$map = Map::find($id);
		if ($map->img_path) {
			File::delete(public_path() . $map->img_path);
		}
		$map->delete();

		return $map;
	}

}
