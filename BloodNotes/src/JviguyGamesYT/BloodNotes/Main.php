<?php

declare(strict_types=1);

namespace JviguyGamesYT\BloodNotes;

use Exception;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
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
            $iteminhand->setCount($iteminhand->getCount() - 1);
            $player->getInventory()->setItemInHand($iteminhand);
        }
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "bloodynote":
                if ($sender->isOp()){
                    if (empty($args)) {
                        $sender->sendMessage(TextFormat::RED."Usage /bloodynote {player} {amount of bloody notes}");
                        return false;
                    } else {
                        if (isset($args[0])) {
                            $name = $args[0];
                            if (isset($args[1])) {
                                $amount = $args[1];
                                $player = $this->getServer()->getPlayer($name);
                                if ($player == null) {
                                    $sender->sendMessage(TextFormat::RED."Please State A Online Player!");
                                    return false;
                                } else {
                                    if ($args[1] > 1) {
                                        for ($attempts=0; $attempts <(int)$args[1]; $attempts++) {
                                            $item = Item::get(Item::PAPER);
                                            $item->setCustomName(TextFormat::RED."Bloody Note");
                                            $player->getInventory()->addItem($item);
                                        }
                                    } else {
                                        $sender->sendMessage(TextFormat::RED."You Cant Send 0 or below blood notes!");
                                        return false;
                                    }
                                }
                            }
                        } else {
                            $sender->sendMessage(TextFormat::RED."Usage /bloodynote {player} {amount of bloody notes}");
                        }
                    }
                } else {
                    $sender->sendMessage(TextFormat::RED."Insuffiecent Permissions");
                    return false;
                }
            break;
        }
        return false;
    }
}
