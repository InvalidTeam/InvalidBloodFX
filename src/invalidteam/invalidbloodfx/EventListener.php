<?php

/*
 *
 * ____  _                 _ _______  __
 * | __ )| | ___   ___   __| |  ___\ \/ /
 * |  _ \| |/ _ \ / _ \ / _` | |_   \  /
 * | |_) | | (_) | (_) | (_| |  _|  /  \
 * |____/|_|\___/ \___/ \__,_|_|   /_/\_\
 *
 *
 * Copyright 2021 InvalidTeam
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @author InvalidTeam
 * @link https://github.com/InvalidTeam/BloodFX
 *
 */

namespace invalidteam\invalidbloodfx;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class EventListener implements Listener
{

    public function onDamageEntity(EntityDamageEvent $event) {
        if($event instanceof EntityDamageByEntityEvent){
            $entity = $event->getEntity();
            if(!$entity instanceof Player) return;
            Main::$instance->spawnBlood($entity);
        }
    }

}