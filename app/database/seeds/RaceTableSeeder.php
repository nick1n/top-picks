<?php

class RaceTableSeeder extends Seeder {

	public function run()
	{
		DB::table('races')->delete();

		Race::create(array('name' => 'Beast'));
		Race::create(array('name' => 'Demon'));
		Race::create(array('name' => 'Dragon'));
		Race::create(array('name' => 'Murloc'));
		Race::create(array('name' => 'Pirate'));
		Race::create(array('name' => 'Totem'));
	}

}
