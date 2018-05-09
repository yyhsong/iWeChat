<?php
require_once 'utils/WechatObj.php';
$wechatObj = new WechatObj();
//生成签名包，只能在服务器端进行
$signPackage = $wechatObj->getSignPackage();
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>微信JSSDK的基本使用</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css" />
    </head>
    <body ontouchstart="">
    	<p id="info"></p>
    	
    	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    	<script type="text/javascript">
    		//通过config接口注入权限验证配置
    		wx.config({
    			debug: true,
    			appId: '<?php echo $signPackage["appId"] ?>',
    			timestamp: '<?php echo $signPackage["timestamp"] ?>',
    			nonceStr: '<?php echo $signPackage["nonceStr"] ?>',
    			signature: '<?php echo $signPackage["signature"] ?>',
    			jsApiList: [ //所有要调用的API列表
    				'checkJsApi',
			        'onMenuShareTimeline',
			        'onMenuShareAppMessage',
			        'onMenuShareQQ',
			        'onMenuShareWeibo',
			        'onMenuShareQZone',
			        'hideMenuItems',
			        'showMenuItems',
			        'hideAllNonBaseMenuItem',
			        'showAllNonBaseMenuItem',
			        'translateVoice',
			        'startRecord',
			        'stopRecord',
			        'onVoiceRecordEnd',
			        'playVoice',
			        'onVoicePlayEnd',
			        'pauseVoice',
			        'stopVoice',
			        'uploadVoice',
			        'downloadVoice',
			        'chooseImage',
			        'previewImage',
			        'uploadImage',
			        'downloadImage',
			        'getNetworkType',
			        'openLocation',
			        'getLocation',
			        'hideOptionMenu',
			        'showOptionMenu',
			        'closeWindow',
			        'scanQRCode',
			        'chooseWXPay',
			        'openProductSpecificView',
			        'addCard',
			        'chooseCard',
			        'openCard'
    			]
    		});
    		
    		//基本调用形式
    		wx.ready(function() {
    			//检查是否支持某些特定API
    			wx.checkJsApi({
    				jsApiList: [
    					'getNetworkType'
    				],
    				success: function(res) {
    					console.log("CheckJsApi: ", res);
    				}
    			});
    			
    			//获取网络状态
			  	wx.getNetworkType({
			  		success: function(res) {
			  			//console.log("NetworkType: " + res.networkType);
			  			document.getElementById("info").innerHTML = '网络类型：' + res.networkType;
			  		}
			  	});
    		});
    		
    		//配置错误时调用
    		wx.error(function(res) {
    			alert(res.errMsg);
    		});
    	</script>
    </body>
</html>