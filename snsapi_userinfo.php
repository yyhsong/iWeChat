<?php
//页面链接：http://www.abc.com/snsapi_userinfo.php?code=xxx&state=xxx

require_once 'utils/WechatObj.php';

$wechatObj = new WechatObj();

if(isset($_GET["code"])){
    $oauth_access_token = $wechatObj->getOAuthToken($_GET["code"]);
	//var_dump($oauth_access_token);exit;
    $userinfo = $wechatObj->getUserBaseInfo($oauth_access_token['access_token'], $oauth_access_token['openid']);
}else{ //请求中没有code参数，重定向到当前页面，获取code
	$redirect_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $jumpurl = $wechatObj->getOAuthUrl($redirect_url, "snsapi_userinfo", "2");
    Header("Location: $jumpurl");
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0" />
        <title>微信网页授权snsapi_userinfo</title>
        <link rel="stylesheet" href="https://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css" />
    </head>
    <body ontouchstart="">
    	<div class="weui-form-preview">
		    <div class="weui-form-preview__hd">
		        <label class="weui-form-preview__label">用户基本信息</label>
		        <em class="weui-form-preview__value">&nbsp;</em>
		    </div>
		    <div class="weui-form-preview__bd">
		        <p>
		            <label class="weui-form-preview__label">OpenId</label>
		            <span class="weui-form-preview__value">
		            	<?php echo $userinfo["openid"];?>
		            </span>
		        </p>
		        <p>
		            <label class="weui-form-preview__label">昵称</label>
		            <span class="weui-form-preview__value">
		            	<?php echo $userinfo["nickname"];?>
		            </span>
		        </p>
		        <p>
		            <label class="weui-form-preview__label">头像</label>
		            <span class="weui-form-preview__value">
		            	<img src="<?php echo str_replace("/0", "/46", $userinfo["headimgurl"]); ?>">
		            </span>
		        </p>
		        <p>
		            <label class="weui-form-preview__label">性别</label>
		            <span class="weui-form-preview__value">
		            	<?php echo $userinfo["sex"];?>
		            </span>
		        </p>
		        <p>
		            <label class="weui-form-preview__label">地区</label>
		            <span class="weui-form-preview__value">
		            	<?php echo $userinfo["country"];?>
		            </span>
		        </p>
		        <p>
		            <label class="weui-form-preview__label">语言</label>
		            <span class="weui-form-preview__value">
		            	<?php echo $userinfo["language"];?>
		            </span>
		        </p>
		    </div>
		    <div class="weui-form-preview__ft">
		        <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</a>
		    </div>
		</div>
    </body>
</html>