<?php
	/*
	 * 生成带参数二维码的本质是在公众号二维码的基础上添加了场景参数。
	 */

	require_once 'utils/WechatObj.php';
	$wechatObj = new WechatObj();
	
	//永久二维码
	$data = array(
		"action_name" => "QR_LIMIT_STR_SCENE",
		"action_info" => [
			"scene" => [
				"scene_str" => "oEOxN1i1oKmikIrdrVbq9ZYeLU44" //场景参数
			]
		]
	);
	$resp = $wechatObj->getQrcode(json_encode($data));
	$ticket = $resp['ticket'];
	
	//下载二维码图片
	$qrcodeUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
	$qrcodePicInfo = $wechatObj->downloadFile($qrcodeUrl);
	$filename = "qrcode.jpg";
	$local_file = fopen($filename, 'w');
	if(false !== $local_file) {
	    if(false !== fwrite($local_file, $qrcodePicInfo["body"])) {
        	fclose($local_file);
	    }
	}
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>生成带参数的二维码</title>
    </head>
    <body ontouchstart="">
    	<pre>
			<?php var_dump($resp); ?>
    	</pre>
    	<p>
    		<img style="width:256px;" 
    			src='<?php echo "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket"; ?>' />
    	</p>
    	<p>
    		<a href='<?php echo "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket"; ?>'>下载二维码</a>
    	</p>
    </body>
</html>