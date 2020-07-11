<?php

namespace LeRUGod\LWarn\command;

use LeRUGod\LWarn\form\addWarnForm;
use LeRUGod\LWarn\LWarn;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class addWarnCommand extends Command
{

    public function __construct()
    {
        parent::__construct('경고 추가', '경고 추가 명령어입니다', '/경고 추가', ['addwarn']);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){

            if ($sender->isOp()){

                $sender->sendForm(new addWarnForm());

            }else{

                $sender->sendMessage(LWarn::getInstance()->sy."§l§fOP만 사용가능한 명령어입니다!");
                return;

            }

        }else return;
    }

}