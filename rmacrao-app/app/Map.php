<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model {

	public function year(){
		return $this->belongsTo('App\Year');
	}

	public $fillable = ['name', 'img_path'];

	public $timestamps = false;

}
