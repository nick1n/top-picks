<?php

class Arena extends Eloquent {

	public $timestamps = false;

	protected $fillable = array('player', 'info', 'class', 'real', 'wins', 'loses', 'gold', 'dust', 'packs', 'card');


	// Relationships
	public function packs() {
		return $this->hasMany('Pack', 'arena');
	}

	public function card() {
		return $this->belongsTo('Card', 'card');
	}


	// Custom Functions
	public static function store() {
		$array = Input::only('player', 'info', 'class', 'real', 'wins', 'loses', 'gold', 'dust', 'packs', 'card0', 'card1');

		$array['real'] = $array['real'] == 'on' ? 1 : 0;

		$array['card0'] = $array['card0'] ?: null;

		$array['card1'] = $array['card1'] ?: null;

		$arena = Arena::create($array);


		for ($index = 0; $index < 30; ++$index) {

			for ($card = 0; $card < 3; ++$card) {

				$pack = new Pack;

				$pack->arena = $arena->id;

				$pack->pack = $index;

				$pack->card = Input::get("card-$index-$card");

				if (Input::get("pick-$index") == $card) {
					$pack->pick = 1;
				}

				$pack->save();

			}

		}

		return print_r($array);
	}
}
