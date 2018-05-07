<?php
header('Content-type:text');

//定义常量TOKEN
define('TOKEN', 'iwechat');

$wechatObj = new WechatObj();

//判断是验证开发者服务器还是回复消息
if(isset($_GET['echostr'])) {
	$wechatObj->validServer();
}else {
	$wechatObj->responseMsg();
}

class WechatObj {
	//验证开发者服务器
	public function validServer() {
		$echostr = $_GET['echostr'];
		if($this->checkSignature()) {
			echo $echostr;
			exit;
		}
	}
	
	//校验签名
	private function checkSignature() {
		$token = TOKEN;
		
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];
		
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING); //字典序排序
		$tmpStr = implode($tmpArr);
		$hashCode = sha1($tmpStr); //加密
		
		if($hashCode == $signature) {
			return true;
		}else {
			return false;
		}
	}
	
	//回复特定的文本消息
	public function responseMsg() {
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //获取从微信服务器POST过来的原始消息数据包
		
		if(!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //XML解析
			$fromUserName = $postObj->FromUserName;
			$toUserName = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$createTime = time();
			$msgType = 'text';
			
			$msgTpl = "<xml>
				       <ToUserName><![CDATA[%s]]></ToUserName>
				       <FromUserName><![CDATA[%s]]></FromUserName>
				       <CreateTime>%s</CreateTime>
				       <MsgType><![CDATA[%s]]></MsgType>
				       <Content><![CDATA[%s]]></Content>
				       <FuncFlag>0</FuncFlag>
					   </xml>";
					   
			if($keyword == 'test' || $keyword == '测试') {
				$content = 'timestamp: '.$_GET['timestamp']."\n".
						   'nonce: '.$_GET['nonce']."\n".
						   'signature: '.$_GET['signature']."\n".
						   'content: '.$keyword."\n".
						   'post: '.$postStr;
				$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime, $msgType, $content);
				echo $result;
			}else {
				echo ""; //如果不回复客户消息，可以返回空字符串或success，但必须使用双引号
			}
		}else {
			exit;
		}
	}

}