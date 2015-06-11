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
		User::create(['email'=>'Tmcclure@mymail.mines.edu']);
		User::create(['email'=>'Mccluretaylor11@gmail.com']);

		// RMACRAO ACCOUNTS
		User::create(['email'=>'president@rmacrao.org']);
		User::create(['email'=>'presidentelect@rmacrao.org']);
		User::create(['email'=>'pastpresident@rmacrao.org']);
		User::create(['email'=>'vpwyoming@rmacrao.org']);
		User::create(['email'=>'vpcolorado@rmacrao.org']);
		User::create(['email'=>'vpnewmexico@rmacrao.org']);
		User::create(['email'=>'secretary@rmacrao.org']);
		User::create(['email'=>'historian@rmacrao.org']);
		User::create(['email'=>'exhibitors@rmacrao.org']);
		User::create(['email'=>'treasurer@rmacrao.org']);
		User::create(['email'=>'treasurerelect@rmacrao.org']);
		User::create(['email'=>'communication@rmacrao.org']);
		User::create(['email'=>'web@rmacrao.org']);
		User::create(['email'=>'localarrangements@rmacrao.org']);
		// $this->call('UserTableSeeder');
	}

}
