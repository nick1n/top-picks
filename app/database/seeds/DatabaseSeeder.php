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
		$this->call('PlayerTableSeeder');
		$this->call('CardTableSeeder');
	}

}
