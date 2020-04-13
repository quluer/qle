<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>广告赏金任务</title>
</head>
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/reward/Home01.css">
<script src="../addons/ewei_shopv2/static/js/jquery.min.js"></script>
<script src="../addons/ewei_shopv2/plugin/merchmanage/static/css/reward/jquery-1.11.3.min.js"></script>
<body>
    <div class="home01_body">
        <!-- header -->
        
        <!-- header -->
        <!-- content -->
        <div class="home01-article">
            <div class="home01-article-center">
                <div class="article-top">
                    <!-- 广告金账户 -->
                    <div class="account-reward">
                        <div class="reward-top">
                            <div class="reward-top-text">广告赏金账户(卡路里)</div>
                            <!-- 充值按钮 -->
                            <button class="recharge_btn" id="rechangebtn">充值</button>
                        </div>
                        <div class="reward-totalmoney">
                            <div class="money-img"><img src="http://paokucoin.com/img/backgroup/reward.png" alt=""></div>
                            <div class="money-symbol">￥</div>
                            <div class="money-num"></div>
                        </div>
                        <div class="task-explain">
                            <div class="task-left" id="explainbtn">
                                <div class="explain-img"><img src="http://paokucoin.com/img/backgroup/explain01.png" alt=""></div>
                                <div class="explain-text">任务说明</div>
                            </div>
                            <!-- 明细 -->
                            <div class="task-right">
                                <div class="detail-text" id="detailbtn">明细</div>
                                <div class="detail-img"><img src="http://paokucoin.com/img/backgroup/right01.png" alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="article-bottom">
                        <div class="reward-info">
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- content -->
        <!-- footer -->
        <footer class="home01-footer">
            <button class="footer-post-btn" id="postBtn">发布任务</button>
        </footer>
        <!-- footer -->
    </div>
    <!-- 错误提示 -->
    <div id="warning">
            <div class="mask-text"></div>
        </div>
         <!-- 任务说明 -->
    <div id="taskexplain">
        <div class="taskexplain_explain">
            <div class="taskexplain-text">全店赏金（全部商品）和指定商品的赏金任务不能同时进行，当发生时冲突时，以新发布的为准，正在进行中的任务则自动结束。</div>
            <div class="closeBtn"><img src="http://paokucoin.com/img/backgroup/guanbi.png" alt=""></div>
        </div>
    </div>
</body>

<script type="text/javascript">
var merchCard=''
var str1=''
	// 任务说明
    $("#explainbtn").bind("click",function(){
        // $("#warning").show().delay(1000).hide(300);
        // $("#warning .mask-text ").text(message);
        $("#taskexplain").css('display','block')
    })
    
    $(".closeBtn").bind("click",function(){
        $("#taskexplain").css('display','none')
    })
    
$("#rechangebtn").bind("click",function(){
            location.href="<?php  echo mobileUrl('merchmanage/reward/home/recharge')?>"
})

$("#detailbtn").bind("click",function(){
            location.href="<?php  echo mobileUrl('merchmanage/reward/home/log')?>"
})

$("#postBtn").bind("click",function(){
            location.href="<?php  echo mobileUrl('merchmanage/reward/home/release')?>"
})
$(window).load(function() {
    // 商家余额
    $.ajax({
            type: 'POST',
            url:'<?php  echo mobileUrl('merchmanage/reward/index/shop_reward')?>',
            dataType: "json", 
            success: function(data){
                console.log(data)
                merchCard=Number(data.result.card)
                console.log(merchCard) 
                $(".money-num").text(data.result.card)
            }
        })

    // 任务列表
    $.ajax({
            type: 'POST',
            url: '<?php  echo mobileUrl('merchmanage/reward/index/go_reward')?>' ,
            data: {
            "page":"1"
                } ,
            dataType: "json", 
            success: function(data){
                console.log(data)
                $.each(data.result.list, function(i, item) {
                console.log(item.id);
                if(item.type==2){
                str1 += '<div class="rewardCon">'+'<div class="reward-info-top">'+
                            '<li class="info-item">'+'<span class="item-text01">'+'全店赏金'+'</span>'+'<i class="item-num01">'+'进行中'+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'分享次数'+'</span>'+'<i class="item-num">'+item.share+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'点击次数'+'</span>'+'<i class="item-num">'+item.click+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'支出'+'</span>'+'<i class="item-num">'+item.commission_count+'</i>'+'</li>'+
                        '</div>'+
                        '<div class="reward-info-bottom">'+
                            '<div class="post-time">'+'发布于'+item.create_time+'</div>'+
                            '<div class="btnBox">'+
                                // '<button class="product-btn">'+'商品'+'</button>'+
                                '<button class="post-end-btn" data-id='+item.id+'>'+'结束'+'</button>'+
                            '</div>'+
                        '</div>'+'</div>'
                }else if(item.type==1){
                    str1 += '<div class="rewardCon">'+'<div class="reward-info-top">'+
                            '<li class="info-item">'+'<span class="item-text01">'+'指定赏金'+'</span>'+'<i class="item-num01">'+'进行中'+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'分享次数'+'</span>'+'<i class="item-num">'+item.share+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'点击次数'+'</span>'+'<i class="item-num">'+item.click+'</i>'+'</li>'+
                            '<li class="info-item">'+'<span class="item-text">'+'支出'+'</span>'+'<i class="item-num">'+item.commission_count+'</i>'+'</li>'+
                        '</div>'+
                        '<div class="reward-info-bottom">'+
                            '<div class="post-time">'+'发布于'+item.create_time+'</div>'+
                            '<div class="btnBox">'+
                                // '<button class="product-btn" data-id='+item.id+'>'+'商品'+'</button>'+
                                '<button class="post-end-btn" data-id='+item.id+'>'+'结束'+'</button>'+
                            '</div>'+
                            '</div>'+'</div>'
                }
                $(".reward-info").html(str1)
        })
            }
        })
        // $(".reward-info").on("click",".product-btn",function(){
        //        console.log($(this).attr("data-id"))
            
        //     })
        $(".reward-info").on("click",".post-end-btn",function(){
           var rewardId=$(this).attr("data-id")
           console.log(rewardId)
           $.ajax({
            type: 'POST',
            url: '<?php  echo mobileUrl('merchmanage/reward/index/end_reward')?>' ,
            data: {
            "reward_id":rewardId
                } ,
            dataType: "json", 
            success: function(data){
                console.log(data)
                var message=data.result.message
                if(data.status==0){
                    $("#warning").show().delay(1000).hide(300);
                    $("#warning .mask-text ").text(message);
                }else{
                	 $("#warning").show().delay(1000).hide(300);
                     $("#warning .mask-text ").text(message);
                     location.reload();
                }
            }
        })
       
        })
        
        
 })

</script>

</html>
