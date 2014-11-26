<?php namespace Guildle\Harvester;

use Cache;

class Harvester
{
	private $charname;
	private $realm;
	private $zone;
	private $baseurl;
	private $client_id;
	private $chardata;

	public function setParams($zone, $realm, $charname)
	{
		$this->charname = $charname;
		$this->realm = str_replace(' ', '-', $realm);
		$this->zone = $zone;
		$this->baseurl = 'https://' . $zone . '.api.battle.net/wow/';
		$this->client_id = $_ENV['CLIENT_ID'];
		$this->chardata = $this->chardata();
	}

	private function cacheApi($url, $key, $time)
	{
		if (Cache::has($key)) {
			$apiResult = Cache::get($key);
		} else {
			$apiResult = @json_decode(file_get_contents($url), TRUE);
			Cache::put($key, $apiResult, $time);
		}

		return $apiResult ?: FALSE;
	}

	public function charData()
	{
		$url = $this->baseurl . 'character/' . $this->realm . '/' . $this->charname . '?fields=talents,items,progression,professions,audit,stats&apikey=' . $this->client_id;
		$key = $this->charname . $this->realm . $this->zone;

		return $this->cacheApi($url, $key, 10);
	}

	private function queryGear($slot)
	{
		$forg = isset($this->chardata['items'][$slot]['tooltipParams']['reforge']) ? $this->chardata['items'][$slot]['tooltipParams']['reforge'] : NULL;

		$upgd = isset($this->chardata['items'][$slot]['tooltipParams']['upgrade']['current']) ? $this->chardata['items'][$slot]['tooltipParams']['upgrade']['current'] : NULL;

		$sock = isset($this->chardata['items'][$slot]['tooltipParams']['extraSocket']) ? TRUE : FALSE;

		$ench = isset($this->chardata['items'][$slot]['tooltipParams']['enchant']) ? $this->chardata['items'][$slot]['tooltipParams']['enchant'] : NULL;

		$pcs = isset($this->chardata['items'][$slot]['tooltipParams']['set']) ? implode(':', $this->chardata['items'][$slot]['tooltipParams']['set']) : NULL;

		$rand = isset($this->chardata['items'][$slot]['tooltipParams']['suffix']) ? $this->chardata['items'][$slot]['tooltipParams']['suffix'] : NULL;

		if (isset($this->chardata['items'][$slot]['tooltipParams']['gem0']))
		{
			foreach ($this->chardata['items'][$slot]['tooltipParams'] as $key => $value)
			{
				if (preg_match('/^gem/', $key))
				{
					$allgems[] = $value;
				}
			}
			$gems = implode(':', $allgems);
		}
		else {
			$gems = NULL;
		}

		return [
			'item_id' => $this->chardata['items'][$slot]['id'],
			'item_name' => $this->chardata['items'][$slot]['name'],
			'item_icon' => $this->chardata['items'][$slot]['icon'],
			'forg' => $forg,
			'upgd' => $upgd,
			'sock' => $sock,
			'ench' => $ench,
			'pcs' => $pcs,
			'rand' => $rand,
			'gems' => $gems
		];
	}

	private function charRace()
	{
		$url = $this->baseurl . 'data/character/races?locale=en_GB&apikey=' . $this->client_id;
		$key = 'race';

		$racesQuery = $this->cacheApi($url, $key, 360);

		foreach ($racesQuery['races'] as $race)
		{
			if ($this->chardata['race'] == $race['id'])
			{
				$race_name = $race['name'];
				$faction = $race['side'];
			}
		}

		return [$race_name, $faction];
	}

	private function charClass()
	{
		$url = $this->baseurl . 'data/character/classes?locale=en_GB&apikey=' . $this->client_id;
		$key = 'class';

		$classesQuery = $this->cacheApi($url, $key, 360);

		foreach ($classesQuery['classes'] as $class)
		{
			if ($this->chardata['class'] == $class['id'])
			{
				return $class['name'];
			}
		}
	}

