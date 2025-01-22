<?php 

namespace App\Services;

use Config\ConnectionRBMQ;

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

        // Filas
        $queue_generate_contract = 'generate_contract';
        $queue_notify_payment    = 'notify_payment';

        // Declarando as filas 
        $this->channelRBMQ->queue_declare($queue_generate_contract, false, true, false, false);
        $this->channelRBMQ->queue_declare($queue_notify_payment, false, true, false, false);

        // Vincular as filas ao exchange
        $this->channelRBMQ->queue_bind($queue_generate_contract, $exchange);
        $this->channelRBMQ->queue_bind($queue_notify_payment, $exchange);

        // Função para processar mensagens
        $callback = function ($msg) {
            // Disparar emails aqui ::: 
            // Armazenar em um redis ::: 
        };

        // Consumo das mensagens das filas
        $this->channelRBMQ->basic_consume($queue_generate_contract, '', false, true, false, false, $callback);
        $this->channelRBMQ->basic_consume($queue_notify_payment, '', false, true, false, false, $callback);

        // Esperar por mensagens
        while ($this->channelRBMQ->is_consuming()) {
            try {
                $this->channelRBMQ->wait();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }
    }
}