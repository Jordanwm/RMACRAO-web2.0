<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model {

	public function year() {
		return $this->belongsTo('App\Year');
	}

	public $fillable = ['name', 'session', 'day', 'stime', 'ftime', 'location', 'title', 'img_path'];
	
	public $timestamps = false;

}
