<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeakersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('speakers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('year_id');
			$table->string('name');
			$table->string('img_path')->nullable();
			$table->string('session')->nullable();
			$table->string('day');
			$table->string('stime');
			$table->string('ftime');
			$table->string('location');
			$table->string('title')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('speakers');
	}

}
