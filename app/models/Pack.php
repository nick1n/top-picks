<?php

class Pack extends Eloquent {

	public $timestamps = false;


	// Relationships
	public function cards() {
		return $this->belongsToMany('Card', 'card_pack', 'card', 'pack');
	}

	public function cardPack() {
		return $this->hasMany('CardPack', 'pack');
	}

	public function arena() {
		return $this->belongsTo('Arena', 'arena');
	}


	// Custom Functions

	// returns the picked card id
	public function pickId() {
		// return CardPack::wherePicked('pack', $this->id)->first()['id'];
		return CardPack::wherePicked('pack', $this->id)->pluck('id');
	}

	// returns the picked card model
	public function pick() {
		return Card::find($this->pickId());
	}

}
