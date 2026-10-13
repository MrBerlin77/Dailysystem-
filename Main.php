<?php

namespace MrBerlin\Dailysystem;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use MrBerlin\Dailysystem\commands\DailyCommand;
use MrBerlin\Dailysystem\managers\DailyManager;
use MrBerlin\Dailysystem\listeners\PlayerListener;

class Main extends PluginBase {

    private static ?Main $instance = null;
    private Config $config;
    private DailyManager $dailyManager;

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();

        $this->dailyManager = new DailyManager($this);

        $this->getServer()->getCommandMap()->register(
            "dailysystem",
            new DailyCommand($this)
        );

        $this->getServer()->getPluginManager()->registerEvents(
            new PlayerListener($this),
            $this
        );

        $this->printBanner();
    }

    protected function onDisable(): void {
        if (isset($this->dailyManager)) {
            $this->dailyManager->save();
        }
    }

    private function printBanner(): void {
        $banner = [
            "————————————————————————————————————————",
            "|  ____                        ____  __  __ __  __ ____  ",
            "| |  _ \\ _   _ _ __ ___   __ _|  _ \\|  \\/  |  \\/  |  _ \\ ",
            "| | |_) | | | | '_ ` _ \\ / _` | |_) | |\\/| | |\\/| | |_) |",
            "| |  __/| |_| | | | | | | (_| |  __/| |  | | |  | |  __/ ",
            "| |_|    \\__,_|_| |_| |_|\\__,_|_|   |_|  |_|_|  |_|_|    ",
            "|                                                         ",
            "|  Plugin by MrBerlin  |  Version " . $this->getDescription()->getVersion(),
            "————————————————————————————————————————"
        ];

        foreach ($banner as $line) {
            $this->getLogger()->info($line);
        }
    }

    public static function getInstance(): Main {
        return self::$instance;
    }

    public function getPluginConfig(): Config {
        return $this->config;
    }

    public function getDailyManager(): DailyManager {
        return $this->dailyManager;
    }

    public function reloadPluginConfig(): void {
        $this->reloadConfig();
        $this->config = $this->getConfig();
    }
}