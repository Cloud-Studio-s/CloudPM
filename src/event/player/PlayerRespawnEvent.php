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

use pocketmine\player\Player;
use pocketmine\utils\Utils;
use pocketmine\world\Position;
use pocketmine\world\World;

/**
 * Called when a player is respawned
 */
class PlayerRespawnEvent extends PlayerEvent{
	public function __construct(
		Player $player,
		protected Position $position
	){
		$this->player = $player;
	}

	public function getRespawnPosition() : Position{
		return $this->position;
	}

	public function setRespawnPosition(Position $position) : void{
		if(!$position->isValid()){
			throw new \InvalidArgumentException("Spawn position must reference a valid and loaded World");
		}
		Utils::checkVector3NotInfOrNaN($position);
		$this->position = $position;
	}

	/**
	 * Returns the world the player will respawn in.
	 */
	public function getRespawnWorld() : World{
		return $this->position->getWorld();
	}

	/**
	 * Returns the respawn X coordinate (floored).
	 */
	public function getRespawnX() : int{
		return $this->position->getFloorX();
	}

	/**
	 * Returns the respawn Y coordinate (floored).
	 */
	public function getRespawnY() : int{
		return $this->position->getFloorY();
	}

	/**
	 * Returns the respawn Z coordinate (floored).
	 */
	public function getRespawnZ() : int{
		return $this->position->getFloorZ();
	}

	/**
	 * Returns whether the respawn world is the server's default world.
	 */
	public function isRespawnInDefaultWorld() : bool{
		$world = $this->position->getWorld();
		$default = $world->getServer()->getWorldManager()->getDefaultWorld();
		return $default !== null && $default === $world;
	}

	/**
	 * Returns whether the respawn world is different from the server's default world.
	 */
	public function isRespawnInCustomWorld() : bool{
		return !$this->isRespawnInDefaultWorld();
	}
}