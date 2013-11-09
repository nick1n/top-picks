<?php

class CardPack extends Eloquent {

	public $timestamps = false;

	protected $table = 'card_pack';


	// Relationships
	public function card() {
		return $this->belongsTo('Card', 'card');
	}

	public function pack() {
		return $this->belongsTo('Pack', 'pack');
	}


	// Where Clauses
	// public static function card($card) {
		// return self::where('card', $card);
	// }

	public static function wherePicked($where, $id) {
		return self::where($where, $id)->where('pick', true);
	}
}
