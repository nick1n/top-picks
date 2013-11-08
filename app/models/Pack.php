<?php

class Pack extends Eloquent {

	public $timestamps = false;

	public function cards() {
		return $this->belongsToMany('Card', 'card_pack', 'card', 'pack');
	}

}
