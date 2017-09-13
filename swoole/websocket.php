<?php
/**
 * 获取参数
 */
function getParameters($id, $group_name)
{
    $file_names = array(
        '1' => array(
            'ten_gbe_param' => array(
                'name' => 'fast_l8_ten_gbe_param.dat',
                'num' => 14
            )
        )
    );
    $file_name = $file_names[$id][$group_name];
    $str = file_get_contents("./data/" . $file_name['name']);
    $arr = unpack('v' . ($file_name['num'] + 1), $str);
    $result_arr = array();
    foreach ($arr as $item) {
        array_push($result_arr, strtoupper(dechex((int)$item)));
    }
    return json_encode($result_arr);
}

$ws_server = new swoole_websocket_server('0.0.0.0', 9501); //创建ws服务
//监听连接
$ws_server->on('open', function (swoole_websocket_server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});
//监听消息
$ws_server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $data = json_decode($frame->data);
    $result = '';
    switch ($data->type) {
        case 'getParameter':
            $result = getParameters($data->id, $data->group);
    }
    $server->push($frame->fd, $result);
});
//监听断开
$ws_server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});
//创建udp服务
$udp_server = $ws_server->addlistener('0.0.0.0', 9905, SWOOLE_SOCK_UDP);
//监听udp消息
$udp_server->on('Packet', function (swoole_server $serv, $data, $addr) {
    var_dump($data);
    global $ws_server;
    foreach ($ws_server->connections as $connection) {
        echo $connection;
        $ws_server->push($connection, $data);
    }
});
//开始执行
$ws_server->start();
