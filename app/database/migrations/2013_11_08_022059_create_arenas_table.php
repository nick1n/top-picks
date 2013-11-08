<?php

use Illuminate\Database\Migrations\Migration;

class CreateArenasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('arenas', function($table) {
			$table->increments('id');

			$table->integer('player')->unsigned();

			$table->text('info');

			$table->integer('class')->unsigned();

			$table->boolean('real')->default(true);

			$table->integer('wins');
			$table->integer('loses');

			$table->integer('gold');
			$table->integer('dust');
			$table->integer('packs')->default(1);
			$table->integer('card')->unsigned()->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('arenas');
	}

}
