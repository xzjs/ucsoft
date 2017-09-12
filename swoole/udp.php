<?php
try {
    $client = new swoole_client(SWOOLE_SOCK_UDP);
    if ($client->connect('127.0.0.1', 8000)) {
        $client->send("hello world");
        echo "send success";
    } else {
        echo "connect failed";
    }
    $client->close();
} catch (Exception $exception) {
    echo $exception->getMessage();
}