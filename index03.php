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
	
	//发送被动回复消息：text、image、voice、video、music、news
	public function responseMsg() {
		$postStr = $GLOBALS['HTTP_RAW_POST_DATA']; //获取从微信服务器POST过来的原始消息数据包
		
		if(!empty($postStr)) {
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //XML解析
			$fromUserName = $postObj->FromUserName;
			$toUserName = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			
			if($keyword == '文本') {
				$this->responseTextMsg($fromUserName, $toUserName);
			}else if($keyword == '图片') {
				$this->responseImageMsg($fromUserName, $toUserName);
			}else if($keyword == '语音') {
				$this->responseVoiceMsg($fromUserName, $toUserName);
			}else if($keyword == '视频') {
				$this->responseVideoMsg($fromUserName, $toUserName);
			}else if($keyword == '音乐') {
				$this->responseMusicMsg($fromUserName, $toUserName);
			}else if($keyword == '图文') {
				$this->responseNewsMsg($fromUserName, $toUserName);
			}else {
				echo ""; //如果不回复客户消息，可以返回空字符串或success，但必须使用双引号
			}
		}else {
			exit;
		}
	}
	
	//发送文本消息
	private function responseTextMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[text]]></MsgType>
			       <Content><![CDATA[%s]]></Content>
				   </xml>";
		
		$createTime = time();
		$content = '再小的个体，也有自己的品牌！';
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime, $content);
		echo $result;
	}
	
	//发送图片消息
	private function responseImageMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[image]]></MsgType>
			       <Image>
			       <MediaId><![CDATA[3xCukESEdLhO4Er8AH3g4RYs-9tSizVOCrGkWahquYoJeP118m-L1Kox1UdN_uWz]]></MediaId>
			       </Image>
				   </xml>";
		
		$createTime = time();
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime);
		echo $result;
	}
	
	//发送语音消息
	private function responseVoiceMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[voice]]></MsgType>
			       <Voice>
			       <MediaId><![CDATA[E8K42hbNYA2QsdoahXgX3YA4S9WT8sWhF9ERkdi0uWtBB8V1dyups1n1PaPYIG5A]]></MediaId>
			       </Voice>
				   </xml>";
		
		$createTime = time();
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime);
		echo $result;
	}
	
	//发送视频消息
	private function responseVideoMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[video]]></MsgType>
			       <Video>
			       <MediaId><![CDATA[7EcOoJe4bgmqdpAaIxgkgCtlkh5chdx_PsvqcKHhXMS-4FlbsPw3JTTH2VKpLIxJ]]></MediaId>
			       <ThumbMediaId><![CDATA[LggSgPIEmG4ZMTJnjE2AecHVE_7kK3b6GjeGlMXeprONYq91_7VqtGNKsDY-TDmS]]></ThumbMediaId>
			       <Title><![CDATA[快乐的摇摇车]]></Title>
				   <Description><![CDATA[小美女，小帅哥，快来玩啊]]></Description>
			       </Video>
				   </xml>";
		
		$createTime = time();
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime);
		echo $result;
	}
	
	//发送音乐消息
	private function responseMusicMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[music]]></MsgType>
			       <Music>
			       <Title><![CDATA[最炫民族风]]></Title>
				   <Description><![CDATA[凤凰传奇]]></Description>
			       <MusicUrl><![CDATA[http://zj189.cn/zj/download/music/zxmzf.mp3]]></MusicUrl>
			       <HQMusicUrl><![CDATA[http://zj189.cn/zj/download/music/zxmzf.mp3]]></HQMusicUrl>
			       </Music>
				   </xml>";
		
		$createTime = time();
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime);
		echo $result;
	}
	
	//发送图文消息
	private function responseNewsMsg($fromUserName, $toUserName) {
		//回复消息模板
		$msgTpl = "<xml>
			       <ToUserName><![CDATA[%s]]></ToUserName>
			       <FromUserName><![CDATA[%s]]></FromUserName>
			       <CreateTime>%s</CreateTime>
			       <MsgType><![CDATA[news]]></MsgType>
			       <ArticleCount>2</ArticleCount>
			       <Articles>
			       <item>
			       <Title><![CDATA[权利的游戏]]></Title>
				   <Description><![CDATA[第七季，2017年7月17日回归！]]></Description>
			       <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/ibjoknupYqtMx4d5bABRIXdFC7vLdcicA2CQpicMCI9clHcJyQIf5GyC17h1EpCkAec1HHEytWicdC3NJPWsaPdLPw/0]]></PicUrl>
				   <Url><![CDATA[]]></Url>
			       </item>
			       <item>
			       <Title><![CDATA[宽带咨询]]></Title>
				   <Description><![CDATA[用户办理家庭承诺消费送宽带及个人承诺消费送宽带2年期活动，即可在活动协议期前12个月每月获赠1GB本地流量。]]></Description>
			       <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/ibjoknupYqtMx4d5bABRIXdFC7vLdcicA2CQpicMCI9clHcJyQIf5GyC17h1EpCkAec1HHEytWicdC3NJPWsaPdLPw/0]]></PicUrl>
				   <Url><![CDATA[]]></Url>
			       </item>
			       </Articles>
				   </xml>";
		
		$createTime = time();
				   
		$result = sprintf($msgTpl, $fromUserName, $toUserName, $createTime);
		echo $result;
	}
}