<?php

class Card extends Eloquent {

	public $timestamps = false;

	// might need this:
	// protected $visible = array('id', 'mana', 'name', 'attack', 'health', 'text');

	protected $appends = array('class', 'type', 'race');


	// Relationships
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

	public function cardPack() {
		return $this->hasMany('CardPack', 'card');
	}

	public function arenas() {
		return $this->hasMany('Arena', 'card');
	}


	// Custom Attributes
	public function getClassAttribute() {
		return Classification::find($this->attributes['class'])['name'] ?: '';
	}

	public function getTypeAttribute() {
		return Type::find($this->attributes['type'])['name'] ?: '';
	}

	public function getRaceAttribute() {
		return Race::find($this->attributes['race'])['name'] ?: '';
	}


	// Custom Functions

	// returns amount of times this card was picked
	public function picked() {
		return CardPack::wherePicked('card', $this->id)->count();
	}
}
