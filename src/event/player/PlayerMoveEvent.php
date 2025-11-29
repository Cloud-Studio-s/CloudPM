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

use pocketmine\entity\Location;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class PlayerMoveEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Player $player,
		private Location $from,
		private Location $to
	){
		$this->player = $player;
	}

	public function getFrom() : Location{
		return $this->from;
	}

	public function getTo() : Location{
		return $this->to;
	}

	public function setTo(Location $to) : void{
		Utils::checkLocationNotInfOrNaN($to);
		$this->to = $to;
	}

	/**
	 * Returns the horizontal delta X between from and to.
	 */
	public function getDeltaX() : float{
		return $this->to->x - $this->from->x;
	}

	/**
	 * Returns the vertical delta Y between from and to.
	 */
	public function getDeltaY() : float{
		return $this->to->y - $this->from->y;
	}

	/**
	 * Returns the horizontal delta Z between from and to.
	 */
	public function getDeltaZ() : float{
		return $this->to->z - $this->from->z;
	}

	/**
	 * Returns the squared 3D distance between from and to.
	 */
	public function getDistanceSquared() : float{
		return $this->from->distanceSquared($this->to);
	}

	/**
	 * Returns the squared horizontal (XZ) distance between from and to.
	 */
	public function getHorizontalDistanceSquared() : float{
		$dx = $this->getDeltaX();
		$dz = $this->getDeltaZ();
		return $dx * $dx + $dz * $dz;
	}

	/**
	 * Returns the yaw difference (toYaw - fromYaw).
	 */
	public function getDeltaYaw() : float{
		return $this->to->yaw - $this->from->yaw;
	}

	/**
	 * Returns the pitch difference (toPitch - fromPitch).
	 */
	public function getDeltaPitch() : float{
		return $this->to->pitch - $this->from->pitch;
	}

	/**
	 * Returns whether the position (x, y, z) changed between from and to.
	 */
	public function hasPositionChanged() : bool{
		return $this->getDistanceSquared() > 0.0;
	}

	/**
	 * Returns whether the rotation (yaw, pitch) changed between from and to.
	 */
	public function hasRotationChanged() : bool{
		return $this->getDeltaYaw() != 0.0 || $this->getDeltaPitch() != 0.0;
	}

	/**
	 * Returns true if only rotation changed (no position change).
	 */
	public function isRotationOnly() : bool{
		return $this->hasRotationChanged() && !$this->hasPositionChanged();
	}
}
