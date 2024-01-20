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

    public function onOpen(ConnectionInterface $conn)
    {
        var_dump(session_status());
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }        
     
        print_r($_SESSION['user']);
     
        if (isset($_SESSION['user'])) {

            $user = $_SESSION['user'];
            $conn->id = $user['id'];
            $conn->name = $user['name'];
            $conn->email = $user['email'];
            $conn->status = $user['status'];

            $this->clients[$user['id']] = $conn;
            echo "TOTAL CONNECTIONS COUNT:" . count($this->clients) . PHP_EOL;
            $conn->send($conn->id);
    
            foreach ($this->clients as $client) {
                $client->send(json_encode($this->clients));
            }
        }else{
            $conn->send('NOT AUTHENTICATED');
            $conn->close();
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

    public function onClose(ConnectionInterface $conn)
    {
        if (isset($conn->id) && $conn->id) {
            unset($this->clients[$conn->id]);
        }

        echo "TOTAL CONNECTIONS COUNT:" . count($this->clients) . PHP_EOL;
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
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
