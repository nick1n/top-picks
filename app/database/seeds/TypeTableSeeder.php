<?php

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
