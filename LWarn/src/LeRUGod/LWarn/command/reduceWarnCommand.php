<?php

namespace LeRUGod\LWarn\command;

use LeRUGod\LWarn\form\reduceWarnForm;
use LeRUGod\LWarn\LWarn;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class reduceWarnCommand extends Command
{

    public function __construct()
    {
        parent::__construct('경고 차감', '경고 차감 명령어입니다', '/경고 차감', ['reducewarn']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){

            if ($sender->isOp()){

                $sender->sendForm(new reduceWarnForm());

            }else{

                $sender->sendMessage(LWarn::getInstance()->sy."§l§fOP만 사용가능한 명령어입니다!");
                return;

            }

        }else return;
    }

}