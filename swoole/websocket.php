<?php
//$server->set(['worker_num' => 4]);
$id = 0;
$ws_server = new swoole_websocket_server('0.0.0.0', 9501);
$ws_server->on('open', function (swoole_websocket_server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});
$ws_server->on('message', function (swoole_websocket_server $server, $frame) {
    $GLOBALS['id'] = $frame->fd;
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});
$ws_server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});
$udp_server = $ws_server->addlistener('0.0.0.0', 9905, SWOOLE_SOCK_UDP);
$udp_server->on('Packet', function (swoole_server $serv, $data, $addr) {
    var_dump($data);
    global $ws_server;
    foreach ($ws_server->connections as $connection) {
        echo $connection;
        $ws_server->push($connection, $data);
    }
});
$ws_server->start();
