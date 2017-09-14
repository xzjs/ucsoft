<?php

class WSServer
{

    public $ws_server;
    public $udp_server;
    public $file_names = array(
        '1' => array(
            'ten_gbe_param' => array(
                'name' => 'fast_l8_ten_gbe_param.dat',
                'num' => 14
            ),
            'ip' => '127.0.0.1',
            'port' => '8000',
        ),
        'network' => 'ten_gbe_enable.dat',
        'rest' => 'adc_soft_rst.dat'
    );

    function __construct()
    {
        $this->ws_server = new swoole_websocket_server('0.0.0.0', 9501); //创建ws服务
        //监听连接
        $this->ws_server->on('open', function (swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });
        //监听消息
        $this->ws_server->on('message', function (swoole_websocket_server $server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $data = json_decode($frame->data);
            $result = '';
            switch ($data->type) {
                case 'getParameter'://获取参数
                    $result = $this->getParameters($data->id, $data->group);
                    break;
                case 'setParameter'://设置参数
                    $this->setParameters($data->id, $data->group, $data->parameters);
                    break;
                case 'downloadParameter':
                    $filename = $this->file_names[$data->id][$data->group]['name'];
                    $ip = $this->file_names[$data->id]['ip'];
                    $port = $this->file_names[$data->id]['port'];
                    $result = $this->downloadParameters($filename, $ip, $port);
                    break;
                case 'networkCtrl':
                    $result = $this->networkCtrl($data->id, $data->value);
                    break;
                case 'rest':
                    $result=$this->rest($data->id);
                    break;
                default:
                    break;
            }
            $server->push($frame->fd, $result);
        });
        //监听断开
        $this->ws_server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });
        //创建udp服务
        $this->udp_server = $this->ws_server->addlistener('0.0.0.0', 9905, SWOOLE_SOCK_UDP);
        //监听udp消息
        $this->udp_server->on('Packet', function (swoole_server $serv, $data, $addr) {
            var_dump($data);
//            global $ws_server;
            foreach ($this->ws_server->connections as $connection) {
                echo $connection;
                $this->ws_server->push($connection, $data);
            }
        });
    }

    /**
     * 获取参数
     */
    function getParameters($id, $group_name)
    {
        var_dump($id);
        var_dump($group_name);
        $file_name = $this->file_names[$id][$group_name];
        $str = file_get_contents("./data/" . $file_name['name']);
        $arr = unpack('v' . ($file_name['num'] + 1), $str);
        $result_arr = array();
        foreach ($arr as $item) {
            array_push($result_arr, strtoupper(dechex((int)$item)));
        }
        $data['type'] = 'getParameter';
        $data['parameter'] = $result_arr;
        return json_encode($data);
    }

    /**
     * 设置参数
     * @param $id 应用id
     * @param $group_name 参数组名
     * @param $parameters 参数
     * @return string
     */
    function setParameters($id, $group_name, $parameters)
    {
        try {
            $file_name = $this->file_names[$id][$group_name];
            $handle = fopen("./data/" . $file_name['name'], "wb");//打开date.bat文件不存在则创建文件
            foreach ($parameters as $parameter) {
                if (fwrite($handle, pack("v", hexdec($parameter))) == FALSE) {//数据打包成二进制字符串后写入文件
                    echo "Can not write data.dat.";
                }
            }
            fclose($handle);//关闭一个已打开的文件指针
        } catch (Exception $exception) {
            return json_encode($exception->getMessage());
        }
        return 'True';
    }

    /**
     * 下发参数
     * @param $filename string 文件名
     * @param $ip
     * @param $port
     * @return string
     */
    function downloadParameters($filename, $ip, $port)
    {
        try {
            $client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_SYNC);
            $client->connect($ip, $port);
            $str = file_get_contents($filename);
            $client->send($str);
            return json_encode(true);
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return json_encode($exception->getMessage());
        }
    }

    /**
     * 万兆网开关
     * @param $id
     * @param $value
     * @return string
     */
    function networkCtrl($id, $value)
    {
        try {
            $filename = "./data/ten_gbe_enable.dat";
            $handle = fopen($filename, "wb");//打开date.bat文件不存在则创建文件
            if (fwrite($handle, pack("v", 0xFB)) == FALSE) {//数据打包成二进制字符串后写入文件
                echo "Can not write data.dat.";
            }
            $arg = $value == 'true' ? 0x1 : 0x0;
            if (fwrite($handle, pack("V", $arg)) == FALSE) {//此处数据为32位
                echo "Can not write data.dat.";
            }
            fclose($handle);//关闭一个已打开的文件指针
            echo 'write file success';
            return $this->downloadParameters($filename, $this->file_names[$id]['ip'], $this->file_names[$id]['port']);
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return $exception->getMessage();
        }
    }

    /**
     * 软复位
     * @param $id
     * @return string
     */
    function rest($id)
    {
        try {
            $filename = "./data/adc_soft_rst.dat";
            $handle = fopen($filename, "wb");//打开date.bat文件不存在则创建文件
            if (fwrite($handle, pack("v", 0xFC)) == FALSE) {//数据打包成二进制字符串后写入文件
                echo "Can not write data.dat.";
            }
            if (fwrite($handle, pack("V", 0x0)) == FALSE) {//数据打包成二进制字符串后写入文件
                echo "Can not write data.dat.";
            }
            if (fwrite($handle, pack("V", 0x1)) == FALSE) {//数据打包成二进制字符串后写入文件
                echo "Can not write data.dat.";
            }
            if (fwrite($handle, pack("V", 0x0)) == FALSE) {//数据打包成二进制字符串后写入文件
                echo "Can not write data.dat.";
            }
            fclose($handle);//关闭一个已打开的文件指针
            echo 'write file success';
            return $this->downloadParameters($filename, $this->file_names[$id]['ip'], $this->file_names[$id]['port']);
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return $exception->getMessage();
        }

    }
}

$ws = new WSServer();
$ws->ws_server->start();

