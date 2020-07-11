<?php

namespace LeRUGod\LWarn\form;

use LeRUGod\LWarn\LWarn;
use pocketmine\form\Form;
use pocketmine\Player;

class addWarnForm implements Form
{

    public function jsonSerialize()
    {
        $arr = [
            [
                'type' => 'input',
                'text' => '경고를 추가할 플레이어의 닉네임을 입력해주세요!',
                'placeholder' => 'EX ) RuSx2'
            ],
            [
                'type' => 'input',
                'text' => '경고 횟수를 입력해주세요!',
                'placeholder' => '숫자를 입력해주세요!'
            ],
            [
                'type' => 'input',
                'text' => '경고 사유를 입력해주세요!',
                'placeholder' => 'EX ) 너무 잘생겨서'
            ]
        ];

        return [
            'type' => 'custom_form',
            'title' => '§l§f경고 추가',
            'content' => $arr
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null)return;
        if ($data[0] === null){
            $player->sendMessage(LWarn::getInstance()->sy."§l§f경고를 추가할 플레이어의 이름을 입력해주세요!");
            return;
        }elseif ($data[1] === null){
            $player->sendMessage(LWarn::getInstance()->sy."§l§f경고 횟수를 입력해주세요!");
            return;
        }elseif ($data[2] === null){
            $player->sendMessage(LWarn::getInstance()->sy."§l§f사유를 입력해주세요!");
            return;
        }elseif (!is_numeric($data[1])){
            $player->sendMessage(LWarn::getInstance()->sy."§l§f경고 횟수는 숫자여야 합니다!");
            return;
        }else{
            LWarn::getInstance()->addWarn($data[0],round($data[1]),$data[2],$player->getName());
        }
    }

}