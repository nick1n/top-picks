<?php

class Pack extends Eloquent {

	public $timestamps = false;


	// Relationships
	public function cards() {
		return $this->belongsTo('Card', 'card');
	}

	public function arena() {
		return $this->belongsTo('Arena', 'arena');
	}


	// Custom Functions

	// returns the picked card id
	public function pickId() {
		return self::wherePicked('pack', $this->pack)->pluck('card');
	}

	// returns the picked card model
	public function pick() {
		return Card::find($this->pickId());
	}


	// Query Where Clauses
	public static function wherePicked($where, $id) {
		return self::where($where, $id)->where('pick', 1);
	}

}
