<?php

namespace LeRUGod\LWarn;

use LeRUGod\LWarn\command\addWarnCommand;
use LeRUGod\LWarn\command\reduceWarnCommand;
use LeRUGod\LWarn\command\seeWarnCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class LWarn extends PluginBase implements Listener
{

    public $data;
    public $db;

    public $sy = "§b§l[ §f시스템 §b]§r ";


    //경고 몇에 밴이 되게 할 것인지 정합니다
    public const WARN_MAX_COUNT = 5;

    private static $instance;

    public function onEnable(){

        $this->getServer()->getPluginManager()->registerEvents($this,$this);

        @mkdir($this->getDataFolder());
        $this->data = new Config($this->getDataFolder().'warns.yml',Config::YAML);
        $this->db = $this->data->getAll();

        $this->getServer()->getCommandMap()->register('경고추가',new addWarnCommand());
        $this->getServer()->getCommandMap()->register('경고차감',new reduceWarnCommand());
        $this->getServer()->getCommandMap()->register('경고보기',new seeWarnCommand());

        if (!isset($this->db['warn'])){

            $this->db['warn'] = [];

            $this->onSave();

        }

    }

    public function onLoad(){

        self::$instance = $this;

    }

    public static function getInstance() : self {

        return self::$instance;

    }

    public function onDisable(){
        $this->onSave();
    }

    public function onSave(){

        $this->data->setAll($this->db);
        $this->data->save();

    }

    /*
     * editing player's warn
     */

    public function addWarn(string $nam,int $amount,string $because,string $op){

        $name = strtolower($nam);

        if (!isset($this->db['warn'][$name])){

            $this->getServer()->getPlayer($op)->sendMessage($this->sy."§l§f플레이어가 존재하지 않습니다!");
            return;

        }

        $this->db['warn'][$name] = $this->db['warn'][$name] + $amount;
        $this->onSave();

        if ($this->db['warn'][$name]>=self::WARN_MAX_COUNT){

            $this->getServer()->getOfflinePlayer($name)->setBanned(true);
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님이 경고를 ".(string)$amount."회 지급받으셔서 밴처리 되셨습니다");
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님의 총 경고 수 : ".(string)$this->db['warn'][$name]." 부여자 : ".$op." 사유 : ".$because);
            return;

        }else{

            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님이 경고를 ".(string)$amount."회 지급받으셨습니다");
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님의 총 경고 수 : ".(string)$this->db['warn'][$name]." 부여자 : ".$op." 사유 : ".$because);
            return;

        }

    }

    public function reduceWarn(string $nam,int $amount,string $because,string $op){

        $name = strtolower($nam);

        if (!isset($this->db['warn'][$name])){

            $this->getServer()->getPlayer($op)->sendMessage($this->sy."§l§f플레이어가 존재하지 않습니다!");
            return;

        }

        if ($this->db['warn'][$name] - $amount < 0){
            $this->getServer()->getPlayer($op)->sendMessage($this->sy."§l§f경고 차감 후 횟수가 0보다 작습니다!");
            return;
        }else{
            $this->db['warn'][$name]-=$amount;
            $this->onSave();
        }

        if ($this->getServer()->getOfflinePlayer($name)->isBanned() and $this->db['warn'][$name]<self::WARN_MAX_COUNT){

            $this->getServer()->getOfflinePlayer($name)->setBanned(false);
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님이 경고를 ".(string)$amount."회 차감받으셔서 밴이 해제되셨습니다");
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님의 총 경고 수 : ".(string)$this->db['warn'][$name]." 부여자 : ".$op." 사유 : ".$because);

        }else{

            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님이 경고를 ".(string)$amount."회 차감받으셨습니다");
            $this->getServer()->broadcastMessage($this->sy."§l§f".$nam."님의 총 경고 수 : ".(string)$this->db['warn'][$name]." 부여자 : ".$op." 사유 : ".$because);

        }

    }

    public function getWarn(string $nam) : int {

        $name = strtolower($nam);

        if (!isset($this->db['warn'][$name])){

            return -1;

        }else{

            return $this->db['warn'][$name];

        }

    }

    /*
     * using Events
     */

    public function onJoin(PlayerJoinEvent $event){

        $player = $event->getPlayer();
        $name = strtolower($player->getName());

        if (!isset($this->db['warn'][$name])){
            $this->db['warn'][$name] = 0;
            return;
        }

        if ($this->db['warn'][$name]>self::WARN_MAX_COUNT){
            $player->kick("§l§f경고 5회를 넘으셔서 밴 당하셨습니다!");
            return;
        }

    }

}