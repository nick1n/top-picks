<?php

class Classification extends Eloquent {

	public $timestamps = false;


	// Relationships
	public function cards() {
		return $this->hasMany('Card', 'class');
	}


	// Custom Functions
	public static function getId($name = null) {
		return self::where('name', $name)->first()['id'];
	}

	public static function allArray() {
		$classes = Classification::all();
		$array = array();

		foreach ($classes as $class) {
			$array[$class->id] = $class->name;
		}

		return $array;
	}
}
