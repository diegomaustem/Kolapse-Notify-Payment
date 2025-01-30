<?php
use Predis\Client;
use App\Controllers\NotifyPaymentController;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$notifyPayment = new NotifyPaymentController();

$app->run();
