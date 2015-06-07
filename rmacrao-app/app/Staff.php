<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model {

	public function exhibitor() {
		return $this->belongsTo('App\Exhibitor');
	}

	public $timestamps = false;

}
