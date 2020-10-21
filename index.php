<?php
header("Content-type: image/JPEG");
use UAParser\Parser;
require_once 'vendor/autoload.php';
$im = imagecreatefromjpeg("xhxh.jpg"); 
$ip = $_SERVER["REMOTE_ADDR"];
$ua = $_SERVER['HTTP_USER_AGENT'];
$get = $_GET["s"];
$get = base64_decode(str_replace(" ","+",$get));
$weekarray = array("日","一","二","三","四","五","六"); 
//ua
$parser = Parser::create();
$result = $parser->parse($ua);
$os = $result->os->toString(); // Mac OS X
$browser = $result->device->family.'-'.$result->ua->family;// Safari 6.0.2 
//地址
$data = json_decode(curl_get('http://ip.taobao.com/outGetIpInfo?ip='.$ip.'&accessKey=alibaba-inc'), true);
$country = $data['data']['country']; 
$region = $data['data']['city']; 
$adcode = $data['site']['adcode']; 
//温度
$data_w = json_decode(curl_get('http://wthrcdn.etouch.cn/weather_mini?city='.$region),true);
$weather = substr($data_w['data']['forecast'][0]['high'],7);
$temperature = substr($data_w['data']['forecast'][0]['low'],7);
//定义颜色
$black = ImageColorAllocate($im, 0,0,0);//定义黑色的值
$red = ImageColorAllocate($im, 255,0,0);//红色
$font = 'msyh.ttf';//加载字体
//输出
imagettftext($im, 16, 0, 10, 40, $red, $font,'欢迎您来自'.$country.'-'.$region.'的朋友');
imagettftext($im, 16, 0, 10, 72, $red, $font, '今天是'.date('Y年n月j日').' 星期'.$weekarray[date("w")]);//当前时间添加到图片
imagettftext($im, 16, 0, 10, 104, $red, $font,'您的IP是:'.$ip.'  '.$weather."-".$temperature);//ip和温度
imagettftext($im, 16, 0, 10, 140, $red, $font,'您使用的是'.$os.'操作系统');
imagettftext($im, 16, 0, 10, 175, $red, $font,'您使用的是'.$browser);
imagettftext($im, 13, 0, 10, 200, $black, $font,$get); 
ImageGif($im);
ImageDestroy($im);


function curl_get($url, array $params = array(), $timeout = 6){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
?>
