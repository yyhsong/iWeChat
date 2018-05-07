<?php
header('Content-type:text');

//定义常量TOKEN
define('TOKEN', 'iwechat');

$wechatObj = new WechatObj();

//判断是验证开发者服务器还是接收消息
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
	
	//处理事件消息：关注、取消关注
	public function responseMsg() {
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //获取从微信服务器POST过来的原始消息数据包
		
		if(!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //XML解析
			$fromUserName = $postObj->FromUserName;
			$toUserName = $postObj->ToUserName;
			$msgType = $postObj->MsgType;
			
			$result = "";
			if($msgType == 'event') {
				$event = $postObj->Event;
				if($event == 'subscribe') { //关注
					$result = $this->responseSubscribeMsg($fromUserName, $toUserName);
				}else if($event == 'unsubscribe') { //取消关注
					$result = $this->responseUnsubscribeMsg($fromUserName, $toUserName);
				}
			}
			echo $result;
		}else {
			exit;
		}
	}
	
	//回复关注消息
	private function responseSubscribeMsg($fromUserName, $toUserName) {
		$content = '感谢您关注本公众号！';
		$result = $this->createTextMsg($fromUserName, $toUserName, $content);
		return $result;
	}
	
	//回复取关消息
	private function responseUnsubscribeMsg($fromUserName, $toUserName) {
		$content = '取消关注！';
		$result = $this->createTextMsg($fromUserName, $toUserName, $content);
		return $result;
	}
	
	//构造文本消息
	private function createTextMsg($fromUserName, $toUserName, $content) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[text]]></MsgType>
			       <Content><![CDATA[%s]]></Content>
				   </xml>";
		
		$createTime = time();
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime, $content);
		return $result;
	}
	
}