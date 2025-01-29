<?php 

namespace App\Services;

use App\Repositories\MessagesRepositoryRDS;
use Config\ConnectionRBMQ;
use Config\ConnectionRDS;
use Predis\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class RabbitMQService
{    
    private $rabbitMQConnectionInstance;
    private $channelRBMQ;

    public function __construct()
    {
        $this->rabbitMQConnectionInstance = new ConnectionRBMQ();

        $this->channelRBMQ = $this->rabbitMQConnectionInstance->getChannel();

        $this->consumerMessages();

        $this->rabbitMQConnectionInstance->closeConnect();
    }

    public function consumerMessages() 
    {
        $exchange = 'payment_users_fanout';
        $this->channelRBMQ->exchange_declare($exchange, 'fanout', false, true, false);

        // Fila
        $queue_notify_payment    = 'notify_payment';

        // Declarando as filas
        $this->channelRBMQ->queue_declare($queue_notify_payment, false, true, false, false);

        // Vincular as fila ao exchange
        $this->channelRBMQ->queue_bind($queue_notify_payment, $exchange);

        $callbackNotifyPayment = function ($msg) {
            $this->useStoresRDS($msg->body, 'queue_notify_payment');
        };

        // Consumo das mensagens das fila
        $this->channelRBMQ->basic_consume($queue_notify_payment, '', false, true, false, false, $callbackNotifyPayment);

        // Esperar por mensagens
        while ($this->channelRBMQ->is_consuming()) {
            try {
                $this->channelRBMQ->wait();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }
    }

    private function useStoresRDS($msg, $storage_queue) 
    {
        $connectionRedis = new ConnectionRDS(new Client());
        $connection = $connectionRedis->getConnectionRDS();

        $serviceRepositoryRDS = new MessagesRepositoryRDS($connection, $storage_queue);
        $serviceRepositoryRDS->addMsgOfRDS($msg);
    }
}