<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	public function session() {
		return $this->belongsTo('App\Session');
	}

	public function facilitators() {
		return $this->hasMany('App\Facilitator');
	}

	public function presenters() {
		return $this->hasMany('App\Presenter');
	}

	protected $fillable = ['title', 'sid', 'description', 'survey', 'sponser', 'location'];

	public $timestamps = false;

}
