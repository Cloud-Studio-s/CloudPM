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

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\item\Item;
use pocketmine\world\Position;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

/**
 * Called when a player interacts or touches a block.
 * This is called for both left click (start break) and right click (use).
 */
class PlayerInteractEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public const LEFT_CLICK_BLOCK = 0;
	public const RIGHT_CLICK_BLOCK = 1;

	protected Vector3 $touchVector;

	protected bool $useItem = true;
	protected bool $useBlock = true;

	public function __construct(
		Player $player,
		protected Item $item,
		protected Block $blockTouched,
		?Vector3 $touchVector,
		protected int $blockFace,
		protected int $action = PlayerInteractEvent::RIGHT_CLICK_BLOCK
	){
		$this->player = $player;
		$this->touchVector = $touchVector ?? Vector3::zero();
	}

	public function getAction() : int{
		return $this->action;
	}

	public function getItem() : Item{
		return clone $this->item;
	}

	public function getBlock() : Block{
		return $this->blockTouched;
	}

	public function getTouchVector() : Vector3{
		return $this->touchVector;
	}

	public function getFace() : int{
		return $this->blockFace;
	}

	/**
	 * Returns whether the item may react to the interaction. If disabled, items such as spawn eggs will not activate.
	 * This does NOT prevent blocks from being placed - it makes the item behave as if the player is sneaking.
	 */
	public function useItem() : bool{ return $this->useItem; }

	/**
	 * Sets whether the used item may react to the interaction. If false, items such as spawn eggs will not activate.
	 * This does NOT prevent blocks from being placed - it makes the item behave as if the player is sneaking.
	 */
	public function setUseItem(bool $useItem) : void{ $this->useItem = $useItem; }

	/**
	 * Returns whether the block may react to the interaction. If false, doors, fence gates and trapdoors will not
	 * respond, containers will not open, etc.
	 */
	public function useBlock() : bool{ return $this->useBlock; }

	/**
	 * Sets whether the block may react to the interaction. If false, doors, fence gates and trapdoors will not
	 * respond, containers will not open, etc.
	 */
	public function setUseBlock(bool $useBlock) : void{ $this->useBlock = $useBlock; }

	/**
	 * Returns whether this interaction was a left click on a block.
	 */
	public function isLeftClick() : bool{
		return $this->action === self::LEFT_CLICK_BLOCK;
	}

	/**
	 * Returns whether this interaction was a right click on a block.
	 */
	public function isRightClick() : bool{
		return $this->action === self::RIGHT_CLICK_BLOCK;
	}

	/**
	 * Returns the position of the block being interacted with.
	 */
	public function getBlockPosition() : Position{
		return $this->blockTouched->getPosition();
	}

	/**
	 * Returns whether the used item is currently allowed to react
	 * to this interaction (same as useItem()).
	 */
	public function isItemUseAllowed() : bool{
		return $this->useItem;
	}

	/**
	 * Returns whether the block is currently allowed to react
	 * to this interaction (same as useBlock()).
	 */
	public function isBlockUseAllowed() : bool{
		return $this->useBlock;
	}

	/**
	 * Returns true if only the item is allowed to react, and the
	 * block interaction is disabled.
	 */
	public function isItemOnlyInteraction() : bool{
		return $this->useItem && !$this->useBlock;
	}

	/**
	 * Returns true if only the block is allowed to react, and the
	 * item interaction is disabled.
	 */
	public function isBlockOnlyInteraction() : bool{
		return $this->useBlock && !$this->useItem;
	}
}
