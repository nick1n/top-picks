<?php

use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cards', function($table) {
			$table->increments('id');
			// $table->timestamps();

			$table->integer('mana');
			$table->string('name');

			$table->integer('attack');
			$table->integer('health');

			$table->text('text');

			$table->integer('class')->unsigned()->nullable();
			$table->integer('type')->unsigned();
			$table->integer('rarity')->unsigned()->nullable();
			$table->integer('race')->unsigned()->nullable();

			$table->integer('pwn_id')->unsigned();

			//$table->enum('class', array('basic', 'druid', 'hunter', 'mage', 'paladin', 'priest', 'rogue', 'shaman', 'warlock', 'warrior'));
			//$table->enum('type', array('spell', 'weapon', 'minion'));
			//$table->enum('rarity', array('basic', 'common', 'rare', 'epic', 'legendary'));
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
		Schema::drop('cards');
	}

}
