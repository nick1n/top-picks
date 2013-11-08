<?php

class CardTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cards')->delete();

		CardTableSeeder::seed();
	}


	public static function seed() {
		$doc = new DOMDocument();

		for ($display = 2; $display >= 1; --$display) {
			for ($page = 1; $page <= 6; ++$page) {

				// @$doc->loadHTMLFile("http://www.hearthpwn.com/cards?display=$display&page=$page");
				@$doc->loadHTMLFile("hearthpwn\\cards$display$page.html");

				$elements = $doc->getElementsByTagName('tr');
				foreach ($elements as $element) {

					if ($display == 2) {
						self::parseVisual($element);
					} else {
						self::parseListing($element);
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
		}

		if (!$pwn_id) {
			return;
		}

		// Lookup the card
		$card = Card::find(Card::where('pwn_id', $pwn_id)->first()['id']);

		if (!$card) {
			//echo "$pwn_id - $name card wasn't found";
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
		$text = $element->getElementsByTagName('p')->item(0);

		if ($text && $text->parentNode->getAttribute('class') != 'card-flavor-listing-text') {
			$text = trim($text->ownerDocument->saveHTML($text));
			$card->text = substr($text, 3, -4);
		}


		$lists = $element->getElementsByTagName('li');

		foreach ($lists as $list) {

			$result = null;

			preg_match('/^type|^class|^rarity|^race/i', $list->textContent, $result);

			if ($result) {

				$name = trim($list->lastChild->textContent);

				// Card's Type
				if ($result[0] == 'Type') {

					$card->type = Type::getId($name);

				// Card's Class
				} else if ($result[0] == 'Class') {

					$card->class = Classification::getId($name);

				// Card's Rarity
				} else if ($result[0] == 'Rarity') {

					$card->rarity = Rarity::getId($name);

				// Card's Race
				} else if ($result[0] == 'Race') {

					$card->race = Race::getId($name);

				}
			}
		}

		$card->save();
	}

}
