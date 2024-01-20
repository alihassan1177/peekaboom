<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

// Make sure composer dependencies have been installed
require __DIR__ . '/vendor/autoload.php';
require_once "config.php";

class MyChat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $client)
    {
        print_r($client);
    
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $client->id = $user['id'];
            $client->name = $user['name'];
            $client->email = $user['email'];
            $client->status = $user['status'];

            $this->clients[$user['id']] = $client;
            echo "TOTAL CONNECTIONS COUNT:" . count($this->clients) . PHP_EOL;
            $client->send($client->id);

            foreach ($this->clients as $client) {
                $client->send(json_encode($this->clients));
            }
        } else {
            $client->send('NOT AUTHENTICATED');
            $client->close();
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from != $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $client)
    {
        if (isset($client->id) && $client->id) {
            unset($this->clients[$client->id]);
        }

        echo "TOTAL CONNECTIONS COUNT:" . count($this->clients) . PHP_EOL;
    }

    public function onError(ConnectionInterface $client, \Exception $e)
    {
        $client->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyChat()
        )
    ),
    8080
);

$server->run();
