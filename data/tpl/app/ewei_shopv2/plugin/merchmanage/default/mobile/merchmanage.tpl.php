<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>商家后台</title>
</head>
<script src="../addons/ewei_shopv2/static/js/jquery.min.js"></script>
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/merch.css">
<script src="../addons/ewei_shopv2/plugin/merchmanage/static/css/jquery-1.11.3.min.js"></script>
<body>
    <div class="body_box">
        <!-- header -->
      
        <!-- center -->
        <div class="body_center">
                <div class="header_top"><img src="http://paokucoin.com/img/backgroup/navtopbg@2x.png" alt=""></div>
                <div class="header_inner">
                    <div class="inner-top">
                        <div class="info-left" onclick="location.href='<?php  echo mobileUrl('merchmanage/shop')?>'">
                        <?php  if(empty($merchshop)) { ?>
                        <img src="http://paokucoin.com/img/backgroup/touxiang@2x.png" alt="">
                        <?php  } else { ?>
                        <img src="<?php  echo $logo;?>" alt="">
                        <?php  } ?>
                        </div>
                        <div class="info-center" onclick="location.href='<?php  echo mobileUrl('merchmanage/shop')?>'">
                            <div class="store_name"><?php echo !empty($merchshop['merchname'])?$merchshop['merchname']:'未设置商城名称'?></div>
                            <div class="store_desc">1.0版升级中</div>
                        </div>
                        <div class="info-right withoutOpen"><img src="http://paokucoin.com/img/backgroup/erweima.png" alt=""></div>
                    </div>
                    <!-- inner-bottom -->
                    <ul class="inner-bottom">
                        <li class="header_item"><span class="item_num"><?php  echo $viewcount;?></span><span class="item_tit">访问次数</span></li>
                        <li class="header_item"><span class="item_num"><?php  echo $today_order;?></span><span class="item_tit">今日订单</span></li>
                        <li class="header_item"><span class="item_num"><?php  echo $substitute_shipment;?></span><span class="item_tit">待发货</span></li>
                        <li class="header_item"><span class="item_num"><?php  echo $ordercount;?></span><span class="item_tit">累计订单</span></li>
                    </ul>
        
                </div>
                
                <!-- 引流工具-开始 -->
<div class="guide_box">
<p class="guide_tit">引流工具</p >
<div class="guide_info">
<ul class="guide_list">
<li class="guide_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/goods/attract')?>'"><img src="http://paokucoin.com/img/backgroup/yinliu02@2x.png" alt=""></li>
<li class="guide_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/pintuan02@2x.png" alt=""></li>
<li class="guide_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/reward/sendapi/index')?>'"><img src="http://paokucoin.com/img/backgroup/duanxin02@2x.png" alt=""></li>
<li class="guide_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/reward/home/index')?>'"><img src="http://paokucoin.com/img/backgroup/shangjin02@2x.png" alt=""></li>
<li class="guide_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/miaosha02@2x.png" alt=""></li>
<li class="guide_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/hongbao02@2x.png" alt=""></li>
</ul>
</div>
</div>
<!-- 引流工具-结束 -->
                <!-- 工具 -开始-->
                <div class="tool_box">
                    <p class="tool_tit">工具</p>
                    <div class="tool_info">
                        <ul class="tool_list">
                            <li class="tool_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/goods/attract')?>'"><img src="http://paokucoin.com/img/backgroup/goods@2x.png" alt=""><span class="item-tit">商品</span></li>
                            <li class="tool_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/customer@2x.png" alt=""><span class="item-tit">客户</span></li>
                            <li class="tool_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/order', array('status'=>1))?>'"><img src="http://paokucoin.com/img/backgroup/order@2x.png" alt=""><span class="item-tit">订单</span></li>
                            <li class="tool_item " onclick="location.href='<?php  echo mobileUrl('merchmanage/goods/show')?>'"><img src="http://paokucoin.com/img/backgroup/marketing@2x.png" alt=""><span class="item-tit">橱窗</span></li>
                            <li class="tool_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/apply/manage')?>'"><img src="http://paokucoin.com/img/backgroup/Accounts@2x.png" alt=""><span class="item-tit">结算</span></li>
                              <li class="tool_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/dongtai@2x.png" alt=""><span class="item-tit">发动态</span></li>
                            <li class="tool_item withoutOpen"><img src="http://paokucoin.com/img/backgroup/ticket@2x.png" alt=""><span class="item-tit">满减券</span></li>
                           
                            <li class="tool_item" onclick="location.href='<?php  echo mobileUrl('merchmanage/shop')?>'"><img src="http://paokucoin.com/img/backgroup/store@2x.png" alt=""><span class="item-tit">设置</span></li>
                        </ul>
                    </div>
                </div>
                <!-- 工具 -结束-->
                <!-- 选项卡-开始 -->
                <div class="tabbox">
                    <ul>
                        <li class="active">店铺数据</li>
                        <li >流量分析</li>
                        <li>商品分析</li>
                    </ul>
                    <div class="content">
                            <div class="active">
                                <div class="list_child">
                                    <li class="child-item"><span><?php  echo $today_order;?></span><span>今日订单数</span></li>
                                    <li class="child-item"><span><?php  echo $today_price;?></span><span>今日成交额</span></li>
                                    <li class="child-item"><span><?php  echo $orderprice;?></span><span>累计成交</span></li>
                                    <li class="child-item"><span><?php  echo $substitute_shipment;?></span><span>待发货</span></li>
                                    <li class="child-item"><span><?php  echo $order_percent;?></span><span>订单转化率</span></li>
                                    <li class="child-item"><span><?php  echo $vip_percent;?></span><span>会员消费率</span></li>
                                    <li class="child-item"><span><?php  echo $goodscount;?></span><span>在售商品</span></li>
                                    <li class="child-item"><span><?php  echo $member_count;?></span><span>总会员数</span></li>
                                    <li class="child-item"><span><?php  echo $viewcount;?></span><span>总访问数</span></li>
                                </div> 
                            </div>
                            <div>
                                <div>暂未开放，敬请期待</div>
                            </div>
                            <div>暂未开放，敬请期待</div>
                    </div>
                </div>
                <!-- 选项卡-结束 -->
        </div>
        <!-- footer-开始 -->
        <?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('merchmanage/_menu', TEMPLATE_INCLUDEPATH)) : (include template('merchmanage/_menu', TEMPLATE_INCLUDEPATH));?>
        <!-- 底部导航-结束 -->
    </div>
    <!-- 暂未开放提示 -->
<div id="notOpen">
<div class="mask-text">暂未开放，敬请期待</div>
</div>
</body>

<script>
    $(function (){

　　$(".tabbox li").click(function ()
　　{
　　　　//获取点击的元素给其添加样式，讲其兄弟元素的样式移除
　　　　$(this).addClass("active").siblings().removeClass("active");
　　　　//获取选中元素的下标
　　　　var index = $(this).index();
　　　　$(this).parent().siblings().children().eq(index).addClass("active")
　　　　.siblings().removeClass("active");
　　});

    $(".footernav").click(function(){
        $(this).addClass("selected").siblings().removeClass("selected");
        
    });
    
    $(".withoutOpen").click(function(){
    	$("#notOpen").show().delay(1000).hide(300);
    	})
    
});

</script>
</html>
