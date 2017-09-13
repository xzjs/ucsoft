<?php
function w()
{
    $numbers = [0xF1,
        0x1, 0xA00, 0x202, 0x1,
        0xA00, 0xEA60, 0xFC01, 0x2D1C,
        0xE41D, 0xC9, 0xA00, 0xEa60,
        0x2010, 0x0];
    $handle = fopen("./data/fast_l8_ten_gbe_param.dat", "wb");//打开date.bat文件不存在则创建文件
    foreach ($numbers as $number) {
        if (fwrite($handle, pack("v", $number)) == FALSE) {//数据打包成二进制字符串后写入文件
            echo "Can not write data.dat.";
        }
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

r();
//w();
