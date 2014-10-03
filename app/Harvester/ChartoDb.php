<?php namespace Guildle\Harvester;

use Guildle\User;
use Guildle\Character;
use Guildle\Talent;
use Guildle\Glyph;
use Guildle\Audit;
use Guildle\Raid;
use Guildle\Boss;
use Guildle\Progression;

class ChartoDb
{
	public function postAuth()
	{
		// is called after auth, enters new characters
	}

	public function postLogin($user_id)
	{
		// is called after login, updates characters

		$characters = User::find($user_id)->characters()->get();

		foreach ($characters as $character)
		{
			$this->update($character->zone, $character->realm, $character->name, $user_id);
		}
	}

	public function periodic()
	{
		// periodically updates characters

		$characters = Character::where('lastmodified', '>', '')->get();
	}

	public function update($zone, $realm, $character, $user_id)
	{
		$harvester = new Harvester;
		$harvester->setParams($zone, $realm, $character);


		// $character = Character::where('zone', '=', $character->zone)->where(-....-> first();
		// if($character == null) {
		// }
		// create new Character(
		// else
		// {
		// 	updateCharacter;
		// }
		// $character = $character->fill($harvester->character());
		// $character->user_id = 13;
		// $character->save();

		if ($harvester->isValidCharacter())
		{
			// character
			$character_arr = $harvester->character();
			$character_arr['user_id'] = $user_id;
			$character = Character::updateOrCreate($character_arr);

			// talents
			$talents = $harvester->talents();

			foreach ($talents as $talent)
			{
				$talent['character_id'] = $character->id;
				Talent::updateOrCreate($talent);
			}

			// glyphs
			$glyphs = $harvester->glyphs();

			foreach ($glyphs as $glyph)
			{
				$glyph['character_id'] = $character->id;
				Glyph::updateOrCreate($glyph);
			}

			// audit
			$audit = $harvester->audit();
			$audit['character_id'] = $character->id;
			Audit::updateOrCreate($audit);

			// gear
			$gear = $harvester->gear();

			foreach ($gear as $slot => $values)
			{
				$values['character_id'] = $character->id;
				$item = 'Guildle\\' . ucfirst($slot);
				$item::updateOrCreate($values);
			}

			// progression
			$progression = $harvester->progression();

			foreach ($progression as $raid => $boss)
			{
				$raid = Raid::updateOrCreate([
					'raid' => $raid
					]);

				foreach ($boss as $boss)
				{
					$bossid = Boss::updateOrCreate([
						'raids_id' => $raid->id,
						'boss' => $boss['name']
						]);

					Progression::updateOrCreate([
						'character_id' => $character->id,
						'raids_id' => $raid->id,
						'bosses_id' => $bossid->id,
						'lrf' => isset($boss['lfrKills']) ? $boss['lfrKills'] : 0,
						'normal' => isset($boss['normalKills']) ? $boss['normalKills'] : 0,
						'heroic' => isset($boss['heroicKills']) ? $boss['heroicKills'] : 0
						]);
				}
			}
			echo('ok' . '<br>');
		}
		else
		{
			echo('not ok' . '<br>');
		}
	}
}
