<?php

class Card extends Eloquent {

	public $timestamps = false;


	public function classification() {
		return $this->belongsTo('Classification', 'class');
	}

	public function type() {
		return $this->belongsTo('Type', 'type');
	}

	public function rarity() {
		return $this->belongsTo('Rarity', 'rarity');
	}

	public function race() {
		return $this->belongsTo('Race', 'race');
	}

	public function packs() {
		return $this->belongsToMany('Pack', 'card_pack', 'pack', 'card');
	}


}
