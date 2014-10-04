<?php namespace Guildle\Harvester;

use Guildle\User;
use Illuminate\Support\Facades\Auth;
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
	private $user;
	private $socialite;
	private $harvester;

	public function __construct(SocialiteFactory $socialite, Harvester $harvester)
	{
		$this->socialite = $socialite;
		$this->harvester = $harvester;
		$this->user = Auth::user();
	}

	public function saveNewCharacters()
	{
		// $this->user->characters()->delete();
		dd($this->user);

		$zones = ['eu', 'us', 'kr', 'tw'];

		$characters = [];

		foreach ($zones as $zone)
		{
			$zonechars = $this->socialite->driver('battlenet')->getChars($zone, $this->user->access_token);

			foreach ($zonechars as $chars)
			{
				$chars['zone'] = $zone;
				$characters[] = $chars;
			}
		}

		foreach($characters as $character)
		{
			if ($character['level'] > 60)
			{
				$char = new Character;
				$char = $char->fill($character);
				$this->user->characters()->save($char);
			}
		}
	}

	public function updateNewCharacters()
	{
		$characters = $this->user->characters()->get();

		$characters->each(function($char) {
			var_dump($char->name);
		});



		// $characters = User::find($user_id)->characters()->get();


		// {
		// 	$this->update($character->zone, $character->realm, $character->name, $user_id);
		// }
	}

	public function periodic()
	{
		// periodically updates characters

		$characters = Character::where('lastmodified', '>', '')->get();
	}

	public function update($zone, $realm, $character, $user_id)
	{


		// $harvester = new Harvester;
		$harvester->setParams($zone, $realm, $character);

		foreach ($characters as $character)
		{

		}

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
