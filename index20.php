<?php
	require_once 'utils/WechatObj.php';
	$wechatObj = new WechatObj();
	$data = array(
		"touser" => "oEOxN1i1oKmikIrdrVbq9ZYeLU44",
		"template_id" => "RHXZc6wOHvMp8_RRF9mBC7nv9983eMTG6I7TAxQEa-8",
		"url" => "http://wx.prajnax.cn/share.html",
		"data" => array(
			"type" => array(
				"value" => "手机",
				"color" => "#0000ff"
			),
			"name" => array(
				"value" => "小米Pro",
				"color" => "#00ff00"
			),
			"price" => array(
				"value" => "￥999.00",
				"color" => "#ec0000"
			),
			"description" => array(
				"value" => "只为发烧而生！"
			)
		)
	);
	$resp = $wechatObj->sendTemplateMsg(json_encode($data));
	exit;
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>发送模板消息</title>
    </head>
    <body ontouchstart="">
    	<pre>
			<?php var_dump($resp); ?>
    	</pre>
    </body>
</html>