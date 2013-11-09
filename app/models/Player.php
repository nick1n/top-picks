<?php

class Player extends Eloquent {

	public $timestamps = false;


	// Custom Functions

	public static function allArray() {
		$players = Player::all();
		$array = array();

		foreach ($players as $player) {
			$array[$player->id] = $player->name;
		}

		return $array;
	}

}
