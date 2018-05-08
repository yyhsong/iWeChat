<?php
	$appId = 'wx65b979d6a9938837';
	$appSecret = 'd089d19bc0bec54723b1dbf4b860e076';
	$accessUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
	
	//获取access_token
	$resultArr = https_request($accessUrl);
	$accessToken = $resultArr['access_token'];
	
	//自定义菜单内容
	$menuJson = '{
		"button": [
			{
				"name": "扫码",
				"sub_button": [
					{
						"type": "scancode_waitmsg",
						"name": "扫码带提示",
						"key": "wxmenu-01" 
					}, 
					{
						"type": "scancode_push",
						"name": "扫码推事件",
						"key": "wxmenu-02" 
					}
				]
			},
			{
				"name": "发图",
				"sub_button": [
					{
						"type": "pic_sysphoto",
						"name": "系统拍照发图",
						"key": "wxmenu-11" 
					}, 
					{
						"type": "pic_photo_or_album",
						"name": "拍照或从相册发图",
						"key": "wxmenu-12" 
					}, {
						"type": "pic_weixin",
						"name": "微信相册发图",
						"key": "wxmenu-13" 
					}
				]
			},
			{
				"name": "其他",
				"sub_button": [
					{
						"type": "location_select",
						"name": "发送位置",
						"key": "wxmenu-20" 
					}, 
					{
						"type": "click",
						"name": "点击按钮",
						"key": "wxmenu-21" 
					},
					{
						"type": "view",
						"name": "百度",
						"url": "http://www.baidu.com/" 
					}
				]
			}
		]
	}';
	
	//自定义菜单请求
	$menuUrl = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
	$result = https_request($menuUrl, $menuJson);
	var_dump($result);
	
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