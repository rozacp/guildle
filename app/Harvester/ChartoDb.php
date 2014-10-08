<?php namespace Guildle\Harvester;

use Guildle\Character;
use Guildle\Talent;
use Guildle\Glyph;
use Guildle\Audit;
use Guildle\Raid;
use Guildle\Boss;
use Guildle\Progression;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Guildle\Harvester\Harvester as Harvester;
use Carbon\Carbon;

class ChartoDb
{
	public function __construct(SocialiteFactory $socialite, Harvester $harvester)
	{
		$this->socialite = $socialite;
		$this->harvester = $harvester;
	}

	public function saveCharacters($user)
	{
		$zones = ['eu', 'us', 'kr', 'tw'];

		foreach ($zones as $zone)
		{
			$characters = $this->socialite->driver('battlenet')->getChars($zone, $user->access_token);

			foreach ($characters as $character)
			{
				$character['zone'] = $zone;

				if ($character['level'] > 10)
				{
					$char = Character::where('zone', $zone)
						->where('realm', $character['realm'])
						->where('name', $character['name'])
						->first() ?: new Character;

					$char = $char->fill($character);
					$user->characters()->save($char);
				}
			}
		}
	}

	public function updateCharacters($user)
	{
		$characters = $user->characters()->get();

		return $this->writeCharacters($characters);
	}

	public function periodicallyUpdateCharacters()
	{
		$characters = Character::where('updated_at', '<', Carbon::now()->subHours(2))->get();

		return $this->writeCharacters($characters);
	}

	private function writeCharacters($characters)
	{
		$characters->each(function($character) {

			$this->harvester->setParams($character->zone, $character->realm, $character->name);

			if ($this->harvester->isValidCharacter())
			{
				$chardata = $this->harvester->character();

				if ($character->lastmodified != $chardata['lastmodified'])
				{
					// character
					$character->fill($chardata);
					$character->save();

					// audit
					$audit = $this->harvester->audit();

					Audit::updateOrCreate(
						[
							'character_id' => $character->id
						],
						$audit
					);

					// gear
					$gear = $this->harvester->gear();

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
					$talents = $this->harvester->talents();

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
					$glyphs = $this->harvester->glyphs();

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
					$progression = $this->harvester->progression();

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
}
