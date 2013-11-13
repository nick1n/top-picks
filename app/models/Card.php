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
		return $this->hasMany('Pack', 'card');
	}

	public function arenas() {
		return $this->belongsToMany('Arena', 'packs', 'arena', 'card');
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
		return Pack::wherePicked('card', $this->id)->count();
	}

	public static function getCards($name, $class = null) {
		$query = self::where('name', 'like', "%$name%");

		// if a class was passed in filter by it or base cards (null)
		if ($class) {

			$query = $query->where(function($query) use($class) {

				$query
					->where('class', $class)
					->orWhereNull('class');

			});

		}

		return $query->get();
	}

	public static function getMana() {
		return DB::table('cards')
			->select(DB::raw('(attack + health) / mana AS efficiency, cards.*'))
			->where('type', 3)
			->orderBy(DB::raw('(attack + health) / mana DESC, mana DESC, attack + health DESC, attack'), 'DESC')
			->get();

		// Raw SQL:
		// SELECT (attack + health) / mana AS efficiency, cards.*
		// FROM cards
		// WHERE type = 3
		// ORDER BY (attack + health) / mana DESC, mana DESC, attack + health DESC, attack DESC
	}
}
