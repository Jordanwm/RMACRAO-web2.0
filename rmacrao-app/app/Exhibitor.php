<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model {

	public function staff() {
		return $this->hasMany('App\Staff');
	}

	public function year() {
		return $this->belongsTo('App\Year');
	}

	public $timestamps = false;

}