	private function avatar()
	{
		return 'http://' . $this->zone . '.battle.net/static-render/' . $this->zone . '/' . $this->chardata['thumbnail'];
	}

	private function avatarBig()
	{
		return str_replace('avatar', 'profilemain', $this->avatar());
	}

	private function armory()
	{
		return 'http://' . $this->zone . '.battle.net/wow/en/character/' . $this->realm . '/' . $this->charname . '/advanced';
	}

	private function specFirst()
	{
		return isset($this->chardata['talents'][0]['spec']['name']) ? $this->chardata['talents'][0]['spec']['name'] : NULL;
	}

	private function specSecond()
	{
		return isset($this->chardata['talents'][1]['spec']['name']) ? $this->chardata['talents'][1]['spec']['name'] : NULL;
	}

	private function specActive()
	{
		if (isset($this->chardata['talents'][0]['selected']))
		{
			return $this->specFirst();
		}
		else if (isset($this->chardata['talents'][1]['selected']))
		{
			return $this->specSecond();
		}
		else
		{
			return NULL;
		}
	}

	/** RETREIVABLE CHAR DATA **/

	public function isValidCharacter()
	{
		return $this->chardata ? TRUE : FALSE;
	}

	public function character()
	{
		return
		[
			'faction' => $this->charRace()['1'],
			'char_race' => $this->charRace()['0'],
			'char_class' => $this->charClass(),
			'specfirst' => $this->specFirst(),
			'specsecond' => $this->specSecond(),
			'specactive' => $this->specActive(),
			'profnamefirst' => isset($this->chardata['professions']['primary'][1]['name']) ? $this->chardata['professions']['primary'][1]['name'] : NULL,
			'profrankfirst' => isset($this->chardata['professions']['primary'][1]['rank']) ? $this->chardata['professions']['primary'][1]['rank'] : NULL,
			'profnamesecond' => isset($this->chardata['professions']['primary'][0]['name']) ? $this->chardata['professions']['primary'][0]['name'] : NULL,
			'profranksecond' => isset($this->chardata['professions']['primary'][0]['rank']) ? $this->chardata['professions']['primary'][0]['rank'] : NULL,
			'ilevel' => $this->chardata['items']['averageItemLevelEquipped'],
			'health' => $this->chardata['stats']['health'],
			'str' => $this->chardata['stats']['str'],
			'agi' => $this->chardata['stats']['agi'],
			'int' => $this->chardata['stats']['int'],
			'spr' => $this->chardata['stats']['spr'],
			'spellPower' => $this->chardata['stats']['spellPower'],
			'attackPower' => $this->chardata['stats']['attackPower'],
			'crit' => round($this->chardata['stats']['crit'], 2),
			'haste' => round($this->chardata['stats']['haste'], 2),
			'mastery' => round($this->chardata['stats']['mastery'], 2),
			'bonusArmor' => $this->chardata['stats']['bonusArmor'],
			'multistrike' => round($this->chardata['stats']['multistrike'], 2),
			'leech' => round($this->chardata['stats']['leech'], 2),
			'versatility' => round($this->chardata['stats']['versatilityDamageDoneBonus'], 2),
			'avoidance' => round($this->chardata['stats']['avoidanceRatingBonus'], 2),
			'armor' => $this->chardata['stats']['armor'],
			'dodge' => round($this->chardata['stats']['dodge'], 2),
			'parry' => round($this->chardata['stats']['parry'], 2),
			'block' => round($this->chardata['stats']['block'], 2),
			'avatar' => $this->avatar(),
			'avatarbig' => $this->avatarBig(),
			'armory' => $this->armory(),
			'lastmodified' => $this->chardata['lastModified']
		];
	}

