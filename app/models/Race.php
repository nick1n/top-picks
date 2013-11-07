<?php

class Race extends Eloquent {

	public $timestamps = false;

	public function cards() {
		return $this->hasMany('Card', 'race');
	}

	public static function getId($name = null) {
		return self::where('name', $name)->first()['id'];
	}

}
