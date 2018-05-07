<?php
header('Content-type:text');

//定义常量TOKEN
define('TOKEN', 'iwechat');

$wechatObj = new WechatObj();

//判断是验证开发者服务器还是接收消息
if(isset($_GET['echostr'])) {
	$wechatObj->validServer();
}else {
	$wechatObj->receiveMsg();
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
	
	//接收普通消息：text、image、voice、video、location、link
	public function receiveMsg() {
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //获取从微信服务器POST过来的原始消息数据包
		
		if(!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //XML解析
			$fromUserName = $postObj->FromUserName;
			$toUserName = $postObj->ToUserName;
			$createTime = time();
			$msgType = $postObj->MsgType;
			
			switch($msgType) {
				case 'text':
					$content = '文本消息'."\n".'内容：'.trim($postObj->Content);
					break;
				case 'image': 
					$content = '图片消息'."\n".'PicUrl：'.$postObj->PicUrl."\n".'MediaId：'.$postObj->MediaId;
					break;
				case 'voice':
					$content = '语音消息'."\n".'格式：'.$postObj->Format."\n".'MediaId：'.$postObj->MediaId;
					break;
				case 'video':
					$content = '视频消息'."\n".'MediaId：'.$postObj->MediaId."\n".'ThumbMediaId：'.$postObj->ThumbMediaId;
					break;
				case 'location': 
					$content = '地理位置消息'."\n".'维度：'.$postObj->Location_X."\n".'经度：'.$postObj->Location_Y."\n".'地址：'.$postObj->Label;
					break;
				case 'link':
					$content = '链接消息'."\n".'标题：'.$postObj->Title."\n".'链接地址：'.$postObj->Url;
					break;
				default:
					$content = '未知类型消息';
					break;
			}
			
			//回复消息模板
			$msgTpl = "<xml>
				       <ToUserName><![CDATA[%s]]></ToUserName>
				       <FromUserName><![CDATA[%s]]></FromUserName>
				       <CreateTime>%s</CreateTime>
				       <MsgType>text</MsgType>
				       <Content><![CDATA[%s]]></Content>
					   </xml>";
					   
			$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime, $content);
			echo $result;
		}else {
			exit;
		}
	}
}