	public function talents()
	{
		$talents = [];

		foreach ($this->chardata['talents'][0]['talents'] as $talent)
		{
			$talents[] =
			[
				'spec' => 0,
				'tier' => $talent['tier'],
				'talent_id' => $talent['spell']['id'],
				'talent_name' => $talent['spell']['name'],
				'talent_icon' => $talent['spell']['icon']
			];
		}

		foreach ($this->chardata['talents'][1]['talents'] as $talent)
		{
			$talents[] =
			[
				'spec' => 1,
				'tier' => $talent['tier'],
				'talent_id' => $talent['spell']['id'],
				'talent_name' => $talent['spell']['name'],
				'talent_icon' => $talent['spell']['icon']
			];
		}

		return $talents;
	}

	public function glyphs()
	{
		$glyphs = [];

		foreach ($this->chardata['talents'][0]['glyphs']['major'] as $glyph)
		{
			$glyphs[] =
			[
				'spec' => 0,
				'type' => 1,
				'glyph_id' => $glyph['item'],
				'glyph_name' => $glyph['name'],
				'glyph_icon' => $glyph['icon']
			];
		}

		foreach ($this->chardata['talents'][0]['glyphs']['minor'] as $glyph)
		{
			$glyphs[] =
			[
				'spec' => 0,
				'type' => 0,
				'glyph_id' => $glyph['item'],
				'glyph_name' => $glyph['name'],
				'glyph_icon' => $glyph['icon']
			];
		}

		foreach ($this->chardata['talents'][1]['glyphs']['major'] as $glyph)
		{
			$glyphs[] =
			[
				'spec' => 1,
				'type' => 1,
				'glyph_id' => $glyph['item'],
				'glyph_name' => $glyph['name'],
				'glyph_icon' => $glyph['icon']
			];
		}

		foreach ($this->chardata['talents'][1]['glyphs']['minor'] as $glyph)
		{
			$glyphs[] =
			[
				'spec' => 1,
				'type' => 0,
				'glyph_id' => $glyph['item'],
				'glyph_name' => $glyph['name'],
				'glyph_icon' => $glyph['icon']
			];
		}

		return $glyphs;
	}

	public function progression()
	{
		$raids_json = $this->chardata['progression']['raids'];

		for ($i = 27 ; $i < sizeof($raids_json) ; $i++)
		{
			$allraids[$raids_json[$i]['name']] = $raids_json[$i]['bosses'];
		}

		return $allraids;
	}

	public function gear()
	{
		foreach ($this->chardata['items'] as $key => $value)
		{
			$gear_slots[] = $key;
		}

		$gear_slots = array_values(array_diff($gear_slots, ['averageItemLevel', 'averageItemLevelEquipped']));

		foreach ($gear_slots as $slot)
		{
			$gear[$slot] = $this->queryGear($slot);
		}

		return $gear;
	}

	public function audit()
	{
		return
		[
			'issues' => $this->chardata['audit']['numberOfIssues'],
			'empty_glyphs' => $this->chardata['audit']['emptyGlyphSlots'],
			'unspent_talents' => $this->chardata['audit']['unspentTalentPoints'],
			'unenchanted_items' => sizeof($this->chardata['audit']['unenchantedItems']),
			'empty_sockets' => $this->chardata['audit']['emptySockets'],
			'inappropriate_armor' => sizeof($this->chardata['audit']['inappropriateArmorType']),
			'no_extra_sockets' => sizeof($this->chardata['audit']['missingExtraSockets']),
			'no_blacksmith_sockets' => sizeof($this->chardata['audit']['missingBlacksmithSockets']),
			'no_enchanter_enchants' => sizeof($this->chardata['audit']['missingEnchanterEnchants']),
			'no_engineer_enchants' => sizeof($this->chardata['audit']['missingEngineerEnchants']),
			'no_scribe_enchants' => sizeof($this->chardata['audit']['missingScribeEnchants']),
			'no_jewelcrafter_gems' => $this->chardata['audit']['nMissingJewelcrafterGems'],
			'no_leatherworker_enchants' => sizeof($this->chardata['audit']['missingLeatherworkerEnchants'])
		];
	}
}
