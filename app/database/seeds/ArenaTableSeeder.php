<?php

class ArenaTableSeeder extends Seeder {

	public function run()
	{
		DB::table('arenas')->delete();

		DB::table('arenas')->insert(ArenaTableSeeder::$arenas);
	}

	public static $arenas = array(
		array('id' => '1','player' => '3','info' => '2:06 AM\nhttp://www.twitch.tv/apdrop/c/3191137','class' => '6','real' => '1','wins' => '9','loses' => '0','gold' => '175','dust' => '40','packs' => '2','card0' => NULL,'card1' => NULL),
	);

}
