<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model {

	public function sessions() {
		return $this->hasMany('App\Session');
	}

	public function year() {
		return $this->belongsTo('App\Year');
	}

	protected $fillable = ['day'];

	public $timestamps = false;

}
