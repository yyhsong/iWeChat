<?php
require_once 'utils/WechatObj.php';
$wechatObj = new WechatObj();
$signPackage = $wechatObj->getSignPackage();
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>微信JSSDK - 获取地理位置信息</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css" />
    </head>
    <body ontouchstart="">
    	<p>获取地理位置信息</p>
    	
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
			        'getLocation',
			        'openLocation'
    			]
    		});
    		
    		//基本调用形式
    		wx.ready(function() {
				//获取地理位置信息
				wx.getLocation({
					type: 'gcj02', //默认wgs84，openLocation使用gcj02
					success: function(res) {
						var lng = res.longitude; //经度
						var lat = res.latitude; //纬度
						var speed = res.speed; //速度，米/秒
						var accuracy = res.accuracy; //位置精度
						
						var info = "经度："+lng+"\n纬度："+lat+"\n速度："+speed+"\n精度："+accuracy;
						alert(info);
						
						//使用微信内置地图查看位置
						wx.openLocation({
							latitude: lat,
							longitude: lng,
							name: '位置名称',
							address: '地址详情说明',
							scale: 28, //地图缩放级别，1~28
							infoUrl: '' //在查看位置界面底部显示的超链接，可点击跳转
						});
					},
					cancel: function(res) {
						alert("用户拒绝授权获取地理位置信息");
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