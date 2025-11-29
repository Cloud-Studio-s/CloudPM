<?php

declare(strict_types=1);

/**
 *       _____ _                     _ _____  __  __
 *      / ____| |                   | |  __ \|  \/  |
 *     | |    | |     ___  _   _  __| | |__) | \  / |
 *     | |    | |    / _ \| | | |/ _` |  ___/| |\/| |
 *     | |____| |___| (_) | |_| | (_| | |    | |  | |
 *      \_____|______\___/ \__,_|\__,_|_|    |_|  |_|
 *
 * CloudPM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Cloud Studio`s
 */

namespace pocketmine\player;

use function abs;
use function uasort;

final class ChunkSendQueue{

	/** @var array<string, array{int,int,int}> key => [chunkX, chunkZ, priority] */
	private array $queue = [];

	public function __construct(
		private Player $player
	){}

	public function queueChunk(int $chunkX, int $chunkZ, int $priority) : void{
		$key = $chunkX . ':' . $chunkZ;

		if(isset($this->queue[$key])){
			if($priority < $this->queue[$key][2]){
				$this->queue[$key][2] = $priority;
			}
			return;
		}
		$this->queue[$key] = [$chunkX, $chunkZ, $priority];
	}

	public function process(int $maxPerTick) : void{
		if($maxPerTick <= 0 || $this->queue === []){
			return;
		}

		uasort($this->queue, static function(array $a, array $b) : int{
			return $a[2] <=> $b[2];
		});

		$sent = 0;
		$world = $this->player->getWorld();

		foreach($this->queue as $key => [$chunkX, $chunkZ, $priority]){
			if(!$this->player->isOnline()){
				$this->queue = [];
				break;
			}

			if(!$world->isChunkLoaded($chunkX, $chunkZ)){
				continue;
			}

			if(!$this->isChunkStillRelevant($chunkX, $chunkZ)){
				unset($this->queue[$key]);
				continue;
			}

			$this->player->sendChunkNow($chunkX, $chunkZ);
			unset($this->queue[$key]);

			if(++$sent >= $maxPerTick){
				break;
			}
		}
	}

	private function isChunkStillRelevant(int $chunkX, int $chunkZ) : bool{
		$playerPos = $this->player->getPosition();
		$px = $playerPos->getFloorX() >> 4;
		$pz = $playerPos->getFloorZ() >> 4;
		$view = $this->player->getViewDistance();

		if($view < 0){
			return true;
		}

		return abs($chunkX - $px) <= $view && abs($chunkZ - $pz) <= $view;
	}

	public function clear() : void{
		$this->queue = [];
	}
}