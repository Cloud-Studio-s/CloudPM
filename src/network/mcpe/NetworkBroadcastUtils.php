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

namespace pocketmine\network\mcpe;

use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\player\Player;
use pocketmine\timings\Timings;
use function count;
use function spl_object_id;

final class NetworkBroadcastUtils{

	private function __construct(){
		//NOOP
	}

	/**
	 * @param Player[]            $recipients
	 * @param ClientboundPacket[] $packets
	 */
	/**
	 * @param Player[]            $recipients
	 * @param ClientboundPacket[] $packets
	 */
	public static function broadcastPackets(array $recipients, array $packets) : bool{
		if($packets === []){
			throw new \InvalidArgumentException("Cannot broadcast empty list of packets");
		}
		if($recipients === []){
			return false;
		}

		return Timings::$broadcastPackets->time(function() use ($recipients, $packets) : bool{
			/** @var PacketBroadcaster[] $uniqueBroadcasters */
			$uniqueBroadcasters = [];
			/** @var NetworkSession[][] $broadcasterTargets */
			$broadcasterTargets = [];

			foreach($recipients as $player){
				if(!$player->isConnected()){
					continue;
				}
				$session = $player->getNetworkSession();
				$broadcaster = $session->getBroadcaster();

				$bid = spl_object_id($broadcaster);
				$sid = spl_object_id($session);

				$uniqueBroadcasters[$bid] = $broadcaster;
				$broadcasterTargets[$bid][$sid] = $session;
			}

			if($uniqueBroadcasters === []){
				return false;
			}

			foreach($uniqueBroadcasters as $bid => $broadcaster){
				$broadcaster->broadcastPackets($broadcasterTargets[$bid], $packets);
			}

			return true;
		});
	}

	/**
	 * @param Player[] $recipients
	 * @phpstan-param \Closure(EntityEventBroadcaster, array<int, NetworkSession>) : void $callback
	 */
	/**
	 * @param Player[] $recipients
	 * @phpstan-param \Closure(EntityEventBroadcaster, array<int, NetworkSession>) : void $callback
	 */
	public static function broadcastEntityEvent(array $recipients, \Closure $callback) : void{
		if($recipients === []){
			return;
		}

		/** @var EntityEventBroadcaster[] $uniqueBroadcasters */
		$uniqueBroadcasters = [];
		/** @var NetworkSession[][] $broadcasterTargets */
		$broadcasterTargets = [];

		foreach($recipients as $player){
			if(!$player->isConnected()){
				continue;
			}
			$session = $player->getNetworkSession();
			$broadcaster = $session->getEntityEventBroadcaster();

			$bid = spl_object_id($broadcaster);
			$sid = spl_object_id($session);

			$uniqueBroadcasters[$bid] = $broadcaster;
			$broadcasterTargets[$bid][$sid] = $session;
		}

		if($uniqueBroadcasters === []){
			return;
		}

		foreach($uniqueBroadcasters as $bid => $broadcaster){
			$callback($broadcaster, $broadcasterTargets[$bid]);
		}
	}
}
