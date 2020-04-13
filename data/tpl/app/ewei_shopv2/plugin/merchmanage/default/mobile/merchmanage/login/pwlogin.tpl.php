<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商户登录</title>
</head>
<script src="../addons/ewei_shopv2/static/js/jquery.min.js"></script>
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/Accountlogin.css">
<script src="../addons/ewei_shopv2/plugin/merchmanage/static/css/jquery-1.11.3.min.js"></script>
<body>
      
    <div class="account-body">
       <div class="account-body-center">
            <!-- 密码重置 -->
        <div class="reset-box"><a style="color:#666;font-size:18px" href="<?php  echo mobileUrl('merchmanage/login/mobile_code')?>">密码重置</a></div>
        <p style="color:#333;font-size:26px">您好,</p>
        <p style="color:#333;font-size:26px;margin-top: 8px">请输入账号密码</p>
        <div class="account_info">
            <div class="ipt_box">
                <div class="img01_box"><img src="http://paokucoin.com/img/backgroup/mobile@2x.png" alt=""></div>
                    <input type="text" placeholder="请输入账号" id="ipt1" class="telphone">
                </div>
            <div class="ipt_box">
                <div class="img01_box"><img src="http://paokucoin.com/img/backgroup/mima@2x.png" alt=""></div>
                <input type="password" placeholder="请输入密码" id="ipt2" class="password">
            </div>
        </div>
        <div class="btn-box01"><button type="button" class="account-btn" style="color:#9a9a9a;font-size: 17px">登录</button></div>
        <div class="btn-box02"><button type="button" class="Rapid-btn" style="color:#333;font-size: 17px">快捷登录</button></div>
       </div>
    </div>
     <!-- 错误提示 -->
     <div id="warning">
        <div class="mask-text"></div>
    </div>
</body>

<script type="text/javascript">	
   $("input").bind("input propertychange change",function(event){		
		var telphone = $(".telphone").val().trim().length;
		var pswd = $(".password").val().trim().length;
		if(telphone > 0 && pswd > 0){

            $(".account-btn").css({'color':'#FFFFFF','background':'#008be4'});
		}else{
			$(".account-btn").css({'color':'#333','background':'#a9a9a9'});
		}
	}); 
$(".account-btn").bind("click",function(){
    $.ajax({
        type: 'POST',
        url: '<?php  echo mobileUrl('merchmanage/login/loginapi')?>' ,
        data: {
            "username":$("#ipt1").val(),
            "password":$("#ipt2").val()
        } ,
        dataType: "json", 
        success: function(data){
            console.log(data)
            var message=data.result.message
            if(data.status=="0"){
                console.log("error")
                $("#warning").show().delay(1000).hide(300);
            $("#warning .mask-text ").text(message);
            
            }else{
                location.href='<?php  echo mobileUrl('merchmanage')?>';
            }
        } ,
        });        

	});
	
//快捷登录的点击事件
//快捷登录的点击事件
$(".Rapid-btn").bind("click",function(){
if (isWeixin()) {
location.href='<?php  echo mobileUrl('merchmanage/login/wx_login')?>';
} else {
$("#warning").show().delay(1000).hide(300);
$("#warning .mask-text ").text('请在微信端打开');
}
//判断是否在微信中打开
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
