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
				$chardata = $harvester->character();

				if ($character->lastmodified == $chardata['lastmodified']) // CHANGE TO NOT EQUAL
				{
					// character
					$character->fill($chardata);
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
								'talent_id' => $talent['talent_id'],
								'spec' => $talent['spec'],
								'tier' => $talent['tier']
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
								'glyph_id' => $glyph['glyph_id'],
								'spec' => $glyph['spec'],
								'type' => $glyph['type']
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
			}
			else
			{
				$character->delete();
			}
		});
	}

	public function periodic()
	{
		$characters = Character::where('updated_at', '<', Carbon::now()->subHours(2))->get();

	}

	public function update($user_id)
	{
		//
	}
}
