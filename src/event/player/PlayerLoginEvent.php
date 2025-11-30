<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

/**
 * Called after the player has successfully authenticated, before it spawns. The player is on the loading screen when
 * this is called.
 * Cancelling this event will cause the player to be disconnected with the kick message set.
 */
class PlayerLoginEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		protected Translatable|string $kickMessage
	){
		$this->player = $player;
	}

	public function setKickMessage(Translatable|string $kickMessage) : void{
		$this->kickMessage = $kickMessage;
	}

	public function getKickMessage() : Translatable|string{
		return $this->kickMessage;
	}

	/**
	 * Returns true if there is a non-empty kick message configured.
	 */
	public function hasKickMessage() : bool{
		return $this->kickMessage !== "";
	}

	/**
	 * Clears the kick message. If the event is cancelled with an empty message,
	 * the server will usually fall back to a generic disconnect message.
	 */
	public function clearKickMessage() : void{
		$this->kickMessage = "";
	}

	/**
	 * Returns the kick message translated to the server language as a plain string.
	 */
	public function getFormattedKickMessage() : string{
		if($this->kickMessage instanceof Translatable){
			return $this->player->getLanguage()->translate($this->kickMessage);
		}
		return (string) $this->kickMessage;
	}

	/**
	 * Convenience method: sets the kick message and cancels the event,
	 * denying the player's login.
	 */
	public function deny(Translatable|string $kickMessage) : void{
		$this->kickMessage = $kickMessage;
		$this->cancel();
	}
}