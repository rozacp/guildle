<?php namespace Guildle\Harvester;

// use Guildle\User;
use Illuminate\Support\Facades\Auth;
use Guildle\Harvester\Harvester;
use Guildle\Character;
use Guildle\Talent;
use Guildle\Glyph;
use Guildle\Audit;
use Guildle\Raid;
use Guildle\Boss;
use Guildle\Progression;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;


class ChartoDb
{
	public function __construct(SocialiteFactory $socialite)
	{
		$this->socialite = $socialite;
	}

	public function saveNewCharacters($user)
	{
		$user->characters()->delete();

		$zones = ['eu', 'us', 'kr', 'tw'];

		$characters = [];

		foreach ($zones as $zone)
		{
			$zonechars = $this->socialite->driver('battlenet')->getChars($zone, $user->access_token);

			foreach ($zonechars as $chars)
			{
				$chars['zone'] = $zone;
				$characters[] = $chars;
			}
		}

		foreach($characters as $character)
		{
			if ($character['level'] > 10)
			{
				$char = new Character;
				$char = $char->fill($character);
				$user->characters()->save($char);
			}
		}
	}

	public function updateNewCharacters($user)
	{
		$characters = $user->characters()->get();

		$characters->each(function($character) {

			$harvester = new Harvester;
			$harvester->setParams($character->zone, $character->realm, $character->name);

			if ($harvester->isValidCharacter())
			{
				// if ($character->lastmodified != $harvester->character()['lastmodified'])
				// {

				// }

				// character
				$character->fill($harvester->character());
				$character->save();

				// audit
				$audit = $harvester->audit();

				Audit::updateOrCreate(
					[
						'character_id' => $character->id
					],
					$audit
				);

				// gear
				$gear = $harvester->gear();

				foreach ($gear as $slot => $stats)
				{
					$item = '\Guildle\\' . ucfirst($slot);

					$item::updateOrCreate(
						[
							'character_id' => $character->id,
							'item_id' => $stats['item_id']
						],
						$stats
					);
				}

				// talents
				$talents = $harvester->talents();

				foreach ($talents as $talent)
				{
					Talent::updateOrCreate(
						[
							'character_id' => $character->id,
							'talent_id' => $talent['talent_id']
						],
						$talent
					);
				}

				// glyphs
				$glyphs = $harvester->glyphs();

				foreach ($glyphs as $glyph)
				{
					Glyph::updateOrCreate(
						[
							'character_id' => $character->id,
							'glyph_id' => $glyph['glyph_id']
						],
						$glyph
					);
				}

				// progression
				$progression = $harvester->progression();

				foreach ($progression as $raid => $boss)
				{
					$raid = Raid::updateOrCreate(
						[
							'raid' => $raid
						]
					);

					foreach ($boss as $boss)
					{
						$bossid = Boss::updateOrCreate(
							[
								'raids_id' => $raid->id,
								'boss' => $boss['name']
							]
						);

						Progression::updateOrCreate(
							[
								'character_id' => $character->id,
								'raids_id' => $raid->id,
								'bosses_id' => $bossid->id
							],
							[
								'lrf' => isset($boss['lfrKills']) ? $boss['lfrKills'] : 0,
								'normal' => isset($boss['normalKills']) ? $boss['normalKills'] : 0,
								'heroic' => isset($boss['heroicKills']) ? $boss['heroicKills'] : 0
							]
						);
					}
				}
			}
			else
			{
				$character->delete();
			}
		});
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
		}
	}
}
