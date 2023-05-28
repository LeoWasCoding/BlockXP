<?php

declare(strict_types=1);

namespace YourNamespace;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use cooldogedev\BedrockEconomy\BedrockEconomyAPI;

class MiningExperiencePlugin extends PluginBase implements Listener
{
    /** @var Config */
    private $config;
    /** @var BedrockEconomyAPI|null */
    private $bedrockEconomy;

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        // Check if BedrockEconomy plugin is loaded
        if ($this->getServer()->getPluginManager()->getPlugin("BedrockEconomy")) {
            $this->bedrockEconomy = BedrockEconomyAPI::getInstance();
        } else {
            $this->getLogger()->warning("BedrockEconomy plugin is not installed. MiningExperiencePlugin will still function, but experience rewards won't be granted.");
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        $experience = (int) $this->config->get("mining_experience_amount", 50);

        if ($this->bedrockEconomy !== null) {
            $this->bedrockEconomy->addExperience($player, $experience);
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $experience = (int) $this->config->get("placing_experience_amount", 50);

        if ($this->bedrockEconomy !== null) {
            $this->bedrockEconomy->addExperience($player, $experience);
        }
    }
}
