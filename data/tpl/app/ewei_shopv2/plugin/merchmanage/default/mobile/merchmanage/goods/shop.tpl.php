<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport"
        content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>橱窗设置</title>
    <link rel="stylesheet" href="../addons/ewei_shopv2/plugin/merchmanage/static/css/shopwindow.css">
</head>

<body>
    <div class="shopwindow_body">
        <!-- header -->
        <!-- <div class="shopwindow_header">橱窗设置</div> -->
        <!-- header -->
        <!-- content -->
        <div class="shopwindow_content">
            <!-- 店铺橱窗广告预览 -->
            <div class="sc_preview">
                <div class="pre-title">店铺橱窗广告预览</div>
                <div class="store_box">
                    <!-- 店铺 -->
                    <div class="store-detailbox"> </div>
                    <!-- 店铺 -->
                    <!-- 商品 -->
                    <div class="store-productbox">
                        <ul class="product-list"></ul>
                    </div>
                    <!-- 商品 -->
                </div>
            </div>
            <!-- 店铺橱窗广告预览 -->
            <!-- 商品列表 -->
            <div class="good-list-box">
                <ul class="good-list"></ul>
            </div>
            <!-- 商品列表 -->
        </div>
        <!-- content -->
        <!-- footer -->
        <!-- <div class="shopwindow_footer"></div> -->
        <!-- footer -->
    </div>

</body>

