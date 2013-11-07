<?php

class Card extends Eloquent {



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



	public static function seed() {
		$doc = new DOMDocument();

		for ($display = 2; $display >= 1; --$display) {
			for ($page = 1; $page <= 1; ++$page) {

				// @$doc->loadHTMLFile("http://www.hearthpwn.com/cards?display=$display&page=$page");
				@$doc->loadHTMLFile("cards$display$page.html");

				$elements = $doc->getElementsByTagName('tr');
				foreach ($elements as $element) {

					if ($display == 2) {

						Card::parseVisual($element);

					} else {

						Card::parseListing($element);

					}

				}

			}
		}
	}

	public static function parseListing($element) {
		$columns = $element->getElementsByTagName('td');

		// Card's name
		if (!$columns->item(0)) {
			return;
		}

		$name = trim($columns->item(0)->textContent);

		// Card's pwn_id
		$pwn_id = $columns->item(0)->getElementsByTagName('a')->item(0);

		if ($pwn_id) {
			$pwn_id = $pwn_id->getAttribute('data-id');

		} else {

			echo 'Card pwn_id wasn\'t found';
			var_dump($pwn_id);
			var_dump($name);
			var_dump($element);

			return;
		}

		// Lookup the card
		$card = Card::find(Card::where('pwn_id', $pwn_id)->first()['id']);

		if (!$card) {

			echo 'Card wasn\'t found';
			var_dump($pwn_id);
			var_dump($name);
			var_dump($element);

			return;
		}

		// Card's mana
		$item = 3;
		if (!$columns->item($item)) {
			return;
		}

		$card->mana = trim($columns->item($item)->textContent);

		// Card's attack
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->attack = trim($columns->item($item)->textContent);

		// Card's health
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->health = trim($columns->item($item)->textContent);

		$card->save();
	}

	public static function parseListingOld($element) {
		$item = 0;
		$columns = $element->getElementsByTagName('td');

		$card = new Card;

		// Card's name
		if (!$columns->item($item)) {
			return;
		}

		$card->name = trim($columns->item($item)->textContent);

		// Card's pwn_id
		$pwn_id = $columns->item($item)->getElementsByTagName('a')->item(0);

		if ($pwn_id) {
			$card->pwn_id = $pwn_id->getAttribute('data-id');
		}

		// Card's Type
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->type = Type::getId(trim($columns->item($item)->textContent));

		// Card's Class
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->class = Classification::getId(trim($columns->item($item)->textContent));

		// Card's mana
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->mana = trim($columns->item($item)->textContent);

		// Card's attack
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->attack = trim($columns->item($item)->textContent);

		// Card's health
		++$item;
		if (!$columns->item($item)) {
			return;
		}

		$card->health = trim($columns->item($item)->textContent);

		$card->save();
	}

	public static function parseVisual($element) {
		$name = $element->getElementsByTagName('h3');

		$card = new Card;

		// Card's name
		if (!$name->item(0)) {
			return;
		}

		$card->name = trim($name->item(0)->textContent);

		// hearthpwn.com's id
		$result = null;

		if ($element->getElementsByTagName('a')->item(0)) {
			preg_match('/\d+/', $element->getElementsByTagName('a')->item(0)->getAttribute('href'), $result);
		}

		if ($result) {
			$card->pwn_id = $result[0];
		}

		// Card's text
		$text = $element->getElementsByTagName('p');

		if ($text->item(0)) {
			$text = trim($text->item(0)->ownerDocument->saveHTML($text->item(0)));
			$card->text = substr($text, 3, -4);
		}


		$lists = $element->getElementsByTagName('li');

		foreach ($lists as $list) {

			$result = null;

			preg_match('/type|class|rarity|race/i', $list->textContent, $result);

			if ($result) {

				// Card's Type
				if ($result[0] == 'Type') {

					$card->type = Type::getId(trim($list->lastChild->textContent));

				// Card's Class
				} else if ($result[0] == 'Class') {

					$card->class = Classification::getId(trim($list->lastChild->textContent));

				// Card's Rarity
				} else if ($result[0] == 'Rarity') {

					$card->rarity = Rarity::getId(trim($list->lastChild->textContent));

				// Card's Race
				} else if ($result[0] == 'Race') {

					$card->race = Race::getId(trim($list->lastChild->textContent));

				}
			}
		}

		$card->save();
	}

}
