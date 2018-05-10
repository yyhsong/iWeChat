<?php
/*
 * 扫描带参数的二维码：
 * 1、如果用户还未关注公众号，则用户可以关注公众号，关注后微信将带场景值关注事件推送给开发者。
 * 2、如果用户已经关注公众号，则微信直接将带场景值事件推送给开发者。
 */

header('Content-type:text');

//定义常量TOKEN
define('TOKEN', 'iwechat');

$wechatObj = new WechatObj();

//判断是验证开发者服务器还是接收消息
if(isset($_GET['echostr'])) {
	$wechatObj->validServer();
}else {
	$wechatObj->scanQrcode();
}

class WechatObj {		
	//处理扫描二维码事件
	public function scanQrcode() {
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //获取从微信服务器POST过来的原始消息数据包
		
		if(!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //XML解析
			$fromUserName = $postObj->FromUserName;
			$toUserName = $postObj->ToUserName;
			$msgType = $postObj->MsgType;
			
			$result = "";
			if($msgType == 'event') {
				switch($postObj->Event) {
					case 'subscribe':
						$content = '新关注，';
						if(isset($postObj->EventKey)) {
							$content .= '二维码参数：'.$postObj->EventKey;
							$result = $this->createTextMsg($fromUserName, $toUserName, $content);
						}
						break;
					case 'SCAN':
						$content = '已关注，';
						if(isset($postObj->EventKey)) {
							$content .= '二维码参数：'.$postObj->EventKey;
							$result = $this->createTextMsg($fromUserName, $toUserName, $content);
						}
						break;
					default: 
						break;					
				}
			}
			echo $result;
		}else {
			exit;
		}
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