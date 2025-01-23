<?php 
namespace Config;


use Exception;


require_once __DIR__ . '/../vendor/autoload.php';


class ConnectionRDS
{
    private $connection;

    public function __construct($clientRDS)
    {
        $this->connection = $clientRDS;
    }  

    public function getConnectionRDS()
    {
        return $this->connection;
    }
}