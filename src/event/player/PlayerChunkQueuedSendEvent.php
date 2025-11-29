<?php

namespace pocketmine\event\player;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

class PlayerChunkQueuedSendEvent extends PlayerEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(Player $player, private int $chunkX, private int $chunkZ, private int $priority){
		$this->player = $player;
	}

	public function getChunkX() : int{
		return $this->chunkX;
	}

	public function getChunkZ() : int{
		return $this->chunkZ;
	}

	public function getPriority() : int{
		return $this->priority;
	}

	public function setPriority(int $priority) : void{
		$this->priority = $priority;
	}
}