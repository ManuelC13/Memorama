<?php
define('PORT', 4040);
require dirname(__DIR__) . '/vendor/autoload.php';
use Ratchet\Server\IoServer;
use Ratchet\http\HttpServer;
use Ratchet\WebSocket\WsServer;

    $server = IoServer::factory(new HttpServer( new WsServer(new myWebSocket)),PORT);
    
    echo 'Running..dsds.';
    $server->run();