<?php

class PlayerTableSeeder extends Seeder {

	public function run()
	{
		DB::table('players')->delete();

		Player::create(array('name' => 'Trump'));
		Player::create(array('name' => 'Kripp'));
		Player::create(array('name' => 'apDrop'));
	}

}
