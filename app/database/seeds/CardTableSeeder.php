<?php

class CardTableSeeder extends Seeder {

	public function run()
	{
		DB::table('cards')->delete();

		DB::table('cards')->insert(CardTableSeeder::$cards);

		// CardTableSeeder::parseCards();
	}


	public static function parseCards() {
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

	public static $cards = array(
		array('id' => '1','mana' => '8','name' => 'Al\'Akir the Windlord','attack' => '3','health' => '5','text' => '<b>Windfury, Charge, Divine Shield, Taunt</b>','class' => '7','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '335'),
		array('id' => '2','mana' => '9','name' => 'Alexstrasza','attack' => '8','health' => '8','text' => '<b>Battlecry:</b> Set a hero\'s remaining Health to 15.','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '303'),
		array('id' => '3','mana' => '7','name' => 'Archmage Antonidas','attack' => '5','health' => '7','text' => 'Whenever you cast a spell, put a \'Fireball\' spell into your hand.','class' => '3','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '220'),
		array('id' => '4','mana' => '5','name' => 'Ashbringer','attack' => '5','health' => '3','text' => '','class' => '4','type' => '2','rarity' => '5','race' => NULL,'pwn_id' => '53'),
		array('id' => '5','mana' => '4','name' => 'Baine Bloodhoof','attack' => '4','health' => '5','text' => '','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '359'),
		array('id' => '6','mana' => '7','name' => 'Baron Geddon','attack' => '7','health' => '5','text' => 'At the end of your turn, deal 2 damage to ALL other characters.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '539'),
		array('id' => '7','mana' => '2','name' => 'Bloodmage Thalnos','attack' => '1','health' => '1','text' => '<b>Spell Damage +1</b>. <b>Deathrattle:</b> Draw a card.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '525'),
		array('id' => '8','mana' => '6','name' => 'Cairne Bloodhoof','attack' => '4','health' => '5','text' => '<b>Deathrattle:</b> Summon a 4/5 Baine Bloodhoof.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '498'),
		array('id' => '9','mana' => '5','name' => 'Captain Greenskin','attack' => '5','health' => '4','text' => '<b>Battlecry:</b> Give your weapon +1/+1.','class' => NULL,'type' => '3','rarity' => '5','race' => '5','pwn_id' => '267'),
		array('id' => '10','mana' => '9','name' => 'Cenarius','attack' => '5','health' => '8','text' => '<b>Choose One</b> - Give your other minions +2/+2; or Summon two 2/2 Treants with <b>Taunt</b>.','class' => '1','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '605'),
		array('id' => '11','mana' => '10','name' => 'Deathwing','attack' => '12','health' => '12','text' => '<b>Battlecry:</b> Destroy all other minions and discard your hand.','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '474'),
		array('id' => '12','mana' => '5','name' => 'DEBUG','attack' => '5','health' => '5','text' => 'Debug text','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '682'),
		array('id' => '13','mana' => '3','name' => 'Edwin VanCleef','attack' => '2','health' => '2','text' => '<b>Combo:</b> Gain +2/+2 for each card played earlier this turn.','class' => '6','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '3'),
		array('id' => '14','mana' => '2','name' => 'Finkle Einhorn','attack' => '3','health' => '3','text' => '','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '541'),
		array('id' => '15','mana' => '6','name' => 'Gelbin Mekkatorque','attack' => '6','health' => '6','text' => '<b>Battlecry:</b> Summon an AWESOME invention.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '251'),
		array('id' => '16','mana' => '8','name' => 'Grommash Hellscream','attack' => '4','health' => '9','text' => '<b>Charge</b>.  <b>Enrage:</b> +6 Attack','class' => '9','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '643'),
		array('id' => '17','mana' => '8','name' => 'Gruul','attack' => '7','health' => '7','text' => 'At the end of each turn, gain +1/+1 .','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '18'),
		array('id' => '18','mana' => '5','name' => 'Harrison Jones','attack' => '5','health' => '4','text' => '<b>Battlecry:</b> Destroy your opponent\'s weapon and draw cards equal to its Durability.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '602'),
		array('id' => '19','mana' => '6','name' => 'Hogger','attack' => '4','health' => '4','text' => 'At the end of your turn, summon a 2/2 Gnoll with <b>Taunt</b>.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '39'),
		array('id' => '20','mana' => '6','name' => 'Illidan Stormrage','attack' => '7','health' => '5','text' => 'Whenever you play a card, summon a 2/1 Flame of Azzinoth.','class' => NULL,'type' => '3','rarity' => '5','race' => '2','pwn_id' => '203'),
		array('id' => '21','mana' => '9','name' => 'King Krush','attack' => '8','health' => '8','text' => '<b>Charge</b>','class' => '2','type' => '3','rarity' => '5','race' => '1','pwn_id' => '194'),
		array('id' => '22','mana' => '3','name' => 'King Mukla','attack' => '5','health' => '5','text' => '<b>Battlecry:</b> Give your opponent 2 Bananas.','class' => NULL,'type' => '3','rarity' => '5','race' => '1','pwn_id' => '373'),
		array('id' => '23','mana' => '4','name' => 'Leeroy Jenkins','attack' => '6','health' => '2','text' => '<b>Charge</b>. <b>Battlecry:</b> Summon two 1/1 Whelps for your opponent.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '674'),
		array('id' => '24','mana' => '9','name' => 'Lord Jaraxxus','attack' => '3','health' => '15','text' => '<b>Battlecry:</b> Destroy your hero and replace him with Lord Jaraxxus.','class' => '8','type' => '3','rarity' => '5','race' => '2','pwn_id' => '482'),
		array('id' => '25','mana' => '0','name' => 'Lord Jaraxxus','attack' => '0','health' => '15','text' => '','class' => '8','type' => '4','rarity' => '5','race' => '2','pwn_id' => '406'),
		array('id' => '26','mana' => '2','name' => 'Lorewalker Cho','attack' => '0','health' => '4','text' => 'Whenever a player casts a spell, put a copy into the other playerâs hand.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '456'),
		array('id' => '27','mana' => '9','name' => 'Malygos','attack' => '4','health' => '12','text' => '<b>Spell Damage +5</b>','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '241'),
		array('id' => '28','mana' => '2','name' => 'Millhouse Manastorm','attack' => '4','health' => '4','text' => '<b>Battlecry:</b> Enemy spells cost (0) next turn.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '339'),
		array('id' => '29','mana' => '2','name' => 'Nat Pagle','attack' => '0','health' => '4','text' => 'At the end of your turn, you have a 50% chance to draw a card.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '19'),
		array('id' => '30','mana' => '9','name' => 'Nozdormu','attack' => '8','health' => '8','text' => 'Players only have 15 seconds to take their turns.','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '285'),
		array('id' => '31','mana' => '4','name' => 'Old Murk-Eye','attack' => '2','health' => '4','text' => '<b>Charge</b>. Has +1 Attack for each other Murloc on the battlefield.','class' => NULL,'type' => '3','rarity' => '5','race' => '4','pwn_id' => '217'),
		array('id' => '32','mana' => '9','name' => 'Onyxia','attack' => '8','health' => '8','text' => '<b>Battlecry:</b> Summon 1/1 Whelps until your side of the battlefield is full.','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '432'),
		array('id' => '33','mana' => '7','name' => 'Prophet Velen','attack' => '7','health' => '7','text' => 'Double the damage and healing of your spells and Hero Power.','class' => '5','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '228'),
		array('id' => '34','mana' => '8','name' => 'Ragnaros the Firelord','attack' => '8','health' => '8','text' => 'Can\'t Attack.  At the end of your turn, deal 8 damage to a random enemy.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '503'),
		array('id' => '35','mana' => '5','name' => 'Sylvanas Windrunner','attack' => '5','health' => '5','text' => '<b>Deathrattle:</b> Take control of a random enemy minion.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '33'),
		array('id' => '36','mana' => '6','name' => 'The Beast','attack' => '9','health' => '7','text' => '<b>Deathrattle:</b> Summon a 3/3 Finkle Einhorn for your opponent.','class' => NULL,'type' => '3','rarity' => '5','race' => '1','pwn_id' => '179'),
		array('id' => '37','mana' => '6','name' => 'The Black Knight','attack' => '4','health' => '5','text' => '<b>Battlecry:</b> Destroy an enemy minion with <b>Taunt</b>.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '396'),
		array('id' => '38','mana' => '3','name' => 'Tinkmaster Overspark','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Transform a minion into a 5/5 Devilsaur or a 1/1 Squirrel at random.','class' => NULL,'type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '245'),
		array('id' => '39','mana' => '8','name' => 'Tirion Fordring','attack' => '6','health' => '6','text' => '<b>Divine Shield</b>. <b>Taunt</b>. <b>Deathrattle:</b> Equip a 5/3 Ashbringer.','class' => '4','type' => '3','rarity' => '5','race' => NULL,'pwn_id' => '391'),
		array('id' => '40','mana' => '9','name' => 'Ysera','attack' => '4','health' => '12','text' => 'At the end of your turn, draw a Dream Card.','class' => NULL,'type' => '3','rarity' => '5','race' => '3','pwn_id' => '495'),
		array('id' => '41','mana' => '1','name' => 'Adrenaline Rush','attack' => '0','health' => '0','text' => 'Draw a card. <b>Combo:</b> Draw 2 cards instead.','class' => '6','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '180'),
		array('id' => '42','mana' => '7','name' => 'Ancient of Lore','attack' => '5','health' => '5','text' => '<b>Choose One -</b> Draw 2 cards; or Restore 5 Health.','class' => '1','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '34'),
		array('id' => '43','mana' => '7','name' => 'Ancient of War','attack' => '5','health' => '5','text' => '<b>Choose One</b> - <b>Taunt</b> and +5 Health; or +5 Attack.','class' => '1','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '242'),
		array('id' => '44','mana' => '6','name' => 'Avenging Wrath','attack' => '0','health' => '0','text' => 'Deal 8 damage randomly split among enemy characters.','class' => '4','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '142'),
		array('id' => '45','mana' => '5','name' => 'Bane of Doom','attack' => '0','health' => '0','text' => 'Deal 2 damage to a character.  If that kills it, summon a random Demon.','class' => '8','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '670'),
		array('id' => '46','mana' => '1','name' => 'Bestial Wrath','attack' => '0','health' => '0','text' => 'Give a Beast +2 Attack and <b>Immune</b> this turn.','class' => '2','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '304'),
		array('id' => '47','mana' => '3','name' => 'Big Game Hunter','attack' => '4','health' => '2','text' => '<b>Battlecry:</b> Destroy a minion with an Attack of 7 or more.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '73'),
		array('id' => '48','mana' => '3','name' => 'Blood Knight','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> All minions lose <b>Divine Shield</b>. Gain +3/+3 for each Shield lost.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '75'),
		array('id' => '49','mana' => '5','name' => 'Brawl','attack' => '0','health' => '0','text' => 'Destroy all minions except one.  (chosen randomly)','class' => '9','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '297'),
		array('id' => '50','mana' => '6','name' => 'Cabal Shadow Priest','attack' => '4','health' => '5','text' => '<b>Battlecry:</b> Take control of an enemy minion that has 2 or less Attack.','class' => '5','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '147'),
		array('id' => '51','mana' => '2','name' => 'Captain\'s Parrot','attack' => '1','health' => '1','text' => '<b>Battlecry:</b> Put a random Pirate from your deck into your hand.','class' => NULL,'type' => '3','rarity' => '4','race' => '1','pwn_id' => '559'),
		array('id' => '52','mana' => '5','name' => 'Doomhammer','attack' => '2','health' => '8','text' => '<b>Windfury, Overload:</b> (2)','class' => '7','type' => '2','rarity' => '4','race' => NULL,'pwn_id' => '172'),
		array('id' => '53','mana' => '2','name' => 'Doomsayer','attack' => '0','health' => '7','text' => 'At the start of your turn, destroy ALL minions.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '467'),
		array('id' => '54','mana' => '5','name' => 'Earth Elemental','attack' => '7','health' => '8','text' => '<b>Taunt</b>. <b>Overload:</b> (3)','class' => '7','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '124'),
		array('id' => '55','mana' => '5','name' => 'Faceless Manipulator','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Choose a minion and become a copy of it.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '450'),
		array('id' => '56','mana' => '3','name' => 'Far Sight','attack' => '0','health' => '0','text' => 'Draw a card. That card costs (3) less.','class' => '7','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '107'),
		array('id' => '57','mana' => '6','name' => 'Force of Nature','attack' => '0','health' => '0','text' => 'Summon three 2/2 Treants with <b>Charge</b> that die at the end of the turn.','class' => '1','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '237'),
		array('id' => '58','mana' => '7','name' => 'Gladiator\'s Longbow','attack' => '5','health' => '2','text' => 'Your hero is <b>Immune</b> while attacking.','class' => '2','type' => '2','rarity' => '4','race' => NULL,'pwn_id' => '278'),
		array('id' => '59','mana' => '7','name' => 'Gorehowl','attack' => '7','health' => '1','text' => 'Attacking a minion costs 1 Attack instead of 1 Durability.','class' => '9','type' => '2','rarity' => '4','race' => NULL,'pwn_id' => '96'),
		array('id' => '60','mana' => '1','name' => 'Hungry Crab','attack' => '1','health' => '2','text' => '<b>Battlecry:</b> Destroy a Murloc and gain +2/+2.','class' => NULL,'type' => '3','rarity' => '4','race' => '1','pwn_id' => '660'),
		array('id' => '61','mana' => '3','name' => 'Ice Block','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your hero takes fatal damage, prevent it and become <b>Immune</b> this turn.','class' => '3','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '28'),
		array('id' => '62','mana' => '6','name' => 'Kidnapper','attack' => '5','health' => '3','text' => '<b>Combo:</b> Return a minion to its owner\'s hand.','class' => '6','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '562'),
		array('id' => '63','mana' => '8','name' => 'Lay on Hands','attack' => '0','health' => '0','text' => 'Restore #8 Health. Draw 3 cards.','class' => '4','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '506'),
		array('id' => '64','mana' => '4','name' => 'Mindgames','attack' => '0','health' => '0','text' => 'Put a copy of a random minion from your opponent\'s deck into the battlefield.','class' => '5','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '301'),
		array('id' => '65','mana' => '20','name' => 'Molten Giant','attack' => '8','health' => '8','text' => 'Costs (1) less for each damage your hero has taken.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '94'),
		array('id' => '66','mana' => '12','name' => 'Mountain Giant','attack' => '8','health' => '8','text' => 'Costs (1) less for each other card in your hand.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '264'),
		array('id' => '67','mana' => '3','name' => 'Murloc Warleader','attack' => '3','health' => '3','text' => 'ALL other Murlocs have +2/+1.','class' => NULL,'type' => '3','rarity' => '4','race' => '4','pwn_id' => '222'),
		array('id' => '68','mana' => '2','name' => 'Patient Assassin','attack' => '1','health' => '1','text' => '<b>Stealth</b>. Destroy any minion damaged by this minion.','class' => '6','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '14'),
		array('id' => '69','mana' => '4','name' => 'Pit Lord','attack' => '5','health' => '6','text' => '<b>Battlecry:</b> Deal 5 damage to your hero.','class' => '8','type' => '3','rarity' => '4','race' => '2','pwn_id' => '402'),
		array('id' => '70','mana' => '9','name' => 'Placeholder Card','attack' => '6','health' => '8','text' => 'Battlecry: Someone remembers to publish this card.','class' => '3','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '102'),
		array('id' => '71','mana' => '0','name' => 'Preparation','attack' => '0','health' => '0','text' => 'The next spell you cast this turn costs (3) less.','class' => '6','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '364'),
		array('id' => '72','mana' => '8','name' => 'Pyroblast','attack' => '0','health' => '0','text' => 'Deal 10 damage.','class' => '3','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '496'),
		array('id' => '73','mana' => '10','name' => 'Sea Giant','attack' => '8','health' => '8','text' => 'Costs (1) less for each other minion on the battlefield.','class' => NULL,'type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '614'),
		array('id' => '74','mana' => '0','name' => 'Shadow of Nothing','attack' => '0','health' => '1','text' => 'Mindgames whiffed! Your opponent had no minions!','class' => '5','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '582'),
		array('id' => '75','mana' => '3','name' => 'Shadowform','attack' => '0','health' => '0','text' => 'Your Hero Power becomes \'Deal 2 damage\'. If already in Shadowform: 3 damage.','class' => '5','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '421'),
		array('id' => '76','mana' => '1','name' => 'Shield Slam','attack' => '0','health' => '0','text' => 'Deal 1 damage to a minion for each Armor you have.','class' => '9','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '50'),
		array('id' => '77','mana' => '2','name' => 'Snake Trap','attack' => '0','health' => '0','text' => '<b>Secret:</b> When one of your minions is attacked, summon three 1/1 Snakes.','class' => '2','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '210'),
		array('id' => '78','mana' => '3','name' => 'Southsea Captain','attack' => '3','health' => '3','text' => 'Your other Pirates have +1/+1.','class' => NULL,'type' => '3','rarity' => '4','race' => '5','pwn_id' => '324'),
		array('id' => '79','mana' => '3','name' => 'Spellbender','attack' => '0','health' => '0','text' => '<b>Secret:</b> When an enemy casts a spell on a minion, summon a 1/3 as the new target.','class' => '3','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '309'),
		array('id' => '80','mana' => '0','name' => 'Spellbender','attack' => '1','health' => '3','text' => '<b></b>','class' => '3','type' => '3','rarity' => '4','race' => NULL,'pwn_id' => '645'),
		array('id' => '81','mana' => '3','name' => 'Sword of Justice','attack' => '1','health' => '5','text' => 'Whenever you summon a minion, give it +1/+1 and this loses 1 Durability.','class' => '4','type' => '2','rarity' => '4','race' => NULL,'pwn_id' => '567'),
		array('id' => '82','mana' => '8','name' => 'Twisting Nether','attack' => '0','health' => '0','text' => 'Destroy all minions.','class' => '8','type' => '5','rarity' => '4','race' => NULL,'pwn_id' => '398'),
		array('id' => '83','mana' => '5','name' => 'Abomination','attack' => '4','health' => '4','text' => '<b>Taunt</b>. <b>Deathrattle:</b> Deal 2 damage to ALL characters.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '597'),
		array('id' => '84','mana' => '3','name' => 'Alarm-o-Bot','attack' => '0','health' => '3','text' => 'At the start of your turn, swap this minion with a random one in your hand.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '425'),
		array('id' => '85','mana' => '3','name' => 'Aldor Peacekeeper','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Change an enemy minion\'s Attack to 1.','class' => '4','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '23'),
		array('id' => '86','mana' => '2','name' => 'Ancestral Spirit','attack' => '0','health' => '0','text' => 'Choose a minion. When that minion is destroyed, return it to the battlefield.','class' => '7','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '526'),
		array('id' => '87','mana' => '4','name' => 'Ancient Mage','attack' => '2','health' => '5','text' => '<b>Battlecry:</b> Give adjacent minions <b>Spell Damage +1</b>.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '176'),
		array('id' => '88','mana' => '2','name' => 'Ancient Watcher','attack' => '4','health' => '5','text' => 'Can\'t Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '153'),
		array('id' => '89','mana' => '1','name' => 'Angry Chicken','attack' => '1','health' => '1','text' => '<b>Enrage:</b> +5 Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => '1','pwn_id' => '57'),
		array('id' => '90','mana' => '3','name' => 'Arcane Golem','attack' => '4','health' => '2','text' => '<b>Charge</b>. <b>Battlecry:</b> Give your opponent a Mana Crystal.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '97'),
		array('id' => '91','mana' => '6','name' => 'Argent Commander','attack' => '4','health' => '3','text' => '<b>Charge</b>, <b>Divine Shield</b>','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '463'),
		array('id' => '92','mana' => '2','name' => 'Armorsmith','attack' => '1','health' => '4','text' => 'Whenever a friendly minion takes damage, gain 1 Armor.','class' => '9','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '644'),
		array('id' => '93','mana' => '4','name' => 'Auchenai Soulpriest','attack' => '3','health' => '5','text' => 'Your cards and powers that restore Health now deal damage instead.','class' => '5','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '656'),
		array('id' => '94','mana' => '5','name' => 'Azure Drake','attack' => '4','health' => '4','text' => '<b>Spell Damage +1</b>. <b>Battlecry:</b> Draw a card.','class' => NULL,'type' => '3','rarity' => '3','race' => '3','pwn_id' => '280'),
		array('id' => '95','mana' => '4','name' => 'Bite','attack' => '0','health' => '0','text' => 'Give your hero +4 Attack this turn and 4 Armor.','class' => '1','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '266'),
		array('id' => '96','mana' => '2','name' => 'Blade Flurry','attack' => '0','health' => '0','text' => 'Destroy your weapon and deal its damage to all enemies.','class' => '6','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '244'),
		array('id' => '97','mana' => '5','name' => 'Blessed Champion','attack' => '0','health' => '0','text' => 'Double a minion\'s Attack.','class' => '4','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '7'),
		array('id' => '98','mana' => '5','name' => 'Blizzard','attack' => '0','health' => '0','text' => 'Deal 2 damage to all enemy minions and <b>Freeze</b> them.','class' => '3','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '276'),
		array('id' => '99','mana' => '1','name' => 'Bloodsail Corsair','attack' => '1','health' => '2','text' => '<b>Battlecry:</b> Remove 1 Durability from your opponent\'s weapon.','class' => NULL,'type' => '3','rarity' => '3','race' => '5','pwn_id' => '453'),
		array('id' => '100','mana' => '3','name' => 'Coldlight Oracle','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Each player draws 2 cards.','class' => NULL,'type' => '3','rarity' => '3','race' => '4','pwn_id' => '88'),
		array('id' => '101','mana' => '3','name' => 'Coldlight Seer','attack' => '2','health' => '3','text' => '<b>Battlecry:</b> Give ALL other Murlocs +2 Health.','class' => NULL,'type' => '3','rarity' => '3','race' => '4','pwn_id' => '424'),
		array('id' => '102','mana' => '2','name' => 'Commanding Shout','attack' => '0','health' => '0','text' => 'Your minions can\'t be reduced below 1 Health this turn.  Draw a card.','class' => '9','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '166'),
		array('id' => '103','mana' => '3','name' => 'Counterspell','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your opponent casts a spell, <b>Counter</b> it.','class' => '3','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '531'),
		array('id' => '104','mana' => '2','name' => 'Crazed Alchemist','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Swap the Attack and Health of a minion.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '612'),
		array('id' => '105','mana' => '4','name' => 'Defender of Argus','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Give adjacent minions +1/+1 and <b>Taunt</b>.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '542'),
		array('id' => '106','mana' => '3','name' => 'Demolisher','attack' => '1','health' => '4','text' => 'At the start of your turn, deal 2 damage to a random enemy.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '212'),
		array('id' => '107','mana' => '3','name' => 'Divine Favor','attack' => '0','health' => '0','text' => 'Draw cards until you have as many in hand as your opponent.','class' => '4','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '581'),
		array('id' => '108','mana' => '5','name' => 'Doomguard','attack' => '5','health' => '7','text' => '<b>Charge</b>. <b>Battlecry:</b> Discard two random cards.','class' => '8','type' => '3','rarity' => '3','race' => '2','pwn_id' => '507'),
		array('id' => '109','mana' => '3','name' => 'Eaglehorn Bow','attack' => '3','health' => '2','text' => 'Whenever a <b>Secret</b> is revealed, gain +1 Durability.','class' => '2','type' => '2','rarity' => '3','race' => NULL,'pwn_id' => '363'),
		array('id' => '110','mana' => '3','name' => 'Emperor Cobra','attack' => '2','health' => '3','text' => 'Destroy any minion damaged by this minion.','class' => NULL,'type' => '3','rarity' => '3','race' => '1','pwn_id' => '625'),
		array('id' => '111','mana' => '2','name' => 'Equality','attack' => '0','health' => '0','text' => 'Change the Health of ALL minions to 1.','class' => '4','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '383'),
		array('id' => '112','mana' => '4','name' => 'Ethereal Arcanist','attack' => '3','health' => '3','text' => 'If you control a <b>Secret</b> at the end of your turn, gain +2/+2.','class' => '3','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '125'),
		array('id' => '113','mana' => '5','name' => 'Explosive Shot','attack' => '0','health' => '0','text' => 'Deal 5 damage to a minion and 2 damage to adjacent ones.','class' => '2','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '114'),
		array('id' => '114','mana' => '3','name' => 'Felguard','attack' => '3','health' => '5','text' => '<b>Taunt</b>. <b>Battlecry:</b> Destroy one of your Mana Crystals.','class' => '8','type' => '3','rarity' => '3','race' => '2','pwn_id' => '236'),
		array('id' => '115','mana' => '3','name' => 'Feral Spirit','attack' => '0','health' => '0','text' => 'Summon two 2/3 Spirit Wolves with <b>Taunt</b>. <b>Overload:</b> (2)','class' => '7','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '214'),
		array('id' => '116','mana' => '1','name' => 'Flare','attack' => '0','health' => '0','text' => 'All minions lose <b>Stealth</b>. Destroy all enemy <b>Secrets</b>. Draw a card.','class' => '2','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '630'),
		array('id' => '117','mana' => '3','name' => 'Frothing Berserker','attack' => '2','health' => '4','text' => 'Whenever a minion takes damage, gain +1 Attack.','class' => '9','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '69'),
		array('id' => '118','mana' => '5','name' => 'Gadgetzan Auctioneer','attack' => '4','health' => '4','text' => 'Whenever you cast a spell, draw a card.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '131'),
		array('id' => '119','mana' => '3','name' => 'Headcrack','attack' => '0','health' => '0','text' => 'Deal 2 damage to the enemy hero. <b>Combo:</b> Return this to your hand next turn.','class' => '6','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '135'),
		array('id' => '120','mana' => '6','name' => 'Holy Fire','attack' => '0','health' => '0','text' => 'Deal 5 damage.  Restore 5 Health to your hero.','class' => '5','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '457'),
		array('id' => '121','mana' => '5','name' => 'Holy Wrath','attack' => '0','health' => '0','text' => 'Draw a card and deal damage equal to its cost.','class' => '4','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '355'),
		array('id' => '122','mana' => '2','name' => 'Hyena','attack' => '2','health' => '2','text' => '','class' => '2','type' => '3','rarity' => '3','race' => '1','pwn_id' => '689'),
		array('id' => '123','mana' => '1','name' => 'Imp','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '3','race' => '2','pwn_id' => '321'),
		array('id' => '124','mana' => '3','name' => 'Imp Master','attack' => '1','health' => '5','text' => 'At the end of your turn, deal 1 damage to this minion and summon a 1/1 Imp.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '178'),
		array('id' => '125','mana' => '3','name' => 'Injured Blademaster','attack' => '4','health' => '7','text' => '<b>Battlecry:</b> Deal 4 damage to HIMSELF.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '209'),
		array('id' => '126','mana' => '4','name' => 'Keeper of the Grove','attack' => '2','health' => '4','text' => '<b>Choose One</b> - Deal 2 damage; or <b>Silence</b> a minion.','class' => '1','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '459'),
		array('id' => '127','mana' => '3','name' => 'Kirin Tor Mage','attack' => '4','health' => '3','text' => '<b>Battlecry:</b> The next <b>Secret</b> you play this turn costs (0).','class' => '3','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '411'),
		array('id' => '128','mana' => '2','name' => 'Knife Juggler','attack' => '3','health' => '2','text' => 'After you summon a minion, deal 1 damage to a random enemy.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '422'),
		array('id' => '129','mana' => '3','name' => 'Lava Burst','attack' => '0','health' => '0','text' => 'Deal 5 damage. <b>Overload:</b> (2)','class' => '7','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '679'),
		array('id' => '130','mana' => '3','name' => 'Lightning Storm','attack' => '0','health' => '0','text' => 'Deal 2-3 damage to all enemy minions. <b>Overload:</b> (2)','class' => '7','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '676'),
		array('id' => '131','mana' => '1','name' => 'Lightwarden','attack' => '1','health' => '2','text' => 'Whenever a character is healed, gain +2 Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '436'),
		array('id' => '132','mana' => '2','name' => 'Lightwell','attack' => '0','health' => '5','text' => 'At the start of your turn, restore 3 Health to a damaged friendly character.','class' => '5','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '117'),
		array('id' => '133','mana' => '2','name' => 'Mana Addict','attack' => '1','health' => '3','text' => 'Whenever you cast a spell, gain +2 Attack this turn.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '67'),
		array('id' => '134','mana' => '3','name' => 'Mana Tide Totem','attack' => '0','health' => '3','text' => 'At the end of your turn, draw a card.','class' => '7','type' => '3','rarity' => '3','race' => '6','pwn_id' => '613'),
		array('id' => '135','mana' => '2','name' => 'Mana Wraith','attack' => '2','health' => '2','text' => 'ALL minions cost (1) more.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '197'),
		array('id' => '136','mana' => '4','name' => 'Mass Dispel','attack' => '0','health' => '0','text' => '<b>Silence</b> all enemy minions. Draw a card.','class' => '5','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '249'),
		array('id' => '137','mana' => '4','name' => 'Master of Disguise','attack' => '4','health' => '4','text' => '<b>Battlecry:</b> Give a friendly minion <b>Stealth</b>.','class' => '6','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '127'),
		array('id' => '138','mana' => '2','name' => 'Master Swordsmith','attack' => '1','health' => '3','text' => 'At the end of your turn, give another random friendly minion +1 Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '584'),
		array('id' => '139','mana' => '3','name' => 'Mind Control Tech','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> If your opponent has 4 or more minions, take control of one at random.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '368'),
		array('id' => '140','mana' => '2','name' => 'Misdirection','attack' => '0','health' => '0','text' => '<b>Secret:</b> When a character attacks your hero, instead he attacks another random character.','class' => '2','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '447'),
		array('id' => '141','mana' => '4','name' => 'Mortal Strike','attack' => '0','health' => '0','text' => 'Deal 4 damage.  If your hero has 12 or less Health, deal 6 damage instead.','class' => '9','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '345'),
		array('id' => '142','mana' => '1','name' => 'Murloc Tidecaller','attack' => '1','health' => '2','text' => 'Whenever a Murloc is summoned, gain +1 Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => '4','pwn_id' => '420'),
		array('id' => '143','mana' => '5','name' => 'Nourish','attack' => '0','health' => '0','text' => '<b>Choose One</b> - Gain 2 Mana Crystals; or Draw 3 cards.','class' => '1','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '120'),
		array('id' => '144','mana' => '3','name' => 'Perdition\'s Blade','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Deal 1 damage. <b>Combo:</b> Deal 2 instead.','class' => '6','type' => '2','rarity' => '3','race' => NULL,'pwn_id' => '82'),
		array('id' => '145','mana' => '2','name' => 'Pint-Sized Summoner','attack' => '2','health' => '2','text' => 'The first minion you play each turn costs (1) less.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '54'),
		array('id' => '146','mana' => '3','name' => 'Questing Adventurer','attack' => '2','health' => '2','text' => 'Whenever you play a card, gain +1/+1.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '157'),
		array('id' => '147','mana' => '7','name' => 'Ravenholdt Assassin','attack' => '7','health' => '5','text' => '<b>Stealth</b>','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '518'),
		array('id' => '148','mana' => '1','name' => 'Savagery','attack' => '0','health' => '0','text' => 'Deal damage equal to your hero\'s Attack to a minion.','class' => '1','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '148'),
		array('id' => '149','mana' => '6','name' => 'Savannah Highmane','attack' => '6','health' => '5','text' => '<b>Deathrattle:</b> Summon two 2/2 Hyenas.','class' => '2','type' => '3','rarity' => '3','race' => '1','pwn_id' => '8'),
		array('id' => '150','mana' => '1','name' => 'Secretkeeper','attack' => '1','health' => '2','text' => 'Whenever a <b>Secret</b> is played, gain +1/+1.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '483'),
		array('id' => '151','mana' => '4','name' => 'Shadow Madness','attack' => '0','health' => '0','text' => 'Gain control of an enemy minion with 3 or less Attack until end of turn.','class' => '5','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '442'),
		array('id' => '152','mana' => '4','name' => 'Shadowflame','attack' => '0','health' => '0','text' => 'Destroy a friendly minion and deal its Attack damage to all enemy minions.','class' => '8','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '673'),
		array('id' => '153','mana' => '3','name' => 'SI:7 Agent','attack' => '3','health' => '3','text' => '<b>Combo:</b> Deal 2 damage.','class' => '6','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '286'),
		array('id' => '154','mana' => '6','name' => 'Siphon Soul','attack' => '0','health' => '0','text' => 'Destroy a minion. Restore #3 Health to your hero.','class' => '8','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '573'),
		array('id' => '155','mana' => '2','name' => 'Spirit Wolf','attack' => '2','health' => '3','text' => '<b>Taunt</b>','class' => '7','type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '451'),
		array('id' => '156','mana' => '5','name' => 'Stampeding Kodo','attack' => '3','health' => '5','text' => '<b>Battlecry:</b> Destroy a random enemy minion with 2 or less Attack.','class' => NULL,'type' => '3','rarity' => '3','race' => '1','pwn_id' => '389'),
		array('id' => '157','mana' => '5','name' => 'Starfall','attack' => '0','health' => '0','text' => '<b>Choose One -</b> Deal 5 damage to a minion; or 2 damage to all enemy minions.','class' => '1','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '464'),
		array('id' => '158','mana' => '2','name' => 'Sunfury Protector','attack' => '2','health' => '3','text' => '<b>Battlecry:</b> Give adjacent minions <b>Taunt</b>.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '372'),
		array('id' => '159','mana' => '6','name' => 'Sunwalker','attack' => '4','health' => '5','text' => '<b>Taunt</b>. <b>Divine Shield</b>','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '221'),
		array('id' => '160','mana' => '4','name' => 'Twilight Drake','attack' => '4','health' => '1','text' => '<b>Battlecry:</b> Gain +1 Health for each card in your hand.','class' => NULL,'type' => '3','rarity' => '3','race' => '3','pwn_id' => '360'),
		array('id' => '161','mana' => '1','name' => 'Upgrade!','attack' => '0','health' => '0','text' => 'If you have a weapon, give it +1/+1.  Otherwise equip a 1/3 weapon.','class' => '9','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '638'),
		array('id' => '162','mana' => '3','name' => 'Vaporize','attack' => '0','health' => '0','text' => '<b>Secret:</b> When a minion attacks your hero, destroy it.','class' => '3','type' => '5','rarity' => '3','race' => NULL,'pwn_id' => '160'),
		array('id' => '163','mana' => '4','name' => 'Violet Teacher','attack' => '3','health' => '5','text' => 'Whenever you cast a spell, summon a 1/1 Violet Apprentice.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '523'),
		array('id' => '164','mana' => '3','name' => 'Void Terror','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Destroy the minions on either side of this minion and gain their Attack and Health.','class' => '8','type' => '3','rarity' => '3','race' => '2','pwn_id' => '119'),
		array('id' => '165','mana' => '2','name' => 'Wild Pyromancer','attack' => '3','health' => '2','text' => 'After you cast a spell, deal 1 damage to ALL minions.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '25'),
		array('id' => '166','mana' => '1','name' => 'Young Priestess','attack' => '2','health' => '1','text' => 'At the end of your turn, give another random friendly minion +1 Health.','class' => NULL,'type' => '3','rarity' => '3','race' => NULL,'pwn_id' => '123'),
		array('id' => '167','mana' => '0','name' => 'Ancestral Healing','attack' => '0','health' => '0','text' => 'Restore a minion to full Health and give it <b>Taunt</b>.','class' => '7','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '216'),
		array('id' => '168','mana' => '0','name' => 'Anduin Wrynn','attack' => '0','health' => '30','text' => '','class' => '5','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '110'),
		array('id' => '169','mana' => '2','name' => 'Arcane Explosion','attack' => '0','health' => '0','text' => 'Deal 1 damage to all enemy minions.','class' => '3','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '56'),
		array('id' => '170','mana' => '3','name' => 'Arcane Intellect','attack' => '0','health' => '0','text' => 'Draw 2 cards.','class' => '3','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '489'),
		array('id' => '171','mana' => '1','name' => 'Arcane Missiles','attack' => '0','health' => '0','text' => 'Deal 3 damage randomly split among enemy characters.','class' => '3','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '589'),
		array('id' => '172','mana' => '1','name' => 'Arcane Shot','attack' => '0','health' => '0','text' => 'Deal 2 damage.','class' => '2','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '167'),
		array('id' => '173','mana' => '2','name' => 'Armor Up!','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Gain 2 Armor.','class' => '9','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '253'),
		array('id' => '174','mana' => '5','name' => 'Assassinate','attack' => '0','health' => '0','text' => 'Destroy an enemy minion.','class' => '6','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '568'),
		array('id' => '175','mana' => '0','name' => 'Avatar of the Coin','attack' => '1','health' => '1','text' => '<i>You lost the coin flip, but gained a friend.</i>','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '666'),
		array('id' => '176','mana' => '0','name' => 'Backstab','attack' => '0','health' => '0','text' => 'Deal 2 damage to an undamaged minion.','class' => '6','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '471'),
		array('id' => '177','mana' => '1','name' => 'Blessing of Might','attack' => '0','health' => '0','text' => 'Give a minion +3 Attack.','class' => '4','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '394'),
		array('id' => '178','mana' => '2','name' => 'Bloodfen Raptor','attack' => '3','health' => '2','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '576'),
		array('id' => '179','mana' => '6','name' => 'Boulderfist Ogre','attack' => '6','health' => '7','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '60'),
		array('id' => '180','mana' => '0','name' => 'Charge','attack' => '0','health' => '0','text' => 'Give a friendly minion <b>Charge</b>.','class' => '9','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '646'),
		array('id' => '181','mana' => '1','name' => 'Claw','attack' => '0','health' => '0','text' => 'Give your hero +2 Attack this turn and 2 Armor.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '532'),
		array('id' => '182','mana' => '2','name' => 'Dagger Mastery','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Equip a 1/2 Dagger.','class' => '6','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '201'),
		array('id' => '183','mana' => '1','name' => 'Deadly Poison','attack' => '0','health' => '0','text' => 'Give your weapon +2 Attack.','class' => '6','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '87'),
		array('id' => '184','mana' => '3','name' => 'Drain Life','attack' => '0','health' => '0','text' => 'Deal 2 damage. Restore #2 Health to your hero.','class' => '8','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '332'),
		array('id' => '185','mana' => '1','name' => 'Execute','attack' => '0','health' => '0','text' => 'Destroy a damaged enemy minion.','class' => '9','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '227'),
		array('id' => '186','mana' => '2','name' => 'Fiery War Axe','attack' => '3','health' => '2','text' => '','class' => '9','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '632'),
		array('id' => '187','mana' => '4','name' => 'Fireball','attack' => '0','health' => '0','text' => 'Deal 6 damage.','class' => '3','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '522'),
		array('id' => '188','mana' => '2','name' => 'Fireblast','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Deal 1 damage.','class' => '3','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '677'),
		array('id' => '189','mana' => '1','name' => 'Frost Shock','attack' => '0','health' => '0','text' => 'Deal 1 damage to an enemy character and <b>Freeze</b> it.','class' => '7','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '233'),
		array('id' => '190','mana' => '0','name' => 'Garrosh Hellscream','attack' => '0','health' => '30','text' => '','class' => '9','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '635'),
		array('id' => '191','mana' => '0','name' => 'Gul\'dan','attack' => '0','health' => '30','text' => '','class' => '8','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '618'),
		array('id' => '192','mana' => '4','name' => 'Hammer of Wrath','attack' => '0','health' => '0','text' => 'Deal 3 damage.  Draw a card.','class' => '4','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '350'),
		array('id' => '193','mana' => '1','name' => 'Hand of Protection','attack' => '0','health' => '0','text' => 'Give a minion <b>Divine Shield</b>.','class' => '4','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '499'),
		array('id' => '194','mana' => '1','name' => 'Healing Totem','attack' => '0','health' => '2','text' => 'At the end of your turn, restore 1 Health to all friendly minions.','class' => '7','type' => '3','rarity' => NULL,'race' => '6','pwn_id' => '275'),
		array('id' => '195','mana' => '3','name' => 'Healing Touch','attack' => '0','health' => '0','text' => 'Restore #8 Health.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '258'),
		array('id' => '196','mana' => '4','name' => 'Hellfire','attack' => '0','health' => '0','text' => 'Deal 3 damage to ALL characters.','class' => '8','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '122'),
		array('id' => '197','mana' => '2','name' => 'Heroic Strike','attack' => '0','health' => '0','text' => 'Give your hero +4 Attack this turn.','class' => '9','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '1'),
		array('id' => '198','mana' => '3','name' => 'Hex','attack' => '0','health' => '0','text' => 'Transform a minion into a 0/1 Frog with <b>Taunt</b>.','class' => '7','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '270'),
		array('id' => '199','mana' => '2','name' => 'Holy Light','attack' => '0','health' => '0','text' => 'Restore #6 Health.','class' => '4','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '108'),
		array('id' => '200','mana' => '1','name' => 'Holy Smite','attack' => '0','health' => '0','text' => 'Deal 2 damage.','class' => '5','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '409'),
		array('id' => '201','mana' => '4','name' => 'Houndmaster','attack' => '4','health' => '3','text' => '<b>Battlecry:</b> Give a friendly Beast +2/+2 and <b>Taunt</b>.','class' => '2','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '225'),
		array('id' => '202','mana' => '0','name' => 'Innervate','attack' => '0','health' => '0','text' => 'Gain 2 Mana Crystals this turn only.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '548'),
		array('id' => '203','mana' => '0','name' => 'Jaina Proudmoore','attack' => '0','health' => '30','text' => '','class' => '3','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '320'),
		array('id' => '204','mana' => '2','name' => 'Lesser Heal','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Restore 2 Health.','class' => '5','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '126'),
		array('id' => '205','mana' => '2','name' => 'Life Tap','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Draw a card and take 2 damage.','class' => '8','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '20'),
		array('id' => '206','mana' => '1','name' => 'Light\'s Justice','attack' => '1','health' => '4','text' => '','class' => '4','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '250'),
		array('id' => '207','mana' => '3','name' => 'Magma Rager','attack' => '5','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '362'),
		array('id' => '208','mana' => '0','name' => 'Malfurion Stormrage','attack' => '0','health' => '30','text' => '','class' => '1','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '621'),
		array('id' => '209','mana' => '2','name' => 'Mark of the Wild','attack' => '0','health' => '0','text' => 'Give a minion <b>Taunt</b> and +2/+2.<i> (+2 Attack/+2 Health)</i>','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '480'),
		array('id' => '210','mana' => '2','name' => 'Mind Blast','attack' => '0','health' => '0','text' => 'Deal 5 damage to the enemy hero.','class' => '5','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '415'),
		array('id' => '211','mana' => '4','name' => 'Multi-Shot','attack' => '0','health' => '0','text' => 'Deal 3 damage to two random enemy minions.','class' => '2','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '407'),
		array('id' => '212','mana' => '1','name' => 'Murloc Raider','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => '4','pwn_id' => '55'),
		array('id' => '213','mana' => '5','name' => 'Nightblade','attack' => '4','health' => '4','text' => '<b>Battlecry: </b>Deal 3 damage to the enemy hero.','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '184'),
		array('id' => '214','mana' => '1','name' => 'Northshire Cleric','attack' => '1','health' => '3','text' => 'Whenever a minion is healed, draw a card.','class' => '5','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '600'),
		array('id' => '215','mana' => '2','name' => 'Novice Engineer','attack' => '1','health' => '2','text' => '<b>Battlecry:</b> Draw a card.','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '435'),
		array('id' => '216','mana' => '4','name' => 'Oasis Snapjaw','attack' => '2','health' => '7','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '15'),
		array('id' => '217','mana' => '4','name' => 'Polymorph','attack' => '0','health' => '0','text' => 'Transform a minion into a 1/1 Sheep.','class' => '3','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '595'),
		array('id' => '218','mana' => '1','name' => 'Power Word: Shield','attack' => '0','health' => '0','text' => 'Give a minion +2 Health.<br>Draw a card.','class' => '5','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '431'),
		array('id' => '219','mana' => '3','name' => 'Raid Leader','attack' => '2','health' => '2','text' => 'Your other minions have +1 Attack.','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '502'),
		array('id' => '220','mana' => '6','name' => 'Reckless Rocketeer','attack' => '5','health' => '2','text' => '<b>Charge</b>','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '560'),
		array('id' => '221','mana' => '2','name' => 'Reinforce','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Summon a 1/1 Silver Hand Recruit.','class' => '4','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '248'),
		array('id' => '222','mana' => '0','name' => 'Rexxar','attack' => '0','health' => '30','text' => '','class' => '2','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '484'),
		array('id' => '223','mana' => '2','name' => 'River Crocolisk','attack' => '2','health' => '3','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '535'),
		array('id' => '224','mana' => '1','name' => 'Rockbiter Weapon','attack' => '0','health' => '0','text' => 'Give a friendly character +3 Attack this turn.','class' => '7','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '491'),
		array('id' => '225','mana' => '2','name' => 'Sap','attack' => '0','health' => '0','text' => 'Return an enemy minion to its owner\'s hand.','class' => '6','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '385'),
		array('id' => '226','mana' => '1','name' => 'Searing Totem','attack' => '1','health' => '1','text' => '','class' => '7','type' => '3','rarity' => NULL,'race' => '6','pwn_id' => '98'),
		array('id' => '227','mana' => '4','name' => 'Sen\'jin Shieldmasta','attack' => '3','health' => '5','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '326'),
		array('id' => '228','mana' => '3','name' => 'Shadow Bolt','attack' => '0','health' => '0','text' => 'Deal 4 damage to a minion.','class' => '8','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '647'),
		array('id' => '229','mana' => '2','name' => 'Shadow Word: Pain','attack' => '0','health' => '0','text' => 'Destroy a minion with 3 or less Attack.','class' => '5','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '315'),
		array('id' => '230','mana' => '2','name' => 'Shapeshift','attack' => '0','health' => '0','text' => '<b>Hero Power</b><br> +1 Attack this turn.  +1 Armor.','class' => '1','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '185'),
		array('id' => '231','mana' => '1','name' => 'Silver Hand Recruit','attack' => '1','health' => '1','text' => '','class' => '4','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '268'),
		array('id' => '232','mana' => '1','name' => 'Sinister Strike','attack' => '0','health' => '0','text' => 'Deal 3 damage to the enemy hero.','class' => '6','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '205'),
		array('id' => '233','mana' => '2','name' => 'Steady Shot','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Deal 2 damage to the enemy hero.','class' => '2','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '481'),
		array('id' => '234','mana' => '1','name' => 'Stoneclaw Totem','attack' => '0','health' => '2','text' => '<b>Taunt</b>','class' => '7','type' => '3','rarity' => NULL,'race' => '6','pwn_id' => '298'),
		array('id' => '235','mana' => '1','name' => 'Stonetusk Boar','attack' => '1','health' => '1','text' => '<b>Charge</b>','class' => NULL,'type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '76'),
		array('id' => '236','mana' => '2','name' => 'Succubus','attack' => '4','health' => '3','text' => '<b>Battlecry:</b> Discard a random card.','class' => '8','type' => '3','rarity' => NULL,'race' => '2','pwn_id' => '208'),
		array('id' => '237','mana' => '0','name' => 'Thrall','attack' => '0','health' => '30','text' => '','class' => '7','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '319'),
		array('id' => '238','mana' => '1','name' => 'Timber Wolf','attack' => '1','health' => '1','text' => 'Your other Beasts have +1 Attack.','class' => '2','type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '86'),
		array('id' => '239','mana' => '2','name' => 'Totemic Call','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Summon a random Totem.','class' => '7','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '316'),
		array('id' => '240','mana' => '1','name' => 'Tracking','attack' => '0','health' => '0','text' => 'Look at the top three cards of your deck. Draw one and discard the others.','class' => '2','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '163'),
		array('id' => '241','mana' => '0','name' => 'Uther Lightbringer','attack' => '0','health' => '30','text' => '','class' => '4','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '257'),
		array('id' => '242','mana' => '0','name' => 'Valeera Sanguinar','attack' => '0','health' => '30','text' => '','class' => '6','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '2'),
		array('id' => '243','mana' => '1','name' => 'Voidwalker','attack' => '1','health' => '3','text' => '<b>Taunt</b>','class' => '8','type' => '3','rarity' => NULL,'race' => '2','pwn_id' => '340'),
		array('id' => '244','mana' => '1','name' => 'Voodoo Doctor','attack' => '2','health' => '1','text' => '<b>Battlecry:</b> Restore 2 Health.','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '410'),
		array('id' => '245','mana' => '3','name' => 'Warsong Commander','attack' => '2','health' => '3','text' => 'Your other minions have <b>Charge</b>.','class' => '9','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '193'),
		array('id' => '246','mana' => '1','name' => 'Wicked Knife','attack' => '1','health' => '2','text' => '','class' => '6','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '183'),
		array('id' => '247','mana' => '2','name' => 'Wild Growth','attack' => '0','health' => '0','text' => 'Gain an empty Mana Crystal.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '282'),
		array('id' => '248','mana' => '2','name' => 'Windfury','attack' => '0','health' => '0','text' => 'Give a minion <b>Windfury</b>.','class' => '7','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '146'),
		array('id' => '249','mana' => '3','name' => 'Wolfrider','attack' => '3','health' => '1','text' => '<b>Charge</b>','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '174'),
		array('id' => '250','mana' => '1','name' => 'Wrath of Air Totem','attack' => '0','health' => '2','text' => '<b>Spell Damage +1</b>','class' => '7','type' => '3','rarity' => NULL,'race' => '6','pwn_id' => '365'),
		array('id' => '251','mana' => '1','name' => 'Abusive Sergeant','attack' => '2','health' => '1','text' => '<b>Battlecry:</b> Give a friendly minion +2 Attack this turn.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '577'),
		array('id' => '252','mana' => '2','name' => 'Acidic Swamp Ooze','attack' => '3','health' => '2','text' => '<b>Battlecry:</b> Destroy your opponent\'s weapon.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '74'),
		array('id' => '253','mana' => '3','name' => 'Acolyte of Pain','attack' => '1','health' => '3','text' => 'Whenever this minion takes damage, draw a card.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '428'),
		array('id' => '254','mana' => '2','name' => 'Amani Berserker','attack' => '2','health' => '3','text' => '<b>Enrage:</b> +3 Attack','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '641'),
		array('id' => '255','mana' => '4','name' => 'Ancient Brewmaster','attack' => '5','health' => '4','text' => '<b>Battlecry:</b> Return a friendly minion from the battlefield to your hand.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '572'),
		array('id' => '256','mana' => '3','name' => 'Animal Companion','attack' => '0','health' => '0','text' => 'Summon a random Beast Companion.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '578'),
		array('id' => '257','mana' => '4','name' => 'Arathi Weaponsmith','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Equip a 2/2 weapon.','class' => '9','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '504'),
		array('id' => '258','mana' => '5','name' => 'Arcanite Reaper','attack' => '5','health' => '2','text' => '','class' => '9','type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '182'),
		array('id' => '259','mana' => '6','name' => 'Archmage','attack' => '4','health' => '7','text' => '<b>Spell Damage +1</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '545'),
		array('id' => '260','mana' => '2','name' => 'Argent Protector','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Give a friendly minion <b>Divine Shield</b>.','class' => '4','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '191'),
		array('id' => '261','mana' => '1','name' => 'Argent Squire','attack' => '1','health' => '1','text' => '<b>Divine Shield</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '473'),
		array('id' => '262','mana' => '5','name' => 'Assassin\'s Blade','attack' => '3','health' => '4','text' => '','class' => '6','type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '433'),
		array('id' => '263','mana' => '1','name' => 'Bananas','attack' => '0','health' => '0','text' => 'Give a friendly minion +1/+1. <i>(+1 Attack/+1 Health)</i>','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '156'),
		array('id' => '264','mana' => '0','name' => 'Barrel','attack' => '0','health' => '2','text' => 'Is something in this barrel?','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '376'),
		array('id' => '265','mana' => '1','name' => 'Barrel Toss','attack' => '0','health' => '0','text' => 'Deal 2 damage.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '606'),
		array('id' => '266','mana' => '2','name' => 'Battle Rage','attack' => '0','health' => '0','text' => 'Draw a card for each damaged friendly character.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '664'),
		array('id' => '267','mana' => '0','name' => 'Bear Form','attack' => '0','health' => '0','text' => '+2 Health and <b>Taunt</b>.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '662'),
		array('id' => '268','mana' => '2','name' => 'Betrayal','attack' => '0','health' => '0','text' => 'An enemy minion deals its damage to the minions next to it.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '198'),
		array('id' => '269','mana' => '4','name' => 'Blessing of Kings','attack' => '0','health' => '0','text' => 'Give a minion +4/+4. <i>(+4 Attack/+4 Health)</i>','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '29'),
		array('id' => '270','mana' => '1','name' => 'Blessing of Wisdom','attack' => '0','health' => '0','text' => 'Choose a minion.  Whenever it attacks, draw a card.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '100'),
		array('id' => '271','mana' => '1','name' => 'Blood Imp','attack' => '1','health' => '1','text' => '<b>Stealth</b>. Your other minions have +1 Health.','class' => '8','type' => '3','rarity' => '2','race' => '2','pwn_id' => '196'),
		array('id' => '272','mana' => '5','name' => 'Bloodlust','attack' => '0','health' => '0','text' => 'Give your minions +3 Attack this turn.','class' => '7','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '256'),
		array('id' => '273','mana' => '2','name' => 'Bloodsail Raider','attack' => '2','health' => '3','text' => '<b>Battlecry:</b> Gain Attack equal to the Attack of your weapon.','class' => NULL,'type' => '3','rarity' => '2','race' => '5','pwn_id' => '637'),
		array('id' => '274','mana' => '2','name' => 'Bluegill Warrior','attack' => '2','health' => '1','text' => '<b>Charge</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '4','pwn_id' => '289'),
		array('id' => '275','mana' => '1','name' => 'Boar','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '65'),
		array('id' => '276','mana' => '5','name' => 'Booty Bay Bodyguard','attack' => '5','health' => '4','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '27'),
		array('id' => '277','mana' => '4','name' => 'Brewmaster','attack' => '4','health' => '4','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '397'),
		array('id' => '278','mana' => '0','name' => 'Cat Form','attack' => '0','health' => '0','text' => '<b>Charge</b>','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '287'),
		array('id' => '279','mana' => '4','name' => 'Chillwind Yeti','attack' => '4','health' => '5','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '31'),
		array('id' => '280','mana' => '0','name' => 'Circle of Healing','attack' => '0','health' => '0','text' => 'Restore #4 Health to ALL minions.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '38'),
		array('id' => '281','mana' => '2','name' => 'Cleave','attack' => '0','health' => '0','text' => 'Deal 2 damage to two random enemy minions.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '81'),
		array('id' => '282','mana' => '1','name' => 'Cold Blood','attack' => '0','health' => '0','text' => 'Give a minion +2 Attack. <b>Combo:</b> +4 Attack instead.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '92'),
		array('id' => '283','mana' => '1','name' => 'Conceal','attack' => '0','health' => '0','text' => 'Give your minions <b>Stealth</b> until your next turn.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '284'),
		array('id' => '284','mana' => '3','name' => 'Cone of Cold','attack' => '0','health' => '0','text' => '<b>Freeze</b> a minion and the minions next to it, and deal 1 damage to them.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '26'),
		array('id' => '285','mana' => '4','name' => 'Consecration','attack' => '0','health' => '0','text' => 'Deal 2 damage to all enemies.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '260'),
		array('id' => '286','mana' => '7','name' => 'Core Hound','attack' => '9','health' => '5','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '173'),
		array('id' => '287','mana' => '1','name' => 'Corruption','attack' => '0','health' => '0','text' => 'Choose an enemy minion.   At the start of your turn, destroy it.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '252'),
		array('id' => '288','mana' => '1','name' => 'Crazed Hunter','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '377'),
		array('id' => '289','mana' => '1','name' => 'Crazy Monkey','attack' => '1','health' => '2','text' => '<b>Battlecry:</b> Throw Bananas.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '393'),
		array('id' => '290','mana' => '2','name' => 'Cruel Taskmaster','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Deal 1 damage to a minion and give it +2 Attack.','class' => '9','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '328'),
		array('id' => '291','mana' => '4','name' => 'Cult Master','attack' => '4','health' => '2','text' => 'Whenever one of your other minions dies, draw a card.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '140'),
		array('id' => '292','mana' => '3','name' => 'Dalaran Mage','attack' => '1','health' => '4','text' => '<b>Spell Damage +1</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '388'),
		array('id' => '293','mana' => '1','name' => 'Damaged Golem','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '200'),
		array('id' => '294','mana' => '4','name' => 'Dark Iron Dwarf','attack' => '4','health' => '4','text' => '<b>Battlecry:</b> Give a minion +2 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '128'),
		array('id' => '295','mana' => '5','name' => 'Darkscale Healer','attack' => '4','health' => '5','text' => '<b>Battlecry:</b> Restore 2 Health to all friendly characters.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '84'),
		array('id' => '296','mana' => '3','name' => 'Deadly Shot','attack' => '0','health' => '0','text' => 'Destroy a random enemy minion.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '239'),
		array('id' => '297','mana' => '1','name' => 'Defender','attack' => '2','health' => '1','text' => '','class' => '4','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '318'),
		array('id' => '298','mana' => '2','name' => 'Defias Ringleader','attack' => '2','health' => '2','text' => '<b>Combo:</b> Summon a 2/1 Defias Bandit.','class' => '6','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '417'),
		array('id' => '299','mana' => '2','name' => 'Demonfire','attack' => '0','health' => '0','text' => 'Deal 2 damage to a minion.   If itâs a friendly Demon, give it +2/+2 instead.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '452'),
		array('id' => '300','mana' => '5','name' => 'Devilsaur','attack' => '5','health' => '5','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '354'),
		array('id' => '301','mana' => '2','name' => 'Dire Wolf Alpha','attack' => '2','health' => '2','text' => 'Adjacent minions have +1 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '305'),
		array('id' => '302','mana' => '2','name' => 'Divine Spirit','attack' => '0','health' => '0','text' => 'Double a minion\'s Health.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '554'),
		array('id' => '303','mana' => '4','name' => 'Dragonling Mechanic','attack' => '2','health' => '4','text' => '<b>Battlecry:</b> Summon a 2/1 Mechanical Dragonling.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '472'),
		array('id' => '304','mana' => '4','name' => 'Dread Corsair','attack' => '3','health' => '3','text' => '<b>Taunt.</b> Costs (1) less per Attack of your weapon.','class' => NULL,'type' => '3','rarity' => '2','race' => '5','pwn_id' => '261'),
		array('id' => '305','mana' => '6','name' => 'Dread Infernal','attack' => '6','health' => '6','text' => '<b>Battlecry:</b> Deal 1 damage to ALL other characters.','class' => '8','type' => '3','rarity' => '2','race' => '2','pwn_id' => '36'),
		array('id' => '306','mana' => '5','name' => 'Druid of the Claw','attack' => '4','health' => '4','text' => '<b>Choose One -</b> <b>Charge</b>; or +2 Health and <b>Taunt</b>.','class' => '1','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '587'),
		array('id' => '307','mana' => '5','name' => 'Druid of the Claw','attack' => '4','health' => '4','text' => '<b>Charge</b>','class' => '1','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '408'),
		array('id' => '308','mana' => '5','name' => 'Druid of the Claw','attack' => '4','health' => '6','text' => '<b>Taunt</b>','class' => '1','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '45'),
		array('id' => '309','mana' => '6','name' => 'Dual Warglaives','attack' => '4','health' => '2','text' => '','class' => NULL,'type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '599'),
		array('id' => '310','mana' => '1','name' => 'Dust Devil','attack' => '3','health' => '1','text' => '<b>Windfury</b>. <b>Overload:</b> (2)','class' => '7','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '129'),
		array('id' => '311','mana' => '1','name' => 'Earth Shock','attack' => '0','health' => '0','text' => '<b>Silence</b> a minion, then deal 1 damage to it.','class' => '7','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '77'),
		array('id' => '312','mana' => '3','name' => 'Earthen Ring Farseer','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Restore 3 Health.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '557'),
		array('id' => '313','mana' => '1','name' => 'Elven Archer','attack' => '1','health' => '1','text' => '<b>Battlecry:</b> Deal 1 damage.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '356'),
		array('id' => '314','mana' => '1','name' => 'Emboldener 3000','attack' => '0','health' => '4','text' => 'At the end of your turn, give a random minion +1/+1.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '240'),
		array('id' => '315','mana' => '2','name' => 'Eviscerate','attack' => '0','health' => '0','text' => 'Deal 2 damage. <b>Combo:</b> Deal 4 damage instead.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '382'),
		array('id' => '316','mana' => '2','name' => 'Explosive Trap','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your hero is attacked, deal 2 damage to all enemies.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '344'),
		array('id' => '317','mana' => '1','name' => 'Eye for an Eye','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your hero takes damage, deal that much damage to the enemy hero.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '206'),
		array('id' => '318','mana' => '2','name' => 'Faerie Dragon','attack' => '3','health' => '2','text' => 'Can\'t be targeted by Spells or Hero Powers.','class' => NULL,'type' => '3','rarity' => '2','race' => '3','pwn_id' => '213'),
		array('id' => '319','mana' => '3','name' => 'Fan of Knives','attack' => '0','health' => '0','text' => 'Deal 1 damage to all enemy minions. Draw a card.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '378'),
		array('id' => '320','mana' => '5','name' => 'Fen Creeper','attack' => '3','health' => '6','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '476'),
		array('id' => '321','mana' => '6','name' => 'Fire Elemental','attack' => '6','health' => '5','text' => '<b>Battlecry:</b> Deal 3 damage.','class' => '7','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '636'),
		array('id' => '322','mana' => '3','name' => 'Flame Burst','attack' => '0','health' => '0','text' => 'Shoot 5 missiles at random enemies for 1 damage each.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '622'),
		array('id' => '323','mana' => '1','name' => 'Flame Imp','attack' => '3','health' => '2','text' => '<b>Battlecry:</b> Deal 2 damage to your hero.','class' => '8','type' => '3','rarity' => '2','race' => '2','pwn_id' => '85'),
		array('id' => '324','mana' => '1','name' => 'Flame of Azzinoth','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '455'),
		array('id' => '325','mana' => '7','name' => 'Flamestrike','attack' => '0','health' => '0','text' => 'Deal 4 damage to all enemy minions.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '44'),
		array('id' => '326','mana' => '2','name' => 'Flametongue Totem','attack' => '0','health' => '3','text' => 'Adjacent minions have +2 Attack.','class' => '7','type' => '3','rarity' => '2','race' => '6','pwn_id' => '390'),
		array('id' => '327','mana' => '3','name' => 'Flesheating Ghoul','attack' => '2','health' => '3','text' => 'Whenever a minion dies, gain +1 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '610'),
		array('id' => '328','mana' => '1','name' => 'Forked Lightning','attack' => '0','health' => '0','text' => 'Deal 2 damage to 2 random enemy minions. <b>Overload:</b> (2)','class' => '7','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '530'),
		array('id' => '329','mana' => '2','name' => 'Freezing Trap','attack' => '0','health' => '0','text' => '<b>Secret:</b> When an enemy minion attacks, return it to its owner\'s hand and it costs (2) more.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '99'),
		array('id' => '330','mana' => '0','name' => 'Frog','attack' => '0','health' => '1','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '115'),
		array('id' => '331','mana' => '6','name' => 'Frost Elemental','attack' => '5','health' => '5','text' => '<b>Battlecry:</b> <b>Freeze</b> a character.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '598'),
		array('id' => '332','mana' => '2','name' => 'Frost Nova','attack' => '0','health' => '0','text' => '<b>Freeze</b> all enemy minions.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '49'),
		array('id' => '333','mana' => '2','name' => 'Frostbolt','attack' => '0','health' => '0','text' => 'Deal 3 damage to a character and <b>Freeze</b> it.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '177'),
		array('id' => '334','mana' => '2','name' => 'Frostwolf Grunt','attack' => '2','health' => '2','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '663'),
		array('id' => '335','mana' => '5','name' => 'Frostwolf Warlord','attack' => '4','health' => '4','text' => '<b>Battlecry:</b> Gain +1/+1 for each other friendly minion on the battlefield.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '604'),
		array('id' => '336','mana' => '1','name' => 'Gnoll','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '381'),
		array('id' => '337','mana' => '4','name' => 'Gnomish Inventor','attack' => '2','health' => '4','text' => '<b>Battlecry:</b> Draw a card.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '246'),
		array('id' => '338','mana' => '1','name' => 'Goldshire Footman','attack' => '1','health' => '2','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '564'),
		array('id' => '339','mana' => '1','name' => 'Grimscale Oracle','attack' => '1','health' => '1','text' => 'ALL other Murlocs have +1 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => '4','pwn_id' => '510'),
		array('id' => '340','mana' => '7','name' => 'Guardian of Kings','attack' => '5','health' => '6','text' => '<b>Battlecry:</b> Restore 6 Health to your hero.','class' => '4','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '283'),
		array('id' => '341','mana' => '5','name' => 'Gurubashi Berserker','attack' => '2','health' => '7','text' => 'Whenever this minion takes damage, gain +3 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '624'),
		array('id' => '342','mana' => '3','name' => 'Harvest Golem','attack' => '2','health' => '3','text' => '<b>Deathrattle:</b> Summon a 2/1 Damaged Golem.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '386'),
		array('id' => '343','mana' => '0','name' => 'Hemet Nesingwary','attack' => '0','health' => '20','text' => '','class' => '2','type' => '4','rarity' => '2','race' => NULL,'pwn_id' => '470'),
		array('id' => '344','mana' => '2','name' => 'Hidden Gnome','attack' => '1','health' => '3','text' => 'Was hiding in a barrel!','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '387'),
		array('id' => '345','mana' => '0','name' => 'Hogger','attack' => '0','health' => '10','text' => '','class' => NULL,'type' => '4','rarity' => '2','race' => NULL,'pwn_id' => '490'),
		array('id' => '346','mana' => '3','name' => 'Hogger SMASH!','attack' => '0','health' => '0','text' => 'Deal 4 damage.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '443'),
		array('id' => '347','mana' => '5','name' => 'Holy Nova','attack' => '0','health' => '0','text' => 'Deal 2 damage to all enemies.  Restore #2 Health to all  friendly characters.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '671'),
		array('id' => '348','mana' => '1','name' => 'Homing Chicken','attack' => '0','health' => '1','text' => 'At the start of your turn, destroy this minion and draw 3 cards.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '423'),
		array('id' => '349','mana' => '3','name' => 'Huffer','attack' => '4','health' => '2','text' => '<b>Charge</b>','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '369'),
		array('id' => '350','mana' => '1','name' => 'Humility','attack' => '0','health' => '0','text' => 'Change a minion\'s Attack to 1.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '189'),
		array('id' => '351','mana' => '0','name' => 'Hunter\'s Mark','attack' => '0','health' => '0','text' => 'Change a minion\'s Health to 1.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '22'),
		array('id' => '352','mana' => '3','name' => 'Ice Barrier','attack' => '0','health' => '0','text' => '<b>Secret:</b> As soon as your hero is attacked, gain 8 Armor.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '672'),
		array('id' => '353','mana' => '1','name' => 'Ice Lance','attack' => '0','health' => '0','text' => '<b>Freeze</b> a character. If it was already <b>Frozen</b>, deal 4 damage instead.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '188'),
		array('id' => '354','mana' => '6','name' => 'Infernal','attack' => '6','health' => '6','text' => '','class' => '8','type' => '3','rarity' => '2','race' => '2','pwn_id' => '121'),
		array('id' => '355','mana' => '1','name' => 'Inner Fire','attack' => '0','health' => '0','text' => 'Change a minion\'s Attack to be equal to its Health.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '207'),
		array('id' => '356','mana' => '0','name' => 'Inner Rage','attack' => '0','health' => '0','text' => 'Deal 1 damage to a minion and give it +2 Attack.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '366'),
		array('id' => '357','mana' => '8','name' => 'Ironbark Protector','attack' => '8','health' => '8','text' => '<b>Taunt</b>','class' => '1','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '238'),
		array('id' => '358','mana' => '2','name' => 'Ironbeak Owl','attack' => '2','health' => '1','text' => '<b>Battlecry:</b> <b>Silence</b> a minion.','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '500'),
		array('id' => '359','mana' => '3','name' => 'Ironforge Rifleman','attack' => '2','health' => '2','text' => '<b>Battlecry:</b> Deal 1 damage.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '41'),
		array('id' => '360','mana' => '3','name' => 'Ironfur Grizzly','attack' => '3','health' => '3','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '519'),
		array('id' => '361','mana' => '0','name' => 'Jaina Proudmoore','attack' => '0','health' => '27','text' => '','class' => '3','type' => '4','rarity' => '2','race' => NULL,'pwn_id' => '139'),
		array('id' => '362','mana' => '3','name' => 'Jungle Panther','attack' => '4','health' => '2','text' => '<b>Stealth</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '392'),
		array('id' => '363','mana' => '3','name' => 'Kill Command','attack' => '0','health' => '0','text' => 'Deal 3 damage.  If you have a Beast, deal 5 damage instead.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '488'),
		array('id' => '364','mana' => '0','name' => 'King Mukla','attack' => '0','health' => '26','text' => '','class' => NULL,'type' => '4','rarity' => '2','race' => NULL,'pwn_id' => '444'),
		array('id' => '365','mana' => '2','name' => 'Kobold Geomancer','attack' => '2','health' => '2','text' => '<b>Spell Damage +1</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '479'),
		array('id' => '366','mana' => '4','name' => 'Kor\'kron Elite','attack' => '4','health' => '3','text' => '<b>Charge</b>','class' => '9','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '130'),
		array('id' => '367','mana' => '3','name' => 'Legacy of the Emperor','attack' => '0','health' => '0','text' => 'Give your minions +2/+2. <i>(+2 Attack/+2 Health)</i>','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '441'),
		array('id' => '368','mana' => '3','name' => 'Leokk','attack' => '2','health' => '4','text' => 'Other friendly minions have +1 Attack.','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '32'),
		array('id' => '369','mana' => '1','name' => 'Leper Gnome','attack' => '2','health' => '1','text' => '<b>Deathrattle:</b> Deal 2 damage to the enemy hero.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '513'),
		array('id' => '370','mana' => '1','name' => 'Lightning Bolt','attack' => '0','health' => '0','text' => 'Deal 3 damage. <b>Overload:</b> (1)','class' => '7','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '10'),
		array('id' => '371','mana' => '4','name' => 'Lightspawn','attack' => '0','health' => '5','text' => 'This minion\'s Attack is always equal to its Health.','class' => '5','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '192'),
		array('id' => '372','mana' => '2','name' => 'Loot Hoarder','attack' => '2','health' => '1','text' => '<b>Deathrattle:</b> Draw a card.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '395'),
		array('id' => '373','mana' => '6','name' => 'Lord of the Arena','attack' => '6','health' => '5','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '414'),
		array('id' => '374','mana' => '2','name' => 'Mad Bomber','attack' => '3','health' => '2','text' => '<b>Battlecry:</b> Deal 3 damage randomly split between all other characters.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '80'),
		array('id' => '375','mana' => '1','name' => 'Mana Wyrm','attack' => '1','health' => '3','text' => 'Whenever you cast a spell, gain +1 Attack.','class' => '3','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '263'),
		array('id' => '376','mana' => '3','name' => 'Mark of Nature','attack' => '0','health' => '0','text' => '<b>Choose One</b> - Give a minion +4 Attack; or +4 Health and <b>Taunt</b>.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '149'),
		array('id' => '377','mana' => '4','name' => 'Massive Gnoll','attack' => '5','health' => '2','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '137'),
		array('id' => '378','mana' => '1','name' => 'Mechanical Dragonling','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '680'),
		array('id' => '379','mana' => '6','name' => 'Metamorphosis','attack' => '0','health' => '0','text' => 'Do something crazy.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '592'),
		array('id' => '380','mana' => '0','name' => 'Millhouse Manastorm','attack' => '0','health' => '20','text' => '','class' => '3','type' => '4','rarity' => '2','race' => NULL,'pwn_id' => '330'),
		array('id' => '381','mana' => '8','name' => 'Mind Control','attack' => '0','health' => '0','text' => 'Take control of an enemy minion.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '401'),
		array('id' => '382','mana' => '1','name' => 'Mind Vision','attack' => '0','health' => '0','text' => 'Put a copy of a random card in your opponent\'s hand into your hand.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '438'),
		array('id' => '383','mana' => '3','name' => 'Mirror Entity','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your opponent plays a minion, summon a copy of it.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '569'),
		array('id' => '384','mana' => '1','name' => 'Mirror Image','attack' => '0','health' => '0','text' => 'Summon two 0/2 minions with <b>Taunt</b>.','class' => '3','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '30'),
		array('id' => '385','mana' => '0','name' => 'Mirror Image','attack' => '0','health' => '2','text' => '<b>Taunt</b>','class' => '3','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '650'),
		array('id' => '386','mana' => '3','name' => 'Misha','attack' => '4','health' => '4','text' => '<b>Taunt</b>','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '593'),
		array('id' => '387','mana' => '4','name' => 'Mogu\'shan Warden','attack' => '1','health' => '7','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '346'),
		array('id' => '388','mana' => '0','name' => 'Moonfire','attack' => '0','health' => '0','text' => 'Deal 1 damage.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '619'),
		array('id' => '389','mana' => '1','name' => 'Mortal Coil','attack' => '0','health' => '0','text' => 'Deal 1 damage to a minion. If that kills it, draw a card.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '43'),
		array('id' => '390','mana' => '6','name' => 'Mukla\'s Big Brother','attack' => '10','health' => '10','text' => 'So strong! And only 6 Mana?!','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '404'),
		array('id' => '391','mana' => '0','name' => 'Murloc Scout','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '4','pwn_id' => '486'),
		array('id' => '392','mana' => '2','name' => 'Murloc Tidehunter','attack' => '2','health' => '1','text' => '<b>Battlecry:</b> Summon a 1/1 Murloc Scout.','class' => NULL,'type' => '3','rarity' => '2','race' => '4','pwn_id' => '357'),
		array('id' => '393','mana' => '1','name' => 'Naga Myrmidon','attack' => '1','health' => '1','text' => '<b></b> ','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '16'),
		array('id' => '394','mana' => '1','name' => 'Naturalize','attack' => '0','health' => '0','text' => 'Destroy a minion. Your opponent draws 2 cards.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '154'),
		array('id' => '395','mana' => '1','name' => 'Noble Sacrifice','attack' => '0','health' => '0','text' => '<b>Secret:</b> When an enemy attacks, summon a 2/1 Defender as the new target.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '158'),
		array('id' => '396','mana' => '4','name' => 'Ogre Magi','attack' => '4','health' => '4','text' => '<b>Spell Damage +1</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '659'),
		array('id' => '397','mana' => '1','name' => 'Pandaren Scout','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '399'),
		array('id' => '398','mana' => '2','name' => 'Panther','attack' => '3','health' => '2','text' => '','class' => '1','type' => '3','rarity' => '2','race' => '1','pwn_id' => '190'),
		array('id' => '399','mana' => '1','name' => 'Poultryizer','attack' => '0','health' => '3','text' => 'At the start of your turn, transform a random minion into a 1/1 Chicken.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '405'),
		array('id' => '400','mana' => '2','name' => 'Power of the Wild','attack' => '0','health' => '0','text' => '<b>Choose One</b> - Give your minions +1/+1; or Summon a 3/2 Panther.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '165'),
		array('id' => '401','mana' => '1','name' => 'Power Overwhelming','attack' => '0','health' => '0','text' => 'Give a friendly minion +4/+4 until end of turn. Then, it dies. Horribly.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '170'),
		array('id' => '402','mana' => '6','name' => 'Priestess of Elune','attack' => '5','health' => '4','text' => '<b>Battlecry:</b> Restore 4 Health to your hero.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '138'),
		array('id' => '403','mana' => '3','name' => 'Raging Worgen','attack' => '3','health' => '3','text' => '<b>Enrage:</b> <b>Windfury</b> and +1 Attack','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '95'),
		array('id' => '404','mana' => '2','name' => 'Rampage','attack' => '0','health' => '0','text' => 'Give a damaged minion +3/+3.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '454'),
		array('id' => '405','mana' => '3','name' => 'Razorfen Hunter','attack' => '2','health' => '3','text' => '<b>Battlecry:</b> Summon a 1/1 Boar.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '47'),
		array('id' => '406','mana' => '1','name' => 'Redemption','attack' => '0','health' => '0','text' => '<b>Secret:</b> When one of your minions dies, return it to life with 1 Health.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '657'),
		array('id' => '407','mana' => '1','name' => 'Repair Bot','attack' => '0','health' => '3','text' => 'At the end of your turn, restore 6 Health to a damaged character.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '439'),
		array('id' => '408','mana' => '1','name' => 'Repentance','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your opponent plays a minion, reduce its Health to 1.','class' => '4','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '642'),
		array('id' => '409','mana' => '1','name' => 'Riverpaw Gnoll','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '678'),
		array('id' => '410','mana' => '0','name' => 'Sacrificial Pact','attack' => '0','health' => '0','text' => 'Destroy a Demon. Restore #5 Health to your hero.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '348'),
		array('id' => '411','mana' => '3','name' => 'Savage Roar','attack' => '0','health' => '0','text' => 'Give your characters +2 Attack this turn.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '329'),
		array('id' => '412','mana' => '3','name' => 'Scarlet Crusader','attack' => '3','health' => '1','text' => '<b>Divine Shield</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '475'),
		array('id' => '413','mana' => '2','name' => 'Scavenging Hyena','attack' => '2','health' => '2','text' => 'Whenever a friendly Beast dies, gain +2/+1.','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '279'),
		array('id' => '414','mana' => '3','name' => 'Sense Demons','attack' => '0','health' => '0','text' => 'Put 2 random Demons from your deck into your hand.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '327'),
		array('id' => '415','mana' => '2','name' => 'Shado-Pan Monk','attack' => '2','health' => '2','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '288'),
		array('id' => '416','mana' => '3','name' => 'Shadow Word: Death','attack' => '0','health' => '0','text' => 'Destroy a minion with an Attack of 5 or more.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '547'),
		array('id' => '417','mana' => '0','name' => 'Shadowstep','attack' => '0','health' => '0','text' => 'Return a friendly minion to your hand. It costs (2) less.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '550'),
		array('id' => '418','mana' => '3','name' => 'Shattered Sun Cleric','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Give a friendly minion +1/+1.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '434'),
		array('id' => '419','mana' => '0','name' => 'Sheep','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '218'),
		array('id' => '420','mana' => '3','name' => 'Shield Block','attack' => '0','health' => '0','text' => 'Gain 5 Armor.  Draw a card.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '493'),
		array('id' => '421','mana' => '1','name' => 'Shieldbearer','attack' => '0','health' => '4','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '24'),
		array('id' => '422','mana' => '2','name' => 'Shiv','attack' => '0','health' => '0','text' => 'Deal 1 damage. Draw a card.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '164'),
		array('id' => '423','mana' => '2','name' => 'Shotgun Blast','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Deal 1 damage.','class' => '2','type' => '6','rarity' => '2','race' => NULL,'pwn_id' => '580'),
		array('id' => '424','mana' => '0','name' => 'Silence','attack' => '0','health' => '0','text' => '<b>Silence</b> a minion.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '544'),
		array('id' => '425','mana' => '5','name' => 'Silver Hand Knight','attack' => '4','health' => '4','text' => '<b>Battlecry:</b> Summon a 2/2 Squire.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '648'),
		array('id' => '426','mana' => '3','name' => 'Silverback Patriarch','attack' => '1','health' => '4','text' => '<b>Taunt</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '611'),
		array('id' => '427','mana' => '4','name' => 'Silvermoon Guardian','attack' => '3','health' => '3','text' => '<b>Divine Shield</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '634'),
		array('id' => '428','mana' => '1','name' => 'Skeleton','attack' => '1','health' => '1','text' => '<b></b> ','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '313'),
		array('id' => '429','mana' => '2','name' => 'Slam','attack' => '0','health' => '0','text' => 'Deal 2 damage to a minion.  If it survives, draw a card.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '215'),
		array('id' => '430','mana' => '0','name' => 'Snake','attack' => '1','health' => '1','text' => '','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '512'),
		array('id' => '431','mana' => '2','name' => 'Snipe','attack' => '0','health' => '0','text' => '<b>Secret:</b> When your opponent plays a minion, deal 4 damage to it.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '553'),
		array('id' => '432','mana' => '2','name' => 'Sorcerer\'s Apprentice','attack' => '3','health' => '2','text' => 'Your spells cost (1) less.','class' => '3','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '4'),
		array('id' => '433','mana' => '4','name' => 'Soul of the Forest','attack' => '0','health' => '0','text' => 'Give your minions "<b>Deathrattle:</b> Summon a 2/2 Treant."','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '311'),
		array('id' => '434','mana' => '0','name' => 'Soulfire','attack' => '0','health' => '0','text' => 'Deal 4 damage. Discard a random card.','class' => '8','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '529'),
		array('id' => '435','mana' => '1','name' => 'Southsea Deckhand','attack' => '2','health' => '1','text' => 'Has <b>Charge</b> while you have a weapon equipped.','class' => NULL,'type' => '3','rarity' => '2','race' => '5','pwn_id' => '103'),
		array('id' => '436','mana' => '4','name' => 'Spellbreaker','attack' => '4','health' => '3','text' => '<b>Battlecry:</b> <b>Silence</b> a minion.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '42'),
		array('id' => '437','mana' => '5','name' => 'Spiteful Smith','attack' => '4','health' => '6','text' => '<b>Enrage:</b> Your weapon has +2 Attack.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '627'),
		array('id' => '438','mana' => '7','name' => 'Sprint','attack' => '0','health' => '0','text' => 'Draw 4 cards.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '90'),
		array('id' => '439','mana' => '1','name' => 'Squire','attack' => '2','health' => '2','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '21'),
		array('id' => '440','mana' => '1','name' => 'Squirrel','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '469'),
		array('id' => '441','mana' => '6','name' => 'Starfire','attack' => '0','health' => '0','text' => 'Deal 5 damage.  Draw a card.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '667'),
		array('id' => '442','mana' => '2','name' => 'Starving Buzzard','attack' => '2','health' => '2','text' => 'Whenever you summon a Beast, draw a card.','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '101'),
		array('id' => '443','mana' => '2','name' => 'Stomp','attack' => '0','health' => '0','text' => 'Deal 2 damage to all enemies.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '105'),
		array('id' => '444','mana' => '2','name' => 'Stormforged Axe','attack' => '2','health' => '3','text' => '<b>Overload:</b> (1)','class' => '7','type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '152'),
		array('id' => '445','mana' => '5','name' => 'Stormpike Commando','attack' => '4','health' => '2','text' => '<b>Battlecry:</b> Deal 2 damage.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '325'),
		array('id' => '446','mana' => '7','name' => 'Stormwind Champion','attack' => '6','health' => '6','text' => 'Your other minions have +1/+1.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '310'),
		array('id' => '447','mana' => '4','name' => 'Stormwind Knight','attack' => '2','health' => '5','text' => '<b>Charge</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '603'),
		array('id' => '448','mana' => '5','name' => 'Stranglethorn Tiger','attack' => '5','health' => '5','text' => '<b>Stealth</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '338'),
		array('id' => '449','mana' => '4','name' => 'Summoning Portal','attack' => '0','health' => '4','text' => 'Your minions cost (2) less, but not less than (1).','class' => '8','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '566'),
		array('id' => '450','mana' => '4','name' => 'Swipe','attack' => '0','health' => '0','text' => 'Deal 4 damage to an enemy and 1 damage to all other enemies.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '620'),
		array('id' => '451','mana' => '3','name' => 'Tauren Warrior','attack' => '2','health' => '3','text' => '<b>Taunt</b>. <b>Enrage:</b> +3 Attack','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '477'),
		array('id' => '452','mana' => '6','name' => 'Temple Enforcer','attack' => '6','health' => '6','text' => '<b>Battlecry:</b> Give a friendly minion +3 Health.','class' => '5','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '232'),
		array('id' => '453','mana' => '3','name' => 'Thoughtsteal','attack' => '0','health' => '0','text' => 'Copy 2 cards from your opponent\'s deck and put them into your hand.','class' => '5','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '62'),
		array('id' => '454','mana' => '3','name' => 'Thrallmar Farseer','attack' => '2','health' => '3','text' => '<b>Windfury</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '265'),
		array('id' => '455','mana' => '0','name' => 'Totemic Might','attack' => '0','health' => '0','text' => 'Give your Totems +2 Health.','class' => '7','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '367'),
		array('id' => '456','mana' => '1','name' => 'Transcendence','attack' => '0','health' => '0','text' => 'Until you kill Cho\'s minions, he can\'t be attacked.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '299'),
		array('id' => '457','mana' => '1','name' => 'Treant','attack' => '2','health' => '2','text' => '<b>Charge</b>.  At the end of the turn, destroy this minion.','class' => '1','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '337'),
		array('id' => '458','mana' => '4','name' => 'Truesilver Champion','attack' => '4','health' => '2','text' => 'Whenever your hero attacks, restore 2 Health to it.','class' => '4','type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '293'),
		array('id' => '459','mana' => '5','name' => 'Tundra Rhino','attack' => '2','health' => '5','text' => 'Your Beasts have <b>Charge</b>.','class' => '2','type' => '3','rarity' => '2','race' => '1','pwn_id' => '162'),
		array('id' => '460','mana' => '3','name' => 'Unbound Elemental','attack' => '2','health' => '4','text' => 'Whenever you play a card with <b>Overload</b>, gain +1/+1.','class' => '7','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '51'),
		array('id' => '461','mana' => '1','name' => 'Unleash the Hounds','attack' => '0','health' => '0','text' => 'Give your Beasts +1 Attack and <b>Charge</b>.','class' => '2','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '317'),
		array('id' => '462','mana' => '6','name' => 'Vanish','attack' => '0','health' => '0','text' => 'Return all minions to their owner\'s hand.','class' => '6','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '658'),
		array('id' => '463','mana' => '5','name' => 'Venture Co. Mercenary','attack' => '7','health' => '6','text' => 'Your minions cost (3) more.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '509'),
		array('id' => '464','mana' => '7','name' => 'War Golem','attack' => '7','health' => '7','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '323'),
		array('id' => '465','mana' => '2','name' => 'Warglaive of Azzinoth','attack' => '2','health' => '2','text' => '','class' => NULL,'type' => '2','rarity' => '2','race' => NULL,'pwn_id' => '494'),
		array('id' => '466','mana' => '4','name' => 'Water Elemental','attack' => '3','health' => '6','text' => '<b>Freeze</b> any character damaged by this minion.','class' => '3','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '274'),
		array('id' => '467','mana' => '1','name' => 'Whirlwind','attack' => '0','health' => '0','text' => 'Deal 1 damage to ALL minions.','class' => '9','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '161'),
		array('id' => '468','mana' => '3','name' => 'Will of Mukla','attack' => '0','health' => '0','text' => 'Restore 8 Health.','class' => NULL,'type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '586'),
		array('id' => '469','mana' => '6','name' => 'Windfury Harpy','attack' => '4','health' => '5','text' => '<b>Windfury</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '675'),
		array('id' => '470','mana' => '4','name' => 'Windspeaker','attack' => '3','health' => '3','text' => '<b>Battlecry:</b> Give a friendly minion <b>Windfury</b>.','class' => '7','type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '151'),
		array('id' => '471','mana' => '0','name' => 'Wisp','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '273'),
		array('id' => '472','mana' => '1','name' => 'Worgen Infiltrator','attack' => '2','health' => '1','text' => '<b>Stealth</b>','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '112'),
		array('id' => '473','mana' => '1','name' => 'Worthless Imp','attack' => '1','health' => '1','text' => '<i>You are out of demons! At least there are always imps...</i>','class' => '8','type' => '3','rarity' => '2','race' => '2','pwn_id' => '230'),
		array('id' => '474','mana' => '2','name' => 'Wrath','attack' => '0','health' => '0','text' => '<b>Choose One</b> - Deal 3 damage to a minion; or 1 damage and draw a card.','class' => '1','type' => '5','rarity' => '2','race' => NULL,'pwn_id' => '633'),
		array('id' => '475','mana' => '1','name' => 'Young Dragonhawk','attack' => '1','health' => '1','text' => '<b>Windfury</b>','class' => NULL,'type' => '3','rarity' => '2','race' => '1','pwn_id' => '629'),
		array('id' => '476','mana' => '2','name' => 'Youthful Brewmaster','attack' => '3','health' => '2','text' => '<b>Battlecry:</b> Return a friendly minion from the battlefield to your hand.','class' => NULL,'type' => '3','rarity' => '2','race' => NULL,'pwn_id' => '247'),
		array('id' => '477','mana' => '0','name' => 'Ancient Secrets','attack' => '0','health' => '0','text' => 'Restore 5 Health.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '243'),
		array('id' => '478','mana' => '0','name' => 'Ancient Teachings','attack' => '0','health' => '0','text' => 'Draw 2 cards.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '517'),
		array('id' => '479','mana' => '1','name' => 'Bananas','attack' => '0','health' => '0','text' => 'Give a minion +1/+1.','class' => NULL,'type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '231'),
		array('id' => '480','mana' => '1','name' => 'Battle Axe','attack' => '2','health' => '2','text' => '','class' => '9','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '403'),
		array('id' => '481','mana' => '3','name' => 'Blood Fury','attack' => '3','health' => '8','text' => '','class' => '8','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '669'),
		array('id' => '482','mana' => '0','name' => 'Chicken','attack' => '1','health' => '1','text' => '<i>Hey Chicken!</i>','class' => NULL,'type' => '3','rarity' => NULL,'race' => '1','pwn_id' => '552'),
		array('id' => '483','mana' => '1','name' => 'Defias Bandit','attack' => '2','health' => '1','text' => '','class' => '6','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '9'),
		array('id' => '484','mana' => '0','name' => 'Demigod\'s Favor','attack' => '0','health' => '0','text' => 'Give your other minions +2/+2.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '358'),
		array('id' => '485','mana' => '0','name' => 'Dispel','attack' => '0','health' => '0','text' => '<b>Silence</b> a minion.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '524'),
		array('id' => '486','mana' => '0','name' => 'Dream','attack' => '0','health' => '0','text' => 'Return a minion to its owner\'s hand.','class' => '10','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '561'),
		array('id' => '487','mana' => '4','name' => 'Emerald Drake','attack' => '7','health' => '6','text' => '','class' => '10','type' => '3','rarity' => NULL,'race' => '3','pwn_id' => '534'),
		array('id' => '488','mana' => '0','name' => 'Excess Mana','attack' => '0','health' => '0','text' => 'Draw a card. <i>(You can only have 10 Mana in your tray.)</i>','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '520'),
		array('id' => '489','mana' => '1','name' => 'Flame of Azzinoth','attack' => '2','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '685'),
		array('id' => '490','mana' => '2','name' => 'Flames of Azzinoth','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Summon two 2/1 minions.','class' => NULL,'type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '349'),
		array('id' => '491','mana' => '2','name' => 'Gnoll','attack' => '2','health' => '2','text' => 'Taunt','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '565'),
		array('id' => '492','mana' => '1','name' => 'Heavy Axe','attack' => '1','health' => '3','text' => '','class' => '9','type' => '2','rarity' => NULL,'race' => NULL,'pwn_id' => '583'),
		array('id' => '493','mana' => '0','name' => 'Illidan Stormrage','attack' => '0','health' => '30','text' => '','class' => '2','type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '223'),
		array('id' => '494','mana' => '2','name' => 'INFERNO!','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Summon a 6/6 Infernal.','class' => '8','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '83'),
		array('id' => '495','mana' => '3','name' => 'Laughing Sister','attack' => '3','health' => '5','text' => 'Can\'t be targeted by Spells or Hero Powers.','class' => '10','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '116'),
		array('id' => '496','mana' => '0','name' => 'Leader of the Pack','attack' => '0','health' => '0','text' => 'Give all of your minions +1/+1.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '204'),
		array('id' => '497','mana' => '0','name' => 'Lorewalker Cho','attack' => '0','health' => '25','text' => '','class' => NULL,'type' => '4','rarity' => NULL,'race' => NULL,'pwn_id' => '655'),
		array('id' => '498','mana' => '0','name' => 'Mark of Nature','attack' => '0','health' => '0','text' => '+4 Attack.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '430'),
		array('id' => '499','mana' => '0','name' => 'Mark of Nature','attack' => '0','health' => '0','text' => '+4 Health and <b>Taunt</b>.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '133'),
		array('id' => '500','mana' => '2','name' => 'Mind Shatter','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Deal 3 damage.','class' => '5','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '229'),
		array('id' => '501','mana' => '2','name' => 'Mind Spike','attack' => '0','health' => '0','text' => '<b>Hero Power</b> <br> Deal 2 damage.','class' => '5','type' => '6','rarity' => NULL,'race' => NULL,'pwn_id' => '70'),
		array('id' => '502','mana' => '0','name' => 'Moonfire','attack' => '0','health' => '0','text' => 'Deal 2 damage.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '111'),
		array('id' => '503','mana' => '0','name' => 'Nightmare','attack' => '0','health' => '0','text' => 'Give a minion +5/+5.  At the start of your next turn, destroy it.','class' => '10','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '334'),
		array('id' => '504','mana' => '2','name' => 'NOOOOOOOOOOOO','attack' => '0','health' => '0','text' => 'Somehow, the card you USED to have has been deleted.  Here, have this one instead!','class' => NULL,'type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '687'),
		array('id' => '505','mana' => '0','name' => 'Nourish','attack' => '0','health' => '0','text' => 'Gain 2 Mana Crystals.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '58'),
		array('id' => '506','mana' => '0','name' => 'Nourish','attack' => '0','health' => '0','text' => 'Draw 3 cards.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '485'),
		array('id' => '507','mana' => '0','name' => 'Rooted','attack' => '0','health' => '0','text' => '+5 Health and <b>Taunt</b>.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '375'),
		array('id' => '508','mana' => '0','name' => 'Shan\'do\'s Lesson','attack' => '0','health' => '0','text' => 'Summon two 2/2 Treants with <b>Taunt</b>.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '159'),
		array('id' => '509','mana' => '3','name' => 'Skeleton','attack' => '3','health' => '3','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '255'),
		array('id' => '510','mana' => '0','name' => 'Starfall','attack' => '0','health' => '0','text' => 'Deal 2 damage to all enemy minions.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '653'),
		array('id' => '511','mana' => '0','name' => 'Starfall','attack' => '0','health' => '0','text' => 'Deal 5 damage to a minion.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '195'),
		array('id' => '512','mana' => '0','name' => 'Summon a Panther','attack' => '0','health' => '0','text' => 'Summon a 3/2 Panther.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '219'),
		array('id' => '513','mana' => '0','name' => 'The Coin','attack' => '0','health' => '0','text' => 'Gain 1 Mana Crystal this turn only.','class' => NULL,'type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '141'),
		array('id' => '514','mana' => '1','name' => 'Treant','attack' => '2','health' => '2','text' => '<b>Taunt</b>','class' => '1','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '181'),
		array('id' => '515','mana' => '1','name' => 'Treant','attack' => '2','health' => '2','text' => '','class' => '1','type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '272'),
		array('id' => '516','mana' => '0','name' => 'Uproot','attack' => '0','health' => '0','text' => '+5 Attack.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '262'),
		array('id' => '517','mana' => '0','name' => 'Violet Apprentice','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => NULL,'pwn_id' => '63'),
		array('id' => '518','mana' => '1','name' => 'Whelp','attack' => '1','health' => '1','text' => '','class' => NULL,'type' => '3','rarity' => NULL,'race' => '3','pwn_id' => '527'),
		array('id' => '519','mana' => '0','name' => 'Wrath','attack' => '0','health' => '0','text' => 'Deal 3 damage to a minion.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '234'),
		array('id' => '520','mana' => '0','name' => 'Wrath','attack' => '0','health' => '0','text' => 'Deal 1 damage to a minion. Draw a card.','class' => '1','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '501'),
		array('id' => '521','mana' => '2','name' => 'Ysera Awakens','attack' => '0','health' => '0','text' => 'Deal 5 damage to all characters except Ysera.','class' => '10','type' => '5','rarity' => NULL,'race' => NULL,'pwn_id' => '235'),
	);

}
