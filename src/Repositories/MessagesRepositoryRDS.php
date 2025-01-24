<?php 

namespace App\Repositories;


class MessagesRepositoryRDS 
{
    private $connectionRedis;
    private $storage_queue;

    public function __construct($connection, $storage_queue)
    {
        $this->connectionRedis = $connection;
        $this->storage_queue = $storage_queue;
    }

    public function getMsgsOfRDS()
    {
         try {
             return $this->connectionRedis->lRange($this->storage_queue, 0, -1);
         } catch (\Throwable $th) {
             return $th;
        }
    }

    public function addMsgOfRDS($msg)
    {
        // OK - Funcionando ::: 
        $this->connectionRedis->lPush($this->storage_queue, $msg);
    }

    public function deleteMsgOfRDS()
    {

        // $this->connectionRedis->lTrim($this);

    }
}