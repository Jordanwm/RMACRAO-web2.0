<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Presenter extends Model {

	public function event() {
		return $this->belongsTo('App\Event');
	}

	protected $fillable = ['name', 'title'];

	public $timestamps = false;

}
