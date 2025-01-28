<?php 

namespace App\Controllers;
// Iremos consumir as filas ::: 

use App\Services\RabbitMQService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotifyPaymentController 
{
    public function __construct()
    {
        $this->consumerMessages();
    }

    public function consumerMessages()
    {
        $rabbitMQService = new RabbitMQService();
    }
}