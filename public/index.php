<?php
use Predis\Client;
use App\Controllers\NotifyPaymentController;
use App\Controllers\MessagesPaymentNotifyController;
use Config\ConnectionRBMQ;
use App\Routes\ApiRoutes;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$notifyPayment = new NotifyPaymentController();

$app->run();
