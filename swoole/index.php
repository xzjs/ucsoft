<?php
function dump($var)
{
    return highlight_string("<?php\n\$array = " . var_export($var, true) . ";", true);
}

$key_dir = dirname(dirname(__DIR__)) . '/tests/ssl';
$http = new swoole_http_server("0.0.0.0", 9501, SWOOLE_BASE);

$http->set([
//    'daemonize' => 1,
//    'open_cpu_affinity' => 1,
//    'task_worker_num' => 1,
    //'open_cpu_affinity' => 1,
    //'task_worker_num' => 100,
    //'enable_port_reuse' => true,
//    'worker_num' => 4,
    //'log_file' => __DIR__.'/swoole.log',
//    'reactor_num' => 24,
    //'dispatch_mode' => 3,
    //'discard_timeout_request' => true,
//    'open_tcp_nodelay' => true,
//    'open_mqtt_protocol' => true,
    //'task_worker_num' => 1,
    //'user' => 'www-data',
    //'group' => 'www-data',
//'daemonize' => true,
//    'ssl_cert_file' => $key_dir.'/ssl.crt',
//    'ssl_key_file' => $key_dir.'/ssl.key',
    'enable_static_handler' => true,
    'document_root' => '/home/htf/workspace/php/www.swoole.com/web/'
]);

$http->listen('127.0.0.1', 9502, SWOOLE_SOCK_TCP);

function chunk(swoole_http_request $request, swoole_http_response $response)
{
    $response->write("<h1>hello world1</h1>");
    //sleep(1);
    $response->write("<h1>hello world2</h1>");
    //sleep(1);
    $response->end();
}

function no_chunk(swoole_http_request $request, swoole_http_response $response)
{
    //udp
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


    if (substr($request->server['request_uri'], -8, 8) == 'test.jpg') {
        $response->header('Content-Type', 'image/jpeg');
        $response->sendfile(dirname(__DIR__) . '/test.jpg');
        return;
    }
    if ($request->server['request_uri'] == '/test.txt') {
        $last_modified_time = filemtime(__DIR__ . '/test.txt');
        $etag = md5_file(__DIR__ . '/test.txt');
        // always send headers
        $response->header("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified_time) . " GMT");
        $response->header("Etag", $etag);
        if (strtotime($request->header['if-modified-since']) == $last_modified_time or trim($request->header['if-none-match']) == $etag) {
            $response->status(304);
            $response->end();
        } else {
            $response->sendfile(__DIR__ . '/test.txt');
        }
        return;
    }
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
        return;
    }

    $output = '';
    $output .= "<h2>HEADER:</h2>" . dump($request->header);
    $output .= "<h2>SERVER:</h2>" . dump($request->server);
    if (!empty($request->files)) {
        $output .= "<h2>FILE:</h2>" . dump($request->files);
    }
    if (!empty($request->cookie)) {
        $output .= "<h2>COOKIES:</h2>" . dump($request->cookie);
    }
    if (!empty($request->get)) {
        $output .= "<h2>GET:</h2>" . dump($request->get);
    }
    if (!empty($request->post)) {
        $output .= "<h2>POST:</h2>" . dump($request->post);
    }

    $response->end("<h1>Hello world.</h1>" . $output);

    return;
}

$http->on('request', 'no_chunk');

$http->on('finish', function () {
    echo "task finish";
});

$http->on('task', function () {
    echo "async task\n";
});

//$http->on('close', function(){
//    echo "on close\n";
//});


$http->on('workerStart', function ($serv, $id) {
    //var_dump($serv);
});

$http->start();
