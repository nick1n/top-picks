<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('ClassTableSeeder');
		$this->call('RaceTableSeeder');
		$this->call('RarityTableSeeder');
		$this->call('TypeTableSeeder');
	}

}


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


class TypeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('types')->delete();

		Type::create(array('name' => 'Spell'));
		Type::create(array('name' => 'Weapon'));
		Type::create(array('name' => 'Minion'));
		Type::create(array('name' => 'Hero'));
		Type::create(array('name' => 'Ability'));
		Type::create(array('name' => 'Hero Power'));
	}

}
