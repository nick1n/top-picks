<?php

class ClassTableSeeder extends Seeder {

	public function run()
	{
		DB::table('classifications')->delete();

		Classification::create(array('name' => 'Druid'));
		Classification::create(array('name' => 'Hunter'));
		Classification::create(array('name' => 'Mage'));
		Classification::create(array('name' => 'Paladin'));
		Classification::create(array('name' => 'Priest'));
		Classification::create(array('name' => 'Rogue'));
		Classification::create(array('name' => 'Shaman'));
		Classification::create(array('name' => 'Warlock'));
		Classification::create(array('name' => 'Warrior'));
		Classification::create(array('name' => 'Dream'));
	}

}
