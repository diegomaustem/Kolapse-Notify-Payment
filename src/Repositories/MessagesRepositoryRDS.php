<?php 

namespace App\Repositories;


class MessagesRepositoryRDS 
{
    private $connectionRedis;
    private $queue_generate_contract = 'queue_generate_contract';
    private $queue_notify_payment    = 'notify_payment';

    public function __construct($connection)
    {
        $this->connectionRedis = $connection;
    }

    public function getMsgsOfRDS()
    {
         try {
             return $this->connectionRedis->lRange($this->queue_generate_contract, 0, -1);
         } catch (\Throwable $th) {
             return $th;
        }
    }

    public function addMsgOfRDS($msg)
    {
        // OK - Funcionando ::: 
        $this->connectionRedis->lPush($this->queue_generate_contract, $msg);
    }

    public function deleteMsgOfRDS()
    {

    }
}