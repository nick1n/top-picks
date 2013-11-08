<?php

use Illuminate\Database\Migrations\Migration;

class CreateCardPackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('card_pack', function($table) {
			$table->integer('pack')->unsigned();
			$table->integer('card')->unsigned();
			$table->boolean('pick')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('card_pack');
	}

}
