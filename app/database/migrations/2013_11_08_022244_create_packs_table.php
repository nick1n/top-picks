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
			$table->integer('pack')->unsigned();
			$table->integer('card')->unsigned();

			// 1 = card was picked
			// -1 = card wasn't picked
			$table->integer('pick')->default(-1);
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
