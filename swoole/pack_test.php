<?php
function w()
{
//    $numbers = [0xF1,
//        0x1, 0xA00, 0x202, 0x1,
//        0xA00, 0xEA60, 0xFC01, 0x2D1C,
//        0xE41D, 0xC9, 0xA00, 0xEa60,
//        0x2010, 0x0];
//    $handle = fopen("./data/fast_l8_ten_gbe_param.dat", "wb");//打开date.bat文件不存在则创建文件
//    $numbers = [0xF4,
//        0x22D, 0x213, 0x22E, 0x231,
//        0x200, 0x280, 0x260, 0x225,
//        0x200, 0x200, 0x200, 0x200,
//        0x1E0, 0x23B, 0x206, 0x1D6,
//        0x200, 0x235, 0x26C, 0x240,
//        0x200, 0x200, 0x200, 0x200,];
//    $handle = fopen("./data/fast_l8_adc_cal_param.dat", "wb");//打开date.bat文件不存在则创建文件
//    $numbers = [0xF4,
//        0x22D, 0x213, 0x22E, 0x231,
//        0x200, 0x280, 0x260, 0x225,
//        0x200, 0x200, 0x200, 0x200,
//        0x1E0, 0x23B, 0x206, 0x1D6,
//        0x200, 0x235, 0x26C, 0x240,
//        0x200, 0x200, 0x200, 0x200,];
//    $handle = fopen("./data/fast_l8_adc_cal_param.dat", "wb");//打开date.bat文件不存在则创建文件
//    foreach ($numbers as $number) {
//        if (fwrite($handle, pack("v", $number)) == FALSE) {//数据打包成二进制字符串后写入文件
//            echo "Can not write data.dat.";
//        }
//    }
    $handle=fopen('./data/light.dat','wb');
    $numbers=[0x100,0xFF,0x1234];
    if (fwrite($handle, pack("v", 0x100)) == FALSE) {//数据打包成二进制字符串后写入文件
        echo "Can not write data.dat.";
    }
    if (fwrite($handle, pack("C", 0xFF)) == FALSE) {//数据打包成二进制字符串后写入文件
        echo "Can not write data.dat.";
    }
    if (fwrite($handle, pack("v", 0x1234)) == FALSE) {//数据打包成二进制字符串后写入文件
        echo "Can not write data.dat.";
    }
    fclose($handle);//关闭一个已打开的文件指针
}

function r()
{
//    $handle = fopen("./data/fast_l8_ten_gbe_param.dat", "rb");
    $str = file_get_contents("./data/fast_l8_ten_gbe_param.dat");
    $arr = unpack('v15', $str);
    foreach ($arr as $item) {
        echo $item . ' ';
        echo dechex((int)$item)."\n";
    }
}

function udp_test(){
    $client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_SYNC);
    $client->connect('127.0.0.1', 9905);
    $str = file_get_contents('./data/light.dat');
    $client->send($str);
}

//r();
//w();
udp_test();