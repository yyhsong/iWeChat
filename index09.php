<?php
require_once 'utils/WechatObj.php';

$wechatObj = new WechatObj();

$res = file_get_contents('utils/access_token.json');
$result = json_decode($res);

var_dump($result);
