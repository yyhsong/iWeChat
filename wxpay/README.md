### 微信支付 - 公众号支付

1、配置公众号：网页授权域名；

2、商户平台，配置JSAPI支付授权目录；

3、使用网页授权获取用户openid;

4、调用统一下单接口：https://api.mch.weixin.qq.com/pay/unifiedorder;

	参数：
	- appid 公众号appId
	- mch_id 商户号
	- nonce_str 随机字符串，自行生成，32位以内
	- sign 使用微信支付的签名算法自行生成
	- sign_type 默认MD5
	- body 商品描述，格式：商家名称-销售商品类目
	- detail 商品详情
	- out_trade_no 商户订单号，32个字符以内，且在同一商户号下唯一
	- total_fee 订单总金额，单位为分
	- spbill_create_ip 终端IP
	- time_start 交易起始时间，即订单生成时间，格式：yyyyMMddHHmmss
	- time_expire 交易结束时间，即订单失效时间，格式：yyyyMMddHHmmss
	- notify_url 通知地址，异步接收微信支付结果通知的回调地址（并非前端页面的跳转地址），不能携带参数
	- trade_type 交易类型，JSAPI、NATIVE、APP
	- openid 用户标识，交易类型为JSAPI时必需
	- ... 向服务器POST的xml格式的参数
	
	返回：
	- return_code 返回状态码，通信标识，SUCCESS/FAIL
	- return_msg 返回信息
	- result_code 业务结果，交易标识，SUCCESS/FAIL
	- err_code 错误代码
	- err_code_des 错误代码描述
	- appid
	- mch_id
	- nonce_str 微信返回的随机字符串
	- sign 微信返回的签名值
	- trade_type 交易类型，JSAPI、NATIVE、APP
	- prepay_id 微信生成的预支付交易会话标识，用于后续的接口调用，有效期2个小时
	- ... 从服务器返回的xml数据
	
5、微信内网页调起支付：在微信浏览器里打开网页执行JS调起支付，接口输入输出数据格式为JSON。
   
	WeixinJSBridge.invoke("getBrandWCPayRequest", parameters, callback(res))
	
	参数：
    - appId 公众号appId
    - timeStamp 当前时间戳
    - nonceStr 随机字符串，不长于32位
    - package 订单详情扩展字段，统一下单接口返回的prepay_id参数值，格式prepay_id=***
    - signType 签名类型，默认MD5，支持HMAC-S、HA256，此处需与统一下单的签名类型一致
    - paySign 签名
    - ... 参与签名的参数为：appId、timeStamp、nonceStr、package、signTpe，区分大小写
    
	返回：
    - get_brand_wcpay_request:ok 支付成功
    - get_brand_wcpay_request:cancel 支付过程中用户取消
    - get_brand_wxpay_request:fail 支付失败
    - 调用支付JSAPI缺少参数：total_fee 检查预支付会话标识prepay_id是否已失效
    
6、用户支付成功并点击完成按钮

	- 前端：WeixinJSBridge.invoke()的回调函数会收到关于支付成功的返回值，可根据该结果跳转到相应的页面进行展示。
	- 后台：统一下单接口中定义的notify_url，收到来自微信平台的支付成功回调通知，标志该笔订单支付成功。
	- 以上两步为异步进行，触发不保证遵循严格的时序，JS API返回值作为触发商户网页跳转的标志，但商户后台应该只在收到微信后台的支付成功通知后，才做真正的支付成功处理。