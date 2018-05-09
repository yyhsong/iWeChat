<?php
require_once 'utils/WechatObj.php';
$wechatObj = new WechatObj();
//生成签名包 - 只能在服务器端进行
$signPackage = $wechatObj->getSignPackage();
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>微信JSSDK - 分享到朋友圈</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css" />
    </head>
    <body ontouchstart="">
    	<p>点击右上角分享后查看</p>
    	
    	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    	<script type="text/javascript">
    		//基本配置
    		wx.config({
    			debug: false,
    			appId: '<?php echo $signPackage["appId"] ?>',
    			timestamp: '<?php echo $signPackage["timestamp"] ?>',
    			nonceStr: '<?php echo $signPackage["nonceStr"] ?>',
    			signature: '<?php echo $signPackage["signature"] ?>',
    			jsApiList: [ //所有要调用的API列表
    				'checkJsApi',
			        'onMenuShareTimeline',
			        'onMenuShareAppMessage'
    			]
    		});
    		
    		//基本调用形式
    		wx.ready(function() {
				//分享到朋友圈
				wx.onMenuShareTimeline({
					title: 'Neo的微信开发学习笔记',
					link: 'http://wx.prajnax.cn/share.html', //与JS接口安全域名一致
					imgUrl: "http://avatar.csdn.net/A/8/B/1_testing.jpg",
					trigger: function(res) {
						alert("用户点击分享到朋友圈");
					},
					success: function(res) {
						alert("分享成功");
						//分享成功后的业务处理
						//window.location.href = '';
					},
					cancel: function(res) {
						alert("用户取消分享");
					},
					fail: function(res) {
						alert("分享失败");
					}
				});
    		});
    		
    		//配置错误时调用
    		wx.error(function(res) {
    			alert("Error: " + res.errMsg);
    		});
    	</script>
    </body>
</html>