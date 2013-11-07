<?php

class Type extends Eloquent {

	public $timestamps = false;

	public function cards() {
		return $this->hasMany('Card', 'type');
	}

	public function id() {
		return $this->id;
	}

	public static function getId($name = null) {
		return self::where('name', $name)->first()['id'];
	}

}
