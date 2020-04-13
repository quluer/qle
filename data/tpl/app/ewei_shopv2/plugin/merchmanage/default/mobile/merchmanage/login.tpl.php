<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商户登录</title>
</head>
<script src="../addons/ewei_shopv2/static/js/jquery.min.js"></script>
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/login01.css">
<script src="../addons/ewei_shopv2/plugin/merchmanage/static/css/jquery-1.11.3.min.js"></script>
<body>
    <!-- header -开始-->
   
    <!-- header-结束 -->
    <div class="login-top">
        <div class="zhihui"></div>
        <div class="tubiao"></div>
        <div class="tubiao_desc">跑库商家</div>
    </div>
    <div class="denglu_pic"><img src="http://paokucoin.com/img/backgroup/denglu-pic@2x.png" alt=""></div>
    <div class="btn_box">
    
        <button id="btn01"><a style="color:#008be4" href="<?php  echo mobileUrl('merchmanage/login/pwlogin')?>">账号登录</a></button>
        <button id="btn02">快捷登录</button> 
    </div>
    <!-- 错误提示 -->
    <div id="warning">
        <div class="mask-text"></div>
    </div>
</body>

<script type="text/javascript">

// 快捷登录的点击事件
$("#btn02").bind("click",function(){
if (isWeixin()) {
location.href='<?php  echo mobileUrl('merchmanage/login/wx_login')?>';
} else {
$("#warning").show().delay(1000).hide(300);
$("#warning .mask-text ").text('请在微信端打开');
}
// 判断是否在微信中打开
function isWeixin() {
var ua = navigator.userAgent.toLowerCase();
if (ua.match(/MicroMessenger/i) == "micromessenger") {
return true;
} else {
return false;
}
}
})
</script>
</html>
