<?php

declare(strict_types=1);

namespace JviguyGamesYT\BloodNotes;

use Exception;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{
    public $econ;
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this , $this);
        try {
            $this->econ = EconomyAPI::getInstance();
            if ($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") == null) {
                throw new Exception("Economy API Was Not Found In This Server!");
            }
        } catch (Exception $exception) {
            $this->getLogger()->info($exception->getMessage());
        }
    }
    public function onClick(PlayerInteractEvent $e) {
        $player = $e->getPlayer();
        $iteminhand = $player->getInventory()->getItemInHand();
        if ($iteminhand->getCustomName() === TextFormat::RED."Bloody Note") {
            $moneytoadd = mt_rand(250000 , 2500000);
            $this->econ->addMoney($player , $moneytoadd);
            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
        }
    }
}
