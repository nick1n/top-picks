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
		$array = Input::only('player', 'info', 'class', 'real', 'wins', 'loses', 'gold', 'dust', 'packs', 'card');

		$array['real'] = $array['real'] == 'on';

		$array['card'] = $array['card'] ?: null;

		$arena = Arena::create($array);


		for ($index = 0; $index < 30; ++$index) {

			$pack = new Pack;

			$pack->arena = $arena->id;

			$pack->save();


			for ($card = 0; $card < 3; ++$card) {

				$card_pack = new CardPack;

				$card_pack->pack = $pack->id;

				$card_pack->card = Input::get("card-$index-$card");

				$card_pack->pick = Input::get("pick-$index") == $card;

				$card_pack->save();

			}

		}

		return print_r($array);
	}
}
