<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model {

	public function exhibitor() {
		return $this->belongsTo('App\Exhibitor');
	}

	protected $fillable = ['name', 'title'];

	public $timestamps = false;

}
