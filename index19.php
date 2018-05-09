<?php
require_once 'utils/WechatObj.php';
$wechatObj = new WechatObj();
$templates = $wechatObj->getTemplateList();
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>获取模板消息列表</title>
    </head>
    <body ontouchstart="">
    	<pre>
			<?php var_dump($templates); ?>
    	</pre>
    </body>
</html>