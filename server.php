<?php

use Josantonius\Cookie\Cookie;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

// Make sure composer dependencies have been installed
require_once __DIR__ . '/vendor/autoload.php';
require_once "config.php";

class MyChat implements MessageComponentInterface
{
    protected $clients;
    protected Cookie $cookie;
    protected mysqli $conn;

    public function __construct($cookie, $conn)
    {
        $this->clients = [];
        $this->cookie = $cookie;
        $this->conn = $conn;
    }

    public function onOpen(ConnectionInterface $client)
    {

        $querystring = $client->httpRequest->getUri()->getQuery();
        $strings = explode("&", $querystring);
        $params = [];

        foreach ($strings as $param) {
            $param = explode("=", $param);
            $params[$param[0]] = $param[1];
        }

        if (isset($params['id'])) {
            $token = "";
            $query = mysqli_query($this->conn, "SELECT * FROM `access_tokens` WHERE `token` = $token");
            $token_data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $user_id = $token_data['user_id'];
            $query = mysqli_query($this->conn, "SELECT * FROM `users` WHERE `id` = $user_id");
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            $user = $data[0];
            $client->id = $user['id'];
            $client->name = $user['name'];
            $client->email = $user['email'];
            $client->status = $user['status'];

            $this->clients[$user['id']] = $client;
            echo "TOTAL CONNECTIONS COUNT:" . count($this->clients) . PHP_EOL;
            print_r($user);
            echo " Connected" . PHP_EOL;
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
            new MyChat($cookie, $conn)
        )
    ),
    8080
);

$server->run();
