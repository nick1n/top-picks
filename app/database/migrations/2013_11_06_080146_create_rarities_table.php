<?php

use Illuminate\Database\Migrations\Migration;

class CreateRaritiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rarities', function($table) {
			$table->increments('id');
			$table->string('name');

			//$table->enum('rarity', array('basic', 'common', 'rare', 'epic', 'legendary'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rarities');
	}

}
