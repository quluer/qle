<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport"
        content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>步数引流</title>
</head>
<link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/steplist.css">

<body>
    <div class="step_body">
        <!-- header -->
        <!-- header -->
        <!-- content -->
        <div class="step_content">
            <div class="content_relative">
                <!-- 暂无内容 -->
                <div class="content_none">
                    <div class="step-explain">
                        <div class="explainImg"><img src="http://paokucoin.com/img/backgroup/explain01.png" alt="">
                        </div>
                        <div class="explainText">步数引流商品必须支持<span style="color:#e01212">全卡路里兑换</span>,建议价格不低于市场价格的20%
                        </div>
                    </div>
                    <div class="none_bg"><img src="http://paokucoin.com/img/backgroup/zanwuneirong@2x.png" alt=""></div>
                    <div class="none_text">暂无内容</div>
                </div>
                <!-- 暂无内容 -->
                <!-- 暂无内容 -->
                <div class="content_list">
                    <div class="list_bg"><img src="http://paokucoin.com/img/backgroup/banner@2x.png" alt=""></div>
                    <div class="step-explain">
                        <div class="explainImg"><img src="http://paokucoin.com/img/backgroup/explain01.png" alt="">
                        </div>
                        <div class="explainText">步数引流商品必须支持<span style="color:#e01212">全卡路里兑换</span>,建议价格不低于市场价格的20%
                        </div>
                    </div>
                    <div class="listBox">
                        <ul class="list_box">

                        </ul>
                    </div>
                </div>
                <!-- 暂无内容 -->
            </div>
        </div>
        <!-- content -->
        <!-- footer -->
        <div class="step_footer"><a href="/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.add"> 上传商品</a></div>
        <!-- footer -->
    </div>
</body>

</html>
<script src="../addons/ewei_shopv2/static/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    var str1 = ""
    var str2=""
    $(window).load(function () {
        //  商品列表
        $.ajax({
            type: 'POST',
            url: '<?php  echo mobileUrl('merchmanage/goods/getlist')?>' ,
            data: {
                "stepfrom": '1',
                "page": "1"
            },
            dataType: "json",
            success: function (data) {
                console.log(data)
                if (data.result.list == []) {
                    $(".content_list").css('display', 'none');
                    $(".content_none").css('display', 'block');
                } else {
                    $(".content_list").css('display', 'block');
                    $(".content_none").css('display', 'none');

                    $.each(data.result.list, function (i, item) {
                        console.log(item.id);
                        if (item.status == 0) {
                            str1 += '<li class="list-item">' +
                                '<div class="item-top">' +
                                '<div class="pro-information">' +
                                '<div class="pro-img">' +
                                '<img src="http://paokucoin.com/img/backgroup/morenpic@2x.png" alt="">' +
                                '<span class="state-storage">' + '仓库中' + '</span>' +
                                '</div>' +
                                '<div class="pro-info">' +
                                '<div class="pro-name">' + item.title + '</div>' +
                                '<div class="pro-price">' + '<span class="price-calorie">' +
                                item.deduct + '卡</span>' + '<span class="ex-calorie">' + '卡路里兑换' +
                                '</span>' + '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="pro-visit-box">' +
                                '<div class="pro-visit-left">' +
                                '<span class="visit_text">' + '访问' +
                                '<i class="visit_num">' + item.viewcount + '</i>' +
                                '</span>' +
                                '<span class="visit_text">' + '已兑' +
                                '<i class="visit_num">' + item.salesreal + '</i>' +
                                '</span>' +
                                '</div>' +
                                '<div class="pro-visit-right">' + '库存' + item.total + '件' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="item-bottom">' +
                                '<div class="pro_handle">' +
                                '<div class="putaway_box">' +
                                '<img class="putawayImg" src="http://paokucoin.com/img/backgroup/shangjia@2x.png" alt="">' +
                                '<span class="putawayText upStatus"  data-id='+item.id+' data-status="1">' + '上架' + '</span>' +
                                '</div>' +
                                '<div class="redact_box" data-id='+item.id+'>' +
                                '<img class="redactImg" src="http://paokucoin.com/img/backgroup/bianji@2x.png" alt="">' +
                                '<span class="redactText">' + '编辑'+'</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</li>'
                                $(".listBox"). append(str1)
                        }
                        if(item.status==1){
                            // 出售中
                            str2 += '<li class="list-item">' +
                                '<div class="item-top">' +
                                '<div class="pro-information">' +
                                '<div class="pro-img">' +
                                '<img src="http://paokucoin.com/img/backgroup/morenpic@2x.png" alt="">' +
                                '<span class="state-sell">' + '出售中' + '</span>' +
                                '</div>' +
                                '<div class="pro-info">' +
                                '<div class="pro-name">' + item.title + '</div>' +
                                '<div class="pro-price">' + '<span class="price-calorie">' +
                                item.deduct + '卡</span>' + '<span class="ex-calorie">' + '卡路里兑换' +
                                '</span>' + '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="pro-visit-box">' +
                                '<div class="pro-visit-left">' +
                                '<span class="visit_text">' + '访问' +
                                '<i class="visit_num">' + item.viewcount + '</i>' +
                                '</span>' +
                                '<span class="visit_text">' + '已兑' +
                                '<i class="visit_num">' + item.salesreal + '</i>' +
                                '</span>' +
                                '</div>' +
                                '<div class="pro-visit-right">' + '库存' + item.total + '件' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '<div class="item-bottom">' +
                                '<div class="pro_handle">' +
                                '<div class="soldout_box">' +
                                '<img class="soldoutImg" src="http://paokucoin.com/img/backgroup/xiajia@2x.png" alt="">' +
                                '<span class="soldoutText upStatus" data-id='+item.id+' data-status="0">' + '下架' + '</span>' +
                                '</div>' +
                                '<div class="redact_box" data-id='+item.id+'>' +
                                '<img class="redactImg" src="http://paokucoin.com/img/backgroup/bianji@2x.png" alt="">' +
                                '<span class="redactText">' + '编辑' + '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</li>'
                                $(".listBox").append(str2)
                        }
                        

                    });
                }

            }
        })
    })

     $(".listBox").on("click",".upStatus",function(){
         var id = $(this).data('id');
         var status = $(this).data('status');
         $.ajax({
             type: 'POST',
             url:'<?php  echo mobileUrl('merchmanage/goods/status')?>' ,
             data: {
                 "id": id,
                 "state":status
             },
             dataType: "json",
             success: function (data) {
                 alert('操作成功');
                 location.reload()
             }
         })
     })

    $(".listBox").on("click",".redact_box",function () {
        var id = $(this).data('id');
        window.location.href = './index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.edit&id='+id;
    })



</script>