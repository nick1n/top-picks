<?php

use Illuminate\Database\Migrations\Migration;

class CreatePacksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packs', function($table) {
			$table->increments('id');

			$table->integer('arena')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('packs');
	}

}
