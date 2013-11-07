<?php

use Illuminate\Database\Migrations\Migration;

class CreateClassTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('classifications', function($table) {
			$table->increments('id');
			$table->string('name');

			//$table->enum('class', array('basic', 'druid', 'hunter', 'mage', 'paladin', 'priest', 'rogue', 'shaman', 'warlock', 'warrior'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('classifications');
	}

}
