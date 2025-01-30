<?php 

namespace App\Repositories;


class MessagesRepositoryRDS 
{
    private $connectionRedis;
    private $storage_queue;

    public function __construct($connection, $storage_queue)
    {
        $this->connectionRedis = $connection;
        $this->storage_queue   = $storage_queue;
    }

    public function addMsgOfRDS($msg)
    {
        if ($this->connectionRedis->exists($this->storage_queue)) {
            try {
                $this->connectionRedis->lPush($this->storage_queue, $msg);
            } catch (\Throwable $th) {
                return $th;
            }
        } else {
            try {
                $this->connectionRedis->del($this->storage_queue);
                $this->connectionRedis->lPush($this->storage_queue, $msg);
            } catch (\Throwable $th) {
                return $th;
            }
        }        
    }

    public function addNotifyMsgRDS()
    {
        $key = 'has_message'; 
        $value = 'has';

        if ($this->connectionRedis->exists($key)) {
            $this->connectionRedis->del($key);
            $this->connectionRedis->set($key, $value);
        } else {
            $this->connectionRedis->set($key, $value);
        }
    }
}