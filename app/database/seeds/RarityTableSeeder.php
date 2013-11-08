<?php

class RarityTableSeeder extends Seeder {

	public function run()
	{
		DB::table('rarities')->delete();

		Rarity::create(array('name' => 'Basic'));
		Rarity::create(array('name' => 'Common'));
		Rarity::create(array('name' => 'Rare'));
		Rarity::create(array('name' => 'Epic'));
		Rarity::create(array('name' => 'Legendary'));
	}

}
