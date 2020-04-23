<?php
/**
 * Copyright (c) 2020 Ramdhan Firmansyah
 * File              : main.php
 * @author           : Cvar1984 <gedzsarjuncomuniti@gmail.com>
 * Date              : 23.04.2020
 * Last Modified Date: 23.04.2020
 * Last Modified By  : Cvar1984 <gedzsarjuncomuniti@gmail.com>
 */
class Scratch
{
    public function request($url, $options)
    {
        $userAgent = $options['user_agent'];
        $apiKey = $options['api_key'];
        $id = $options['id'];
        $cookie = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cookie.txt';
        $headers[] = 'Content-Type: application/json; charset=utf-8';
        $headers[] = 'Host: 160.20.145.203:5000';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Accept-Encoding: gzip';
        $data = '{"API_Key":"' . $apiKey . '","RefCode":"A931","ID":' . $id . '}';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie);

        $result = curl_exec($curl);
        if ($result == false) {
            $result = curl_error($curl);
        } else {
            $result = json_decode($result, true);
        }
        curl_close($curl);
        return $result;
    }
}

$app = new Scratch();

$urlInfo = 'http://160.20.145.203:5000/api/User/GetUserInfo';
$urlScratch = 'http://160.20.145.203:5000/api/Scratch/StartScratch';
$urlRef = 'http://160.20.145.203:5000/api/Ref/EnterRef';
$options = file_get_contents('config.json');
$options = json_decode($options, true);

$app->request($urlRef, $options);
while (true) {
    $userInfo = $app->request($urlInfo, $options);
    $scratch = $app->request($urlScratch, $options);

    $app->diamonds = $userInfo['diamonds'];
    $app->scratches = $userInfo['scratches'];
    $app->slot1 = $scratch['slot1'];
    $app->slot2 = $scratch['slot2'];
    $app->slot3 = $scratch['slot3'];
    echo 'Diamonds: ' . $app->diamonds . PHP_EOL;
    echo 'Scratch: ' . $app->scratches . PHP_EOL;
    echo 'Slot 1: ' . $app->slot1 . PHP_EOL;
    echo 'Slot 2: ' . $app->slot2 . PHP_EOL;
    echo 'Slot 3: ' . $app->slot3 . PHP_EOL;
    echo PHP_EOL;
    if ($app->scratches == 0) {
        echo 'Habis Boss!!' . PHP_EOL;
        break;
    }
}
