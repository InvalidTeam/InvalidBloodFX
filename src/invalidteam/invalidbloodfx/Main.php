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

use JackMD\ConfigUpdater\ConfigUpdater;
use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{

    private const CONFIG_VERSION = 1;

    /** @var self $instance */
    public static $instance;

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        $this->checkConfigs();
        Server::getInstance()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() == "bloodfx") {
            if (!$sender instanceof Player) {
                $sender->sendMessage(TextFormat::RED."Usage this command in game!");
                return true;
            }
            if (!$sender->hasPermission("bloodfx.cmd")) {
                $sender->sendMessage(Server::getInstance()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));
                return false;
            }
            switch ($args[0]) {
                case "help":
                    if (!$sender->hasPermission("bloodfx.cmd.help")) {
                        $sender->sendMessage(Server::getInstance()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));
                        break;
                    }
                    $sender->sendMessage("§aInvalidBloodFX help command: \n".
                    "§a/bloodfx help: §7InvalidBloodFX help\n".
                    "§a/bloodfx reload: §7Reload Configuration");
                    break;
                case "reload":
                    if (!$sender->hasPermission("bloodfx.cmd.reload")) {
                        $sender->sendMessage(Server::getInstance()->getLanguage()->translateString(TextFormat::RED . "%commands.generic.permission"));
                        break;
                    }
                    $this->getConfig()->reload();
                    $this->getConfig()->save();
                    $sender->sendMessage("§aAll Configuration has been reloaded!");
                    break;
                default:
                    $sender->sendMessage("§cUsage: §7/bloodfx help");
                    break;
            }
        }
        return true;
    }

    public function spawnBlood(Player $player) {
        if (!in_array($player->getLevel()->getFolderName(), $this->getConfig()->get("blacklisted-world"))) return;
        $min = $this->getConfig()->getNested("particles-count.min");
        $max = $this->getConfig()->getNested("particles-count.max");
        $player->getLevel()->addParticle(new DestroyBlockParticle($player->add(mt_rand($min, $max)/100, 1 + mt_rand($min, $max)/100, mt_rand($min, $max)/100), Block::get(Block::REDSTONE_BLOCK)));
    }

    private function checkConfigs(): void {
        $this->saveDefaultConfig();
        $min = $this->getConfig()->getNested("particles-count.min");
        $max = $this->getConfig()->getNested("particles-count.max");
        if (!is_int($min) || !is_int($min)) {
            $this->reloadConfig();
        }
        if (!is_int($this->getConfig()->getNested("particles-count.min")) || !is_int($this->getConfig()->getNested("particles-count.min"))) {
            $this->reloadConfig();
        }
        if(ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::CONFIG_VERSION)){
            $this->reloadConfig();
        }
    }

}