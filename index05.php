<?php
	$appId = 'wx5646810d82f8a7f1';
	$appSecret = '1bbaf7e0eddc5c779186b50fdcd88421';
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
	
	$ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	$result = json_decode($output, true);
	var_dump($result);