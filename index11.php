<?php
require_once 'utils/WechatObj.php';

$wechatObj = new WechatObj();

$res = file_get_contents('utils/access_token.json');
$result = json_decode($res);
$access_token = $result->access_token; //获取access_token

//查询用户的Tag列表
$url = "https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=$access_token";
$data = '{"openid":"oaxr41LdM4Nzgg8lCU18nnhkGnBo"}';
$resp = $wechatObj->requestHttp($url, $data);

var_dump($resp);