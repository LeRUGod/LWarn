<?php

namespace LeRUGod\LWarn\command;

use LeRUGod\LWarn\LWarn;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class seeWarnCommand extends Command
{

    public function __construct()
    {
        parent::__construct('경고 보기', '경고 보기 커맨드입니다', '/경고 보기', ['seewarn']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){

            if (isset($args[0])){

                $warncountpl = LWarn::getInstance()->getWarn($args[0]);

                if ($warncountpl == -1){

                    $sender->sendMessage(LWarn::getInstance()->sy."§l§f플레이어가 존재하지 않습니다!");

                    return;

                }else{

                    $sender->sendMessage(LWarn::getInstance()->sy."§l§f".$args[0]." 님의 경고 횟수 : ".$warncountpl);

                    return;

                }

            }else{

                $warn = LWarn::getInstance()->getWarn($sender->getName());
                $sender->sendMessage(LWarn::getInstance()->sy."§l§f".$sender->getName()." 님의 경고 횟수 : ".$warn);

                return;

            }

        }
    }

}