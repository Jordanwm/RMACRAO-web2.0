<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;	

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		User::create(['email'=>'sasquatch1994@gmail.com']);
		User::create(['email'=>'jmoser@mymail.mines.edu']);
		// $this->call('UserTableSeeder');
	}

}
