<?php

class CardTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cards')->delete();

		Card::seed();
	}

}
