<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExhibitorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('exhibitors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('year_id');
			$table->string('img_path');
			$table->string('name');
			$table->string('address');
			$table->string('city');
			$table->string('state');
			$table->integer('zip');
			$table->text('description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('exhibitors');
	}

}