</html>
<script src="../addons/ewei_shopv2/plugin/merchmanage/static/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    var str1 = ""
    var str2 = ""
    var str3 = ""
    var goodId = ""
    var storeId = sessionStorage.getItem("storeid")
    $(".showcasebtn").click(function () {
        $(this).addClass("active").siblings().removeClass("active");

    })
    $(window).load(function () {
        console.log(storeId)
        $.ajax({
            type: 'POST',
            url: 'http://192.168.0.140:8081/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.show.getlist',
            data: {
                // page:this.data.page
                "page": '1'
            },
            dataType: "json",
            success: function (data) {
                console.log(data)
                var storeid = data.result.store.id
                sessionStorage.setItem("storeid", storeid)
                // 店铺
                str1 += '<div class="store-img">' + '<img class="storeimg" src=' + data.result.store
                    .logo + ' alt="">' + '</div>' +
                    '<div class="store-info">' +
                    '<div class="store-name">' + data.result.store.merchname + '</div>' +
                    '<div class="store-business">' + data.result.store.salecate + '</div>' +
                    '<div class="store-place">' + data.result.store.address + '</div>' +
                    '</div>' +
                    '<div class="store-other">' +
                    '<div class="store-other-absolute">' +
                    '<div class="tuijianImg">' +
                    '<img src="http://paokucoin.com/img/backgroup/tuijian.png" alt="">' + '</div>' +
                    '<div class="into-shop-box">' + '<span class="into-shop-text">' + '进店有礼' +
                    '</span>' + '<span class="intoImg">' +
                    '<img src="http://paokucoin.com/img/backgroup/open.png" alt="">' + '</span>' +
                    '</div>' +
                    '</div>'
                '</div>'
                $(".store-detailbox").html(str1)
                // 店铺商品
                var show = data.result.list.slice(0, 3)
                // console.log(show)
                $.each(show, function (i, item) {
                    // console.log(item.id);
                    // 显示免费兑
                    if (item.deduct - item.marketprice > 0) {
                        str2 += '<li class="pro-item" data-id=' + item.id + '>' +
                            '<div class="proImg-box">' + '<img class="proImg" src=' + item
                            .thumb + ' alt="">' + '</div>' +
                            '<div class="proname">' + item.title + '</div>' +
                            '<div class="good-price-box">' + '免费兑' + '</div>' +
                            '</li>'
                        $(".product-list").html(str2)
                    } else if (item.deduct - item.marketprice < 0) {
                        // 显示卡路里加金额
                        str2 += '<li class="pro-item" data-id=' + item.id + '>' +
                            '<div class="proImg-box">' + '<img class="proImg" src=' + item
                            .thumb + ' alt="">' + '</div>' +
                            '<div class="proname">' + item.title + '</div>' +
                            '<div class="good-price-box">' +
                            '<img class="calorie-img" src="http://paokucoin.com/img/backgroup/kaluli02.png" alt="">' +
                            '<span class="calorie-num">' + item.deduct + '</span>' + '+' +
                            '<span class="price-num">' + '￥' + item.marketprice +
                            '</span>' +
                            '</div>' +

                            '</li>'
                        $(".product-list").html(str2)
                    } else if (item.deduct == 0) {
                        // 显示金额
                        str2 += '<li class="pro-item" data-id=' + item.id + '>' +
                            '<div class="proImg-box">' + '<img class="proImg" src=' + item
                            .thumb + ' alt="">' + '</div>' +
                            '<div class="proname">' + item.title + '</div>' +
                            ' <div class="good-price-box">' + '￥' + item.marketprice +
                            '</div>' +
                            '</li>'
                        $(".product-list").html(str2)
                    }
                });

                // 橱窗商品点击跳转编辑事件
                $(".product-list").on("click", ".pro-item", function () {
                    var proId = ($(this).attr("data-id"))
                    console.log(proId)
                    // location.href='http://paokucoin.com/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.edit?id='+proId
                    location.href='http://paokucoin.com/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.edit&id='+proId
                })


                // 列表商品开始
                $.each(data.result.list, function (i, item) {
                    // 显示免费兑
                    if (item.deduct - item.marketprice > 0) {
                        str3 += '<li class="good-item">' +
                            '<div class="handlebtn good-img" data-id=' + item.id + '>' + '<img src=' + item.thumb +
                            ' alt="">' +
                            '</div>' +
                            '<div class="good-info">' +
                            '<div class="handlebtn good-name" data-id=' + item.id + '>' + item.title + '</div>' +
                            '<div class="good-price-box">' + '免费兑' + '</div>' +
                            '<div class="good-count">' +
                            '<span class="good-storage">' + '库存' + '：' + item.total +
                            '</span>' +
                            '<span class="good-sale">' + '销量' + '：' + item.salesreal +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="good-other">' +
                            '<span class="leftbtn" data-id=' + item.id + '>' + '左' +
                            '</span>' +
                            '<span class=" centerbtn " data-id=' + item.id + '>' + '中' +
                            '</span>' +
                            '<span class=" rightbtn" data-id=' + item.id + '>' + '右' +
                            '</span>' +
                            '</div>' +
                            '</li>'
                        $(".good-list").html(str3)
                    } else if (item.deduct - item.marketprice < 0) {
                        // 显示卡路里加金额
                        str3 += '<li class="good-item">' +
                            '<div class="handlebtn good-img" data-id=' + item.id + '>' + '<img src=' + item.thumb +
                            ' alt="">' +
                            '</div>' +
                            '<div class="good-info">' +
                            '<div class="handlebtn good-name" data-id=' + item.id + '>' + item.title + '</div>' +
                            '<div class="good-price-box">' +
                            '<img class="calorie-img" src="http://paokucoin.com/img/backgroup/kaluli02.png" alt="">' +
                            '<span class="calorie-num">100+</span>' +
                            '<span class="price-num">' + '￥' + item.marketprice +
                            '</span>' +
                            '</div>' +
                            '<div class="good-count">' +
                            '<span class="good-storage">' + '库存' + '：' + item.total +
                            '</span>' +
                            '<span class="good-sale">' + '销量' + '：' + item.salesreal +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="good-other">' +
                            '<span class="leftbtn" data-id=' + item.id + '>' + '左' +
                            '</span>' +
                            '<span class="centerbtn " data-id=' + item.id + '>' + '中' +
                            '</span>' +
                            '<span class="rightbtn" data-id=' + item.id + '>' + '右' +
                            '</span>' +
                            '</div>' +
                            '</li>'
                        $(".good-list").html(str3)
                    } else if (item.deduct == 0) {
                        // 显示金额
                        str3 += '<li class="good-item">' +
                            '<div class="handlebtn good-img" data-id=' + item.id + '>' + '<img src=' + item.thumb +
                            ' alt="">' +
                            '</div>' +
                            '<div class="good-info">' +
                            '<div class="handlebtn good-name" data-id=' + item.id + '>' + item.title + '</div>' +
                            ' <div class="good-price-box">' + '￥' + item.marketprice +
                            '</div>' +
                            '<div class="good-count">' +
                            '<span class="good-storage">' + '库存' + '：' + item.total +
                            '</span>' +
                            '<span class="good-sale">' + '销量' + '：' + item.salesreal +
                            '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="good-other">' +
                            '<span class="leftbtn" data-id=' + item.id + '>' + '左' +
                            '</span>' +
                            '<span class="centerbtn " data-id=' + item.id + '>' + '中' +
                            '</span>' +
                            '<span class="rightbtn" data-id=' + item.id + '>' + '右' +
                            '</span>' +
                            '</div>' +
                            '</li>'
                        $(".good-list").html(str3)
                    }
                });
                // 列表商品点击跳转编辑事件
                   $(".good-list").on("click", ".handlebtn", function () {
                    var goodId = ($(this).attr("data-id"))
                    console.log(goodId)
                    // location.href='http://paokucoin.com/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.edit?id='+goodId
                    location.href='http://paokucoin.com/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.edit&id='+goodId
                })

                // 列表商品结束
                // 左移
                $(".good-list").on("click", ".leftbtn", function () {
                    $(this).toggleClass('selected').siblings().removeClass(
                        'selected');
                    var goodid = ($(this).attr("data-id"))
                    console.log(goodid)
                    $.ajax({
                        type: 'POST',
                        url: 'http://192.168.0.140:8081/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.show.changesort&ids=1:2,2:3,3:1',
                        data: {
                            "ids": 'goodid:3'
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data)
                        }
                    })
                    $(this).parent().parent().toggleClass('on').siblings().removeClass(
                        'on');
                    var txtimg = $($(".good-list .on").children('.good-img')).html()
                    var txt = $($(".good-list .on").children(".good-info").children(
                        '.good-name')).html()
                    var pricetxt = $($(".good-list .on").children(".good-info").children(
                        '.good-price-box')).html()
                    $($(".product-list").children("li").get(0)).children('.proImg-box')
                        .html(txtimg)
                    $($(".product-list").children("li").get(0)).children('.proname').html(
                        txt)
                    $($(".product-list").children("li").get(0)).children('.good-price-box')
                        .html(pricetxt)
                })
                // 中移
                $(".good-list").on("click", ".centerbtn", function () {
                    $(this).toggleClass('selected').siblings().removeClass(
                        'selected');
                    var goodid = ($(this).attr("data-id"))
                    console.log(goodid)
                    $.ajax({
                        type: 'POST',
                        url: 'http://192.168.0.140:8081/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.show.changesort&ids=1:2,2:3,3:1',
                        data: {
                            "ids": 'goodid:2'
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data)
                        }
                    })
                    $(this).parent().parent().toggleClass('on').siblings().removeClass(
                        'on');
                    var txtimg = $($(".good-list .on").children('.good-img')).html()
                    var txt = $($(".good-list .on").children(".good-info").children(
                        '.good-name')).html()
                    var pricetxt = $($(".good-list .on").children(".good-info").children(
                        '.good-price-box')).html()
                    $($(".product-list").children("li").get(1)).children('.proImg-box')
                        .html(txtimg)
                    $($(".product-list").children("li").get(1)).children('.proname').html(
                        txt)
                    $($(".product-list").children("li").get(1)).children('.good-price-box')
                        .html(pricetxt)
                })
                // 右移
                $(".good-list").on("click", ".rightbtn", function () {
                    $(this).toggleClass('selected').siblings().removeClass(
                        'selected');
                    var goodid = ($(this).attr("data-id"))
                    console.log(goodid)
                    $.ajax({
                        type: 'POST',
                        url: 'http://192.168.0.140:8081/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.goods.show.changesort&ids=1:2,2:3,3:1',
                        data: {
                            "ids": 'goodid:2'
                        },
                        dataType: "json",
                        success: function (data) {
                            console.log(data)
                        }
                    })
                    $(this).parent().parent().toggleClass('on').siblings().removeClass(
                        'on');
                    var txtimg = $($(".good-list .on").children('.good-img')).html()
                    var txt = $($(".good-list .on").children(".good-info").children(
                        '.good-name')).html()
                    var pricetxt = $($(".good-list .on").children(".good-info").children(
                        '.good-price-box')).html()
                    $($(".product-list").children("li").get(2)).children('.proImg-box')
                        .html(txtimg)
                    $($(".product-list").children("li").get(2)).children('.proname').html(
                        txt)
                    $($(".product-list").children("li").get(2)).children('.good-price-box')
                        .html(pricetxt)
                })


            }

        })
    })
    // 店铺跳转
    $(".store-detailbox").click(function () {
       location.href='http://paokucoin.com/app/index.php?i=1&c=entry&m=ewei_shopv2&do=mobile&r=merchmanage.shop&id='+storeId
    })
</script>