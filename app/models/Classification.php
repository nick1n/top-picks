<?php

class Classification extends Eloquent {

	public $timestamps = false;

	public function cards() {
		return $this->hasMany('Card', 'class');
	}

	public static function getId($name = null) {
		return self::where('name', $name)->first()['id'];
	}

}
