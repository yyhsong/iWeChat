<?php

class WechatObj {	
	//测试号
	private $_appId = 'wx5646810d81f8a7f1';
	private $_appSecret = '1bbaf7e0eddc5a779186b50fdcd88421';
	
	//构造函数，获取access_token
	public function __construct($_appId = NULL, $_appSecret = NULL) {
		//从本地文件读写access_token
		$res = file_get_contents('utils/access_token.json');
		$result = json_decode($res, true);
		$this->expires_time = $result['expires_time'];
		$this->access_token = $result['access_token'];
		
		//如果access_token已过期，则重新获取
		if(time() > ($this->expires_time + 3600)) {
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appId.'&secret='.$this->_appSecret;
			$resp = $this->requestHttp($url);
			$this->access_token = $resp['access_token'];
			$this->expires_time = time();
			file_put_contents('utils/access_token.json', '{"access_token": "'.$this->access_token.'", "expires_time": '.$this->expires_time.'}');
		}
	}
	
	//获取签名包
	public function getSignPackage() {
		$jsApiTicket = $this->getJsApiTicket();
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$str = "jsapi_ticket=$jsApiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($str);
		$signPackage = array(
			'appId' => $this->_appId,
			'nonceStr' => $nonceStr,
			'timestamp' => $timestamp,
			'url' => $url,
			'signature' => $signature,
			'rawString' => $str
		);
		return $signPackage;
	}
	
	//获取JS API Ticket
	public function getJsApiTicket() {
		$res = file_get_contents('utils/jsapi_ticket.json');
		$result = json_decode($res, true);
		$this->jsapi_ticket = $result['jsapi_ticket'];
		$this->jsapi_expire = $result['jsapi_expire'];
		
		if(time() > ($this->jsapi_expire + 6400)) {
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$this->access_token;
			$result = $this->requestHttp($url);
			$this->jsapi_ticket = $result['ticket'];
			$this->jsapi_expire = time();
			file_put_contents('utils/jsapi_ticket.json', '{"jsapi_ticket":"'.$this->jsapi_ticket.'","jsapi_expire":"'.$this->jsapi_expire.'"}');
		}
		
		return $this->jsapi_ticket;
	}
	
	//生成随机字符串
	public function createNonceStr($length = 16) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for($i=0;$i<$length;$i++) {
	    	$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	}
	
	//获取OAuth2的URL
	public function getOAuthUrl($redirect_url, $scope, $state = NULL) {
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->_appId."&redirect_uri=".$redirect_url."&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";
		return $url;
	}
	
	//生成OAuth2的access_token
	public function getOAuthToken($code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->_appId."&secret=".$this->_appSecret."&code=".$code."&grant_type=authorization_code";
		$res = $this->requestHttp($url);
		return $res;
	}
	
	//获取用户基本信息（使用OAuth2授权的access_token获取未关注用户，access_token为临时获取）
	public function getUserBaseInfo($access_token, $openid) {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
		$res = $this->requestHttp($url);
		return $res;
	}
	
	//获取用户列表
	public function getUserList($next_openid = NULL) {
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->access_token."&next_openid=".$next_openid;
		$user_list = $this->requestHttp($url);
		if($user_list['count'] == 10000) {
			$user_list_next = $this->getUserList($next_openid = $user_list['next_openid']);
			$user_list['data']['openid'] = array_merge_recursive($user_list['data']['openid'], $user_list_next['data']['openid']);
		}
		return $user_list;
	}
	
	//获取用户基本信息
	public function getUserInfo($openid) {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$openid."&lang=zh_CN";
		$resp = $this->requestHttp($url);
		return $resp;
	}
	
	//获取模板消息列表
	public function getTemplateList() {
		$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$this->access_token;
		$resp = $this->requestHttp($url);
		return $resp;
	}
	
	//发送模板消息
	public function sendTemplateMsg($data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token;
		$resp = $this->requestHttp($url, $data);
		return $resp;
	}
	
	//封装HTTP请求
	public function requestHttp($url, $data = NULL) {
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
}