<?php

use Illuminate\Database\Migrations\Migration;

class CreateRacesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('races', function($table) {
			$table->increments('id');
			$table->string('name');

			//$table->enum('race', array('beast', 'demon', 'dragon', 'murloc', 'pirate', 'totem'))->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('races');
	}

}
