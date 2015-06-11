<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Year;
use App\Exhibitor;
use App\Staff;

class ExhibitorController extends Controller {

	private function activeYear() {
		return Year::where('active', '=', '1')->first(); // Find active year
	}

	private function getExhibitor($id) {
		$year = $this->activeYear();
		if ($year == null) {
			return null;
		} else {
			$exhibitor = Exhibitor::with('staff')->where('year_id', '=', $year->id)->find($id);
			return $exhibitor;
		}
	}

	public function getExhibitors() 
	{
		$year = $this->activeYear();

		if ($year == null) {
			return json_encode([]);
		} else {
			$exhibitors = Exhibitor::with('staff')->where('year_id', '=', $year->id)->get();
			return json_encode($exhibitors);
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
			$exhibitor = Exhibitor::create($request->input());
			$staff = [];

			$exhibitor->year()->associate($year);
			$exhibitor->save();

			foreach( $request->input('staff') as $staff) {
				$s = Staff::create($staff);
				$s->exhibitor_id = $exhibitor->id;
				$s->save();
			}

			return json_encode($this->getExhibitor($exhibitor->id));
		}
	}

	public function storeImage(Request $request) {
			//Image uploading
			$exhibitor = Exhibitor::find($request->input('id'));
			
			$imageName = 'exhibitor_' . $exhibitor->id . '.' . $request->file('image')->getClientOriginalExtension();

			$request->file('image')->move(base_path() . '/public/images/uploads/', $imageName);
			$exhibitor->img_path = '/images/uploads/' . $imageName;

			$exhibitor->save();
			
			return json_encode($this->getExhibitor($exhibitor->id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$exhibitor = $this->getExhibitor($id);
		$exhibitor->fill($request->input());
		$exhibitor->save();

		// Store the staff information
		$indexes = [];
		foreach($request->input('staff') as $staff) {
			if (isset($staff['id'])) {
				$s = Staff::find($staff['id']);
				$s->fill($staff);
				$s->save();
				array_push($indexes, $s->id);
			} else {
				$s = Staff::create($staff);
				$exhibitor->staff()->save($s);
				array_push($indexes, $s->id);
			}
		}

		// Get updated event with new facilitators.
		$staff = $exhibitor->staff()->get();

		foreach($staff as $key => $s) {
			$found = false;
			foreach($indexes as $i) {
				if ($s->id == $i){
					$found = true;
					break;
				}
			}
			if (!$found) {
				$staff[$key]->delete();
			}
		}

		return json_encode($this->getExhibitor($exhibitor->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$exhibitor = Exhibitor::findOrFail($id);
		$exhibitor->staff()->delete();
		$exhibitor->delete();

		return $exhibitor;
	}

}
