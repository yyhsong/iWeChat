<?php
require_once 'utils/WechatObj.php';

$wechatObj = new WechatObj();

$res = file_get_contents('utils/access_token.json');
$result = json_decode($res);
$access_token = $result->access_token; //获取access_token

//获取第一个用户的openid
$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
$respArr = $wechatObj->requestHttp($url);
$openid = $respArr['data']['openid'][0];

//根据openid获取用户基本信息
$url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
$resp = $wechatObj->requestHttp($url2);

var_dump($resp);