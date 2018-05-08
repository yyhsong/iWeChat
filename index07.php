<?php
	$appId = 'wx65b979d6a9938837';
	$appSecret = 'd089d19bc0bec54723b1dbf4b860e076';
	$accessUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
	
	//获取access_token
	$resultArr = https_request($accessUrl);
	$accessToken = $resultArr['access_token'];
	
	//获取菜单请求
	$menuUrl = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$accessToken";
	$result = https_request($menuUrl);
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	
	//封装http请求方法
	function https_request($url, $data=null) {
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
	    curl_close($curl);
		$result = json_decode($output, true);
		return $result;
	}