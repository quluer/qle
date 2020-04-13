<?php defined('IN_IA') or exit('Access Denied');?><script type="text/html" id="tpl_show_notice">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-notice" style="background: <%style.background%>">

            <div class="image"><img src="<%imgsrc params.iconurl%>" onerror="this.src='../addons/ewei_shopv2/static/images/hotdot.jpg'"></div>

            <div class="icon"><i class="icon icon-notification1" style="font-size: 0.7rem; color: <%style.iconcolor%>;"></i></div>

            <div class="text" style="color: <%style.color%>;">

                <%if params.noticedata=='0'%>这里将读取商城的公告进行滚动显示<%/if%>

                <%if params.noticedata=='1'%>

                <ul>

                    <%each data as item%>

                    <li><%item.title%></li>

                    <%/each%>

                </ul>

                <%/if%>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_title">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-title" style="background: <%style.background||''%>; color: <%style.color||''%>; font-size: <%style.fontsize||'12'%>px; text-align: <%style.textalign||''%>; padding: <%style.paddingtop||'0'%>px <%style.paddingleft||'5'%>px;">

            <%if params.icon%>

            <i class="icox <%params.icon%>"></i>

            <%/if%>

            <%if params.link%>

            <a href="<%params.link%>" style="color: <%style.color||''%>"><%params.title||'请输入标题内容'%></a>

            <%else%>

            <%params.title||'请输入标题内容'%>

            <%/if%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_search">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-search <%style.searchstyle%>" style="background: <%style.background%>; padding: <%style.paddingtop||'10'%>px <%style.paddingleft||'10'%>px;">

            <div class="inner left" style="background: <%style.inputbackground||'#fff'%>;">

                <div class="search-icon" style="color: <%style.iconcolor%>;"><i class="icon icon-search"></i></div>

                <div class="search-input" style="text-align: <%style.textalign%>; color: <%style.color%>;">

                    <span><%params.placeholder%></span>

                </div>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_fixedsearch">

    <div class="drag fixed" data-itemid="<%itemid%>">

        <div class="diy-fixedsearch">

            <div class="background" style="background: <%style.background%>; opacity: <%style.opacity%>;"></div>

            <div class="inner">

                <%if (params.leftnav==1&&params.leftnavicon!='') || (params.leftnav==2&&params.leftnavimg!='')%>

                <div class="leftnav">

                    <%if params.leftnav==1&&params.leftnavicon!=''%>

                    <i class="icox <%params.leftnavicon%>" style="color: <%style.leftnavcolor%>;"></i>

                    <%/if%>

                    <%if params.leftnav==2&&params.leftnavimg&&params.leftnavimg!=''%>

                    <img src="<%imgsrc params.leftnavimg%>" style=""/>

                    <%/if%>

                </div>

                <%/if%>

                <div class="center <%params.searchstyle%>">

                    <input value="<%params.placeholder%>" style="opacity: <%style.opacity%>; background: <%style.searchbackground%>; color: <%style.searchtextcolor%>;"/>

                </div>

                <%if (params.rightnav==1&&params.rightnavicon!='') || (params.rightnav==2&&params.rightnavimg!='')%>

                <div class="rightnav">

                    <%if params.rightnav==1&&params.rightnavicon!=''%>

                    <i class="icox <%params.rightnavicon%>" style="color: <%style.rightnavcolor%>;"></i>

                    <%/if%>

                    <%if params.rightnav==2&&params.rightnavimg&&params.rightnavimg!=''%>

                    <img src="<%imgsrc params.rightnavimg%>"/>

                    <%/if%>

                </div>

                <%/if%>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_line">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-line-diy" style="background: <%style.background%>; padding: <%style.padding%>px 0;">

            <div class="line" style="border-top: <%style.height||'2'%>px <%style.linestyle||'solid'%> <%style.bordercolor||'#000000'%>"></div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_blank">

    <div class="drag" data-itemid="<%itemid%>" style="height: <%style.height%>px; background: <%style.background%>"></div>

</script>



<script type="text/html" id="tpl_show_menu">

    <div class="drag" data-itemid="<%itemid%>">

        <%if data==''%>

        <div class="nochild">您还没有添加图标</div>

        <%else%>

        <div class="fui-icon-group noborder col-<%style.rownum%> <%style.navstyle%> <%if style.showtype>0%>pb10<%/if%>" style="background: <%style.background||'#ffffff'%>">

            <%each data as item%>

            <div class="fui-icon-col">

                <div class="icon"><img src="<%imgsrc item.imgurl%>"></div>

                <div class="text" style="color: <%item.color%>"><%item.text%></div>

            </div>

            <%/each%>

            <%if style.showdot>0&&style.showtype==1%>

            <div class="fui-icon-group-pagination">

                <a class="active"></a>

                <a></a>

            </div>

            <%/if%>

        </div>

        <%/if%>

    </div>

</script>



<script type="text/html" id="tpl_show_menu2">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-menu-group" style="margin-top: <%style.margintop%>px; background: <%style.background%>;">

            <%each data as item%>

            <%if item.text%>

            <a class="fui-menu-item" style="color: <%item.textcolor%>;"><%if item.iconclass%><i class="icox <%item.iconclass%>" style="color: <%item.iconcolor%>;"></i><%/if%> <%item.text%></a>

            <%/if%>

            <%/each%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_listmenu">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-cell-click" style="margin-top: <%style.margintop%>px; background-color: <%style.background%>;">

            <%each data as item%>

            <div class="fui-cell">

                <%if item.iconclass%>

                <div class="fui-cell-icon" style="color: <%style.iconcolor%>;"><i class="icox <%item.iconclass%>"></i></div>

                <%/if%>

                <div class="fui-cell-text" style="color: <%style.textcolor%>;"><%item.text%></div>

                <div class="fui-cell-remark" style="color: <%style.remarkcolor%>;"><%item.remark%></div>

            </div>

            <%/each%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_picture">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-picture" style="padding-bottom: <%style.paddingtop%>px; background: <%style.background%>;">

            <%each data as item%>

            <div style="display: block; padding: <%style.paddingtop%>px <%style.paddingleft%>px 0;">

                <img src="<%imgsrc item.imgurl%>"/>

            </div>

            <%/each%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_picturew">

    <div class="drag" data-itemid="<%itemid%>">

        <%if params.row=='1'%>

        <div class="fui-cube" style="background: <%style.background%>; <%if count(data)==1%>padding: <%style.paddingtop%>px <%style.paddingleft%>px;<%/if%>">

            <%if count(data)==1%>

            <img src="<%imgsrc(toArray(data)[0].imgurl)%>"/>

            <%/if%>

            <%if count(data)>1%>

            <div class="fui-cube-left" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px; padding-right: 0;">

                <img src="<%imgsrc(toArray(data)[0].imgurl)%>"/>

            </div>

            <div class="fui-cube-right" <%if count(data)==2%> style="padding: <%style.paddingtop%>px <%style.paddingleft%>px;"<%/if%>>

            <%if count(data)==2%>

            <img src="<%imgsrc(toArray(data)[1].imgurl)%>"/>

            <%/if%>

            <%if count(data)>2%>

            <div class="fui-cube-right1" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px; padding-bottom: 0;">

                <img src="<%imgsrc(toArray(data)[1].imgurl)%>"/>

            </div>

            <div class="fui-cube-right2" <%if count(data)==3%> style="padding: <%style.paddingtop%>px <%style.paddingleft%>px;"<%/if%>>

            <%if count(data)==3%>

            <img src="<%imgsrc(toArray(data)[2].imgurl)%>"/>

            <%/if%>

            <%if count(data)>3%>

            <div class="left" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px; padding-right: 0;">

                <img src="<%imgsrc(toArray(data)[2].imgurl)%>"/>

            </div>

            <%/if%>

            <%if count(data)>=4%>

            <div class="right" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px;">

                <img src="<%imgsrc(toArray(data)[3].imgurl)%>"/>

            </div>

            <%/if%>

        </div>

        <%/if%>

    </div>

    <%/if%>

    </div>

    <%/if%>

    <%if params.row>1%>

    <div class="fui-picturew row-<%params.row%>" style="padding: <%style.paddingtop%>px; <%style.paddingleft%>px; background: <%style.background%>;">

        <%each data as item%>

        <div class="item" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px;">

            <img src="<%imgsrc item.imgurl%>">

        </div>

        <%/each%>

        <%if style.showdot>0&&params.showtype==1%>

        <div class="fui-picturew-pagination">

            <a class="active"></a>

            <a></a>

        </div>

        <%/if%>

    </div>

    <%/if%>

    </div>

</script>



<script type="text/html" id="tpl_show_banner">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-banner">

            <%each data as item%>

            <img src="<%imgsrc item.imgurl%>"/>

            <%/each%>

            <div class="dots <%style.dotalign||'left'%> <%style.dotstyle||'rectangle'%>" style="padding: 0 <%style.leftright||'10'%>px; bottom: <%style.bottom||'10'%>px; opacity: <%style.opacity||'0.8'%>;">

                <%each data as item%>

                <span style="background: <%style.background||'#000000'%>;"></span>

                <%/each%>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_pictures">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-picturew row-<%params.rownum%>" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px; background: <%style.background%>;">

            <%each data as item%>

            <div class="item" style="padding: <%style.paddingtop%>px <%style.paddingleft%>px;">

                <div class="image">

                    <img src="<%imgsrc item.imgurl%>">

                    <%if item.title!=''%>

                    <div class="title" style="color: <%style.titlecolor%>; text-align: <%style.titlealign%>;"><%item.title%></div>

                    <%/if%>

                </div>

                <div class="text" style="color: <%style.textcolor%>; text-align: <%style.textalign%>;""><%item.text%></div>

        </div>

        <%/each%>

        <%if style.showdot>0&&params.showtype==1%>

        <div class="fui-picturew-pagination">

            <a class="active"></a>

            <a></a>

        </div>

        <%/if%>

    </div>

    </div>

</script>







<!--小程序会员中心-->

<!--会员信息-->

<script type="text/html" id="tpl_show_member">

    <div class="drag" data-itemid="<%itemid%>">

        <%if params.style=='default1'%>

        <div style="overflow: hidden;height: 9rem;position: relative;background: #fff">

            <div class="headinfo" style="z-index:1001;border: none;">

                <div class="setbtn" style="color: <%style.textcolor%>;"><i class="icon <%params.seticon%>"></i></div>

                <div class="child">

                    <div class="title" style="color: <%style.textcolor%>;">余额</div>

                    <div class="num" style="color: <%style.textlight%>;">123.50</div>

                    <%if params.leftnav%>

                    <div class="btn" style="color: <%style.textcolor%>; border-color: <%style.textcolor%>;"><%params.leftnav%></div>

                    <%/if%>

                </div>

                <div class="child userinfo" style="color: <%style.textcolor%>;">

                    <div class="face <%style.headstyle%>"><img src="../addons/ewei_shopv2/static/images/nopic100.jpg"></div>

                    <div class="name">用户昵称</div>

                    <div class="level">[用户等级] <i class="icon icon-question1" style="font-size: 12px;"></i></div>

                </div>

                <div class="child">

                    <div class="title" style="color: <%style.textcolor%>;">卡路里</div>

                    <div class="num" style="color: <%style.textlight%>;">54321</div>

                    <%if params.rightnav%>

                    <div class="btn" style="color: <%style.textcolor%>; border-color: <%style.textcolor%>;"><%params.rightnav%></div>

                    <%/if%>

                </div>

            </div>

            <div class="member_header" style="background: <%style.background%>;border-color: <%style.background%>;"></div>

            <div class='member_card'>

                <img class='icon' src='../addons/ewei_shopv2/static/images/icon-huangguan.png' />

                <div class='title'>我的会员卡</div>

                <div class='card-num'>已拥有X张</div>

            </div>

            <image class='cover-img' src='../addons/ewei_shopv2/static/images/cover.png' />

        </div>

        <%/if%>

        <%if params.style=='default2'%>

        <div class="headinfo style-2" style="background: <%style.background%>; <%if style.background%>border: none;<%/if%>">

            <img class="icon-style2" src="../addons/ewei_shopv2/static/images/header-style2.png" />

            <div class="setbtn" style="color: <%style.textcolor%>;"><i class="icon <%params.seticon%>"></i></div>

            <div class="face <%style.headstyle%>"><img src="../addons/ewei_shopv2/static/images/nopic100.jpg"></div>

            <div class="inner" style="color: <%style.textcolor%>;">

                <div class="name">Change.</div>

                <div class="level">[用户等级] <i class="icon icon-question1" style="font-size: 12px;"></i></div>

                <div class="credit">余额: <span style="color: <%style.textlight%>;">123.50</span></div>

                <div class="credit">卡路里: <span style="color: <%style.textlight%>;">54321</span></div>

            </div>

        </div>

        <%/if%>

    </div>

</script>

<!--绑定手机-->

<script type="text/html" id="tpl_show_bindmobile">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-cell-click" style="margin-top: <%style.margintop%>px; background-color: <%style.background%>;">

            <div class="fui-cell">

                <div class="fui-cell-text" style="color: <%style.titlecolor%>;">

                    <%if params.iconclass%>

                    <i class="icon <%params.iconclass%>" style="color: <%style.iconcolor%>;"></i>

                    <%/if%>

                    <%params.title%>

                    <%if params.text%>

                    <div class="" style="color: <%style.textcolor%>;font-size: 12px;"><%params.text%></div>

                    <%/if%>

                </div>

                <div class="fui-cell-remark"></div>

            </div>



        </div>

    </div>

</script>

<!--待使用商品-->

<script type="text/html" id="tpl_show_verify">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-cell-click" style="margin-top: 10px; background-color: <%style.titlebg%>;">

            <div class="fui-cell">

                <div class="fui-cell-icon" style="color: #999999;"><i class="icon <%params.iconclass%>"></i></div>

                <div class="fui-cell-text" style="color: <%style.titlecolor%>;"><% params.title%></div>

                <div class="fui-cell-remark" style="color: <%style.remarkcolor%>;"><% params.remark%></div>

            </div>

        </div>

        <div class="fui-icon-group selecter" style="overflow: scroll; background: <%style.background%>">

            <%if params.style!='style2'%>

            <ul class="banner-ul">

                <li class="">

                    <a>

                        <div></div><span>待使用</span>

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-1.jpg" alt=""><p>测试核销商品</p>

                    </a>

                </li>

                <li class="banner-li-blue">

                    <a>

                        <div></div><span>待使用</span>

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-2.jpg" alt=""><p>测试核销商品</p>

                    </a>

                </li>

            </ul>

            <%else%>

            <ul class="banner-ul style2">

                <li class="">

                    <a>

                        <div></div><span>待使用</span>

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-1.jpg" alt=""><p>测试核销商品</p>

                    </a>

                </li>

                <li class="banner-li-blue">

                    <a>

                        <div></div><span>待使用</span>

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-2.jpg" alt=""><p>测试核销商品</p>

                    </a>

                </li>

            </ul>

            <%/if%>

        </div>

    </div>

</script>





<style>

    .fui-audio.style1 {

        height: 43px;

        line-height: 41px;

    }

    .fui-audio {

        width: 100%;

        border: 1px solid #eeeeee;

        padding: 0 15px 0 10px;

        box-sizing: border-box;

        position: relative;

        overflow: hidden;

        background: #fff;

    }

    .fui-audio.style1 .progress {

        position: absolute;

        bottom: 0;

        left: 0;

        right: 0;

    }

    .fui-audio.style1 .name {

        float: left;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        max-width: 150px;

    }

    .fui-audio.style1 .author {

        float: left;

        margin-left: 6px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

        max-width: 100px;

    }

    .fui-audio.style1 .start {

        position: absolute;

        top: 0px;

        right: 20px;

        width: 20px;

        height: 20px;

        color: #000;

    }



</style>

<script type="text/html" id="tpl_show_audio">

    <div class="drag" data-itemid="<%itemid%>">

        <%if params.playerstyle=='0'||!params.playerstyle%>

        <div class="fui-audio" style="background: <%style.background%>; border-color: <%style.bordercolor%>; margin: <%style.paddingtop%>px <%style.paddingleft%>px;">

            <div class="horn" style="width:2rem;height:2rem;margin-right:5px;">

                <img src="<%params.headurl&&params.headtype!=1 ? imgsrc(params.headurl) : imgsrc(params.audiodefaultimg)%>" width="100%" height="100%"/>

            </div>

            <div class="center">

                <p style="color: <%style.textcolor%>;"><%params.title||'未定义音频信息'%></p>

                <%if params.subtitle!=''%>

                <p style="color: <%style.subtitlecolor%>;"><%params.subtitle%></p>

                <%/if%>

            </div>

            <div class="time" style="color: <%style.timecolor%>;">11'26''</div>

            <div class="speed"></div>

        </div>

        <%/if%>

        <%if params.playerstyle=='1'%>

        <div class="fui-audio style1 <%params.headalign%>" style="background: <%style.background%>;margin: <%style.paddingtop%>px <%style.paddingleft%>px;">

            <div class="content">

                <p class="name" style="color: <%style.textcolor%>;"><%params.title||'未定义音频信息'%></p>

                <p class="author" style="color: <%style.subtitlecolor%>;"><%params.subtitle%></p>

            </div>

            <div class="start icon"><i class="icon icon-playfill"></i></div>

        </div>

        <%/if%>

    </div>

</script>



<script type="text/html" id="tpl_show_icongroup">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-icon-group noborder col-<%params.rownum%> selecter" style="background-color: <%style.background%>; <%if params.bordertop=='1'%>border-top: 1px solid <%style.bordercolor%>;<%else%>border-top: none;<%/if%> <%if params.borderbottom=='1'%>border-bottom: 1px solid <%style.bordercolor%>;<%else%>border-bottom: none;<%/if%>">

            <%define index=0%>

            <%each data as child itemid %>

            <div class="fui-icon-col external" style="<%if params.border=='1'&&index>0%>border-left: 1px solid <%style.bordercolor%>;<%else%>border-left: none;<%/if%>">

                <div class="badge" style="background-color: <%style.dotcolor%>;">9</div>

                <div class="icon icon-green radius"><i class="icox <%child.iconclass%>" style="color: <%style.iconcolor%>;"></i></div>

                <div class="text" style="color: <%style.textcolor%>;"><%child.text%></div>

            </div>

            <%define index++%>

            <%/each%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_coupon">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-coupon col-<%params.couponstyle%>" style="margin: <%style.margintop%>px 0; background: <%style.background%>;">

            <%each data as item%>

            <div class="diy-coupon-item">

                <div class="inner" style="border: 0; background: <%item.couponcolor%>; color: #ffffff; margin: <%style.margintop%>px <%style.marginleft%>px;">

                    <div class="name"><%item.price%></div>

                    <div class="receive" style="border: 1px solid <%item.textcolor%>;">立即领取</div>

                    <i style="left: -0.35rem;background:<%style.background%>;"></i>

                    <i style="right: -0.35rem;background: <%style.background%>;"></i>

                </div>

            </div>

            <%/each%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_richtext">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-richtext" style="background: <%style.background%>; padding: <%style.padding%>px;">

            <%if params.content%>

            <%=decode(params.content)%>

            <%else%>

            <p><span style="font-size: 20px;">哈喽大家好！这里是『富文本』区域</span></p>

            <p>你可以对文字进行<strong>加粗</strong>、<em>斜体</em>、<span style="text-decoration: underline;">下划线</span>、<span style="text-decoration: line-through;">删除线</span>、文字<span style="color: rgb(0, 176, 240);">颜色</span>、<span style="background-color: rgb(255, 192, 0); color: rgb(255, 255, 255);">背景色</span>、以及字号<span style="font-size: 20px;">大</span><span style="font-size: 14px;">小</span>等简单排版操作。

            </p>

            <p>也可在这里插入图片</p>

            <p><img src="../addons/ewei_shopv2/plugin/app/static/images/default/sale-by.png"></p>

                <p style="text-align: left;"><span style="text-align: left;">还可给文字加上<a href="http://www.baidu.com">超级链接</a>，方便用户点击。</span></p>

            <%/if%>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_goods">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-goods-group <%style.liststyle%>" style="background: <%style.background%>;">

            <%if params.goodsdata=='0'%>

            <%each data as item%>

            <div class="fui-goods-item" data-goodsid="<%item.gid%>">

                <div class="image <%if params.showicon=='1'%><%style.iconstyle%><%/if%>" style="background-image: url('<%imgsrc item.thumb%>');"

                     data-text="

                                <%if params.showicon=='1'%>

                                        <%if style.goodsicon=='recommand'%>推荐<%/if%>

                                        <%if style.goodsicon=='hotsale'%>热销<%/if%>

                                        <%if style.goodsicon=='isnew'%>新上<%/if%>

                                        <%if style.goodsicon=='sendfree'%>包邮<%/if%>

                                        <%if style.goodsicon=='istime'%>限时卖<%/if%>

                                        <%if style.goodsicon=='bigsale'%>促销<%/if%>

                                <%/if%>

                        ">

                    <%if params.showicon=='2'%>

                    <div class="goodsicon <%params.iconposition%>"

                    <%if params.iconposition=='left top'%> style="top: <%style.iconpaddingtop%>px; left: <%style.iconpaddingleft%>px; text-align: left;"<%/if%>

                    <%if params.iconposition=='right top'%> style="top: <%style.iconpaddingtop%>px; right: <%style.iconpaddingleft%>px; text-align: right;"<%/if%>

                    <%if params.iconposition=='left bottom'%> style="bottom: <%style.iconpaddingtop%>px; left: <%style.iconpaddingleft%>px; text-align: left;"<%/if%>

                    <%if params.iconposition=='right bottom'%> style="bottom: <%style.iconpaddingtop%>px; right: <%style.iconpaddingleft%>px; text-align: right;"<%/if%>

                    >

                    <%if params.showicon=='1'%>

                    <img src="../addons/ewei_shopv2/plugin/app/static/images/default/goodsicon-<%style.goodsicon%>.png" style="width: <%style.iconzoom||'100'%>%;"/>

                    <%/if%>

                    <%if params.showicon=='2' && params.goodsiconsrc%>

                    <img src="<%imgsrc params.goodsiconsrc%>" onerror="this.src=''" style="width: <%style.iconzoom||'100'%>%;"/>

                    <%/if%>

                </div>

                <%/if%>

            </div>



            <div class="detail">

                <%if params.showtitle=='1'%>

                <div class="name" style="color: <%style.titlecolor%>;"><%item.title%></div>

                <%/if%>

                <%if params.showprice=='1'%>

                <p class="productprice  <%if (params.showproductprice !=1) && (params.showsales !=1)%>noheight<%/if%>">

                    <%if params.showproductprice==1 && item.productprice>0%>

                    <span style="color: <%style.productpricecolor%>;"><%params.productpricetext||'原价'%>:<span <%if params.productpriceline==1%>style="text-decoration: line-through;"<%/if%>>￥<%item.productprice%></span></span>

                    <%/if%>

                    <%if params.showsales==1&&item.sales>=0%>

                    <span style="color: <%style.salescolor%>;"><%params.salestext||'销量'%>:<%item.sales%></span>

                    <%/if%>

                </p>

                <div class="price">

                                    <span class="text" style="color: <%style.pricecolor%>;">

                                        <p class="minprice">￥<%item.price%></p>

                                    </span>

                    <%if style.buystyle!=''%>

                    <%if style.buystyle=='buybtn-1'%>

                    <span class="buy" style="border-color: <%style.buybtncolor%>;color:<%style.buybtncolor%> ">购买</span>

                    <%/if%>

                    <%if style.buystyle=='buybtn-2'%>

                    <span class="buy buy buybtn-2" style="border-color: <%style.buybtncolor%>;background-color: <%style.buybtncolor%>;">购买</span>

                    <%/if%>

                    <%if style.buystyle=='buybtn-3'%>

                    <span class="buy buybtn-3" style="background-color: <%style.buybtncolor%>; border-color: <%style.buybtncolor%>;"><i class="icon icon-cartfill"></i></span>

                    <%/if%>

                    <%if style.buystyle=='buybtn-4'%>

                    <span class="buy buybtn-4" style="border-color: <%style.buybtncolor%>;"><i class="icon icon-cart" style="color: <%style.buybtncolor%>;"></i></span>

                    <%/if%>

                    <%if style.buystyle=='buybtn-5'%>

                    <span class="buy buybtn-5" style="border-color: <%style.buybtncolor%>;"><i class="icon icon-add" style="color: <%style.buybtncolor%>;"></i></span>

                    <%/if%>

                    <%if style.buystyle=='buybtn-6'%>

                    <span class="buy buybtn-6" style="background-color: <%style.buybtncolor%>; border-color: <%style.buybtncolor%>;"><i class="icon icon-add"></i></span>

                    <%/if%>

                    <%/if%>

                </div>

                <%/if%>

            </div>



                        <%if params.saleout>-1%>

                        <%if params.saleout==0%><div class="salez" style="background-image: url('<?php  echo tomedia($_W['shopset']['shop']['saleout'])?>'); "></div><%/if%>

                        <%if params.saleout==1%><div class="salez diy" style="background-image: url('../addons/ewei_shopv2/plugin/app/static/images/default/saleout-<%style.saleoutstyle%>.png'); "></div><%/if%>

                        <%/if%>

        </div>

        <%/each%>

        <%/if%>



        <%if params.goodsdata>0%>

        <%each data=["c","d"] as item%>

        <div class="fui-goods-item" data-goodsid="<%item.gid%>">

            <div class="image <%if params.showicon=='1'%><%style.iconstyle%><%/if%>" style="background-image: url('../addons/ewei_shopv2/plugin/app/static/images/default/goods-1.jpg');"

                 data-text="

                                <%if params.showicon=='1'%>

                                        <%if style.goodsicon=='recommand'%>推荐<%/if%>

                                        <%if style.goodsicon=='hotsale'%>热销<%/if%>

                                        <%if style.goodsicon=='isnew'%>新上<%/if%>

                                        <%if style.goodsicon=='sendfree'%>包邮<%/if%>

                                        <%if style.goodsicon=='istime'%>限时卖<%/if%>

                                        <%if style.goodsicon=='bigsale'%>促销<%/if%>

                                <%/if%>

                        ">

                <%if params.showicon=='2'%>

                <div class="goodsicon <%params.iconposition%>"

                <%if params.iconposition=='left top'%> style="top: <%style.iconpaddingtop%>px; left: <%style.iconpaddingleft%>px; text-align: left;"<%/if%>

                <%if params.iconposition=='right top'%> style="top: <%style.iconpaddingtop%>px; right: <%style.iconpaddingleft%>px; text-align: right;"<%/if%>

                <%if params.iconposition=='left bottom'%> style="bottom: <%style.iconpaddingtop%>px; left: <%style.iconpaddingleft%>px; text-align: left;"<%/if%>

                <%if params.iconposition=='right bottom'%> style="bottom: <%style.iconpaddingtop%>px; right: <%style.iconpaddingleft%>px; text-align: right;"<%/if%>

                >

                <%if params.showicon=='1'%>

                <img src="../addons/ewei_shopv2/plugin/app/static/images/default/goodsicon-<%style.goodsicon%>.png" style="width: <%style.iconzoom||'100'%>%;"/>

                <%/if%>

                <%if params.showicon=='2' && params.goodsiconsrc%>

                <img src="<%imgsrc params.goodsiconsrc%>" onerror="this.src=''" style="width: <%style.iconzoom||'100'%>%;"/>

                <%/if%>

            </div>

            <%/if%>

        </div>



        <div class="detail">

            <%if params.showtitle=='1'%>

            <div class="name" style="color: <%style.titlecolor%>;">

                这里是商品标题(商品从设定<%if params.goodsdata=='1'%>分类<%/if%><%if params.goodsdata=='2'%>分组<%/if%><%if params.goodsdata=='3'%>新品商品<%/if%><%if params.goodsdata=='4'%>热卖商品<%/if%><%if params.goodsdata=='5'%>推荐商品<%/if%><%if params.goodsdata=='6'%>促销商品<%/if%><%if params.goodsdata=='7'%>包邮商品<%/if%><%if params.goodsdata=='8'%>限时卖商品<%/if%><%if params.goodsdata=='9'%>卡路里兑换商品<%/if%><%if params.goodsdata=='10'%>卡路里抽奖商品<%/if%>读取)

            </div>

            <%/if%>

            <%if params.showprice=='1'%>

            <p class="productprice  <%if (params.showproductprice !=1) && (params.showsales !=1)%>noheight<%/if%>">

                <%if params.showproductprice==1%>

                <span style="color: <%style.productpricecolor%>;"><%params.productpricetext||'原价'%>:<span <%if params.productpriceline==1%>style="text-decoration: line-through;"<%/if%>>￥99.00</span></span>

                <%/if%>

                <%if params.showsales==1&&item.sales>=0%>

                <span style="color: <%style.salescolor%>;"><%params.salestext||'销量'%>:0</span>

                <%/if%>

            </p>

            <div class="price">

                                <span class="text" style="color: <%style.pricecolor%>;">

                                    <p class="minprice">￥20.00</p>

                                </span>

                <%if style.buystyle!=''%>

                <%if style.buystyle=='buybtn-1'%>

                <span class="buy" style="border-color: <%style.buybtncolor%>;color:<%style.buybtncolor%> ">购买</span>

                <%/if%>

                <%if style.buystyle=='buybtn-2'%>

                <span class="buy buy buybtn-2" style="border-color: <%style.buybtncolor%>;background-color: <%style.buybtncolor%>;">购买</span>

                <%/if%>

                <%if style.buystyle=='buybtn-3'%>

                <span class="buy buybtn-3" style="background-color: <%style.buybtncolor%>; border-color: <%style.buybtncolor%>;"><i class="icon icon-cartfill"></i></span>

                <%/if%>

                <%if style.buystyle=='buybtn-4'%>

                <span class="buy buybtn-4" style="border-color: <%style.buybtncolor%>;"><i class="icon icon-cart" style="color: <%style.buybtncolor%>;"></i></span>

                <%/if%>

                <%if style.buystyle=='buybtn-5'%>

                <span class="buy buybtn-5" style="border-color: <%style.buybtncolor%>;"><i class="icon icon-add" style="color: <%style.buybtncolor%>;"></i></span>

                <%/if%>

                <%if style.buystyle=='buybtn-6'%>

                <span class="buy buybtn-6" style="background-color: <%style.buybtncolor%>; border-color: <%style.buybtncolor%>;"><i class="icon icon-add"></i></span>

                <%/if%>

                <%/if%>

            </div>

            <%/if%>

        </div>



                    <%if params.saleout>-1%>

                    <%if params.saleout==0%><div class="salez" style="background-image: url('<?php  echo tomedia($_W['shopset']['shop']['saleout'])?>'); "></div><%/if%>

                    <%if params.saleout==1%><div class="salez diy" style="background-image: url('../addons/ewei_shopv2/plugin/app/static/images/default/saleout-<%style.saleoutstyle%>.png'); "></div><%/if%>

                    <%/if%>

    </div>

    <%/each%>

    <%/if%>

    </div>

    </div>

</script>



<script type="text/html" id="tpl_show_video">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-video style<%style.ratio%>" style='background-image: url("<%imgsrc(params.poster) || "../addons/ewei_shopv2/plugin/app/static/images/default/videocover.png"%>")'></div>

    </div>

</script>





<script type="text/html" id="tpl_show_copyright">

    <div class="drag fixed" data-itemid="<%itemid%>" style="color: #cecece;font-size: 12px;">

        <div style="text-align: center;padding: 10px 24px;line-height: 20px; <%if style.showtype =='1'%><%/if%>">

            <%if params.showimg =='1'%><img src="<%imgsrc params.imgurl%>" style="width: 40px;height: 40px;vertical-align: middle;"><%if style.showtype =='0'%></br><%/if%><%/if%>

            <%if style.showtype !='0'%>

            <span style="display: inline-block;max-width: 200px;vertical-align: middle;text-align: left"><%params.copyright%></span>

            <%else%>

            <%params.copyright%>

            <%/if%>

        </div>

    </div>

</script>





<!--小程序商品详情-->

<!--商品图-->

<script type="text/html" id="tpl_show_detail_swipe">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="diy-swipe">

            <img src="../addons/ewei_shopv2/plugin/diypage/static/template/detail1/goods.jpg">

            <div class="dots <%style.dotalign||'left'%> <%style.dotstyle||'rectangle'%>" style="padding: 0 <%style.leftright||'10'%>px; bottom: <%style.bottom||'10'%>px; opacity: <%style.opacity||'0.8'%>;">

                <span style="background: <%style.background||'#000000'%>;"></span>

                <span style="background: <%style.background||'#000000'%>;"></span>

                <span style="background: <%style.background||'#000000'%>;"></span>

            </div>

        </div>

    </div>

</script>

<!--商品信息-->

<script type="text/html" id="tpl_show_detail_info">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-detail-group" style="background: <%style.background%>; margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px;">

            <div class="fui-cell">

                <div class="fui-cell-text name" style="color: <%style.titlecolor%>;">新款个性创意iphone6s手机壳磨砂苹果6情侣保护套i6日韩国简约潮</div>

                <%if params.hideshare=='0'||!params.hideshare%>

                <a class="fui-cell-remark share" id="btn-share" style="color: #ff5555">

                    <i class="icon <%params.share_icon||'icon-share'%>"></i>

                    <p><%params.share%></p>

                </a>

                <%/if%>

            </div>

            <div class="fui-cell goods-subtitle">

                <span class="" style="color:<%style.subtitlecolor%>;">四角防摔气囊，送纳米防爆膜</span>

            </div>

            <div class="fui-cell">

                <div class="fui-cell-text price">

                    <span class="" style="color: <%style.pricecolor%>;">￥58 <span class="original" style="color: <%style.textcolor%>;">￥128</span></span>

                </div>

            </div>

            <div class="fui-cell" style="padding-bottom: 4px;">

                <div class="fui-labeltext" style="border: none; background: <%style.timecolor%>;">

                    <i class="icon icon-clock" style="float: left;color: <%style.timetextcolor%>;line-height: 30px;margin: 0 3px;"></i>

                    <div class="label" style="font-size: 14px; height: 1.45rem; background: <%style.timecolor%>;color: <%style.timetextcolor%>;">距离限时购结束</div>

                    <div class="text" style="color: <%style.timetextcolor%>;"><span class="day number" style="color: <%style.timetextcolor%>;">0</span><span class="time">天</span><span class="hour number" style="color: <%style.timetextcolor%>;">04</span><span class="time">小时</span><span class="minute number" style="color: <%style.timetextcolor%>;">32</span><span class="time">分</span><span class="second number" style="color: <%style.timetextcolor%>;">26</span><span class="time">秒</span>

                    </div>

                </div>

            </div>

            <div class="fui-cell">

                <div class="fui-cell-text flex" style="color: <%style.textcolor%>;">

                    <span>快递:  10.00</span>

                    <span>销量:  3,663 件</span>

                    <span>山东 济南</span>

                </div>

            </div>



            <div class="fui-cell">

                <div class="fui-cell-text" style="color: <%style.textcolor%>;">

                    <label class="label label-default">预售</label> 预计发货时间：购买后<span class="text-danger" style="color: <%style.textcolorhigh%>;"> 7 </span>天发货</div>

                </div>

            </div>

        </div>

</script>

<!--营销信息-->

<script type="text/html" id="tpl_show_detail_sale">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-cell-click  fui-sale-group" style="background: <%style.background%>; margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px;">



            <div class="fui-cell">

                <div class="fui-cell-text" style="color: <%style.textcolor%>;">

                    <span style="margin-right: 0.6rem">优惠券</span>

                    <span class="coupon-mini" style="background: <%style.textcolorhigh%>;">¥50</span>

                    <span class="coupon-mini" style="background: <%style.textcolorhigh%>;">¥50</span>

                    <span class="coupon-mini" style="background: <%style.textcolorhigh%>;">¥50</span>

                </div>

                <div class="fui-cell-remark"></div>

            </div>



            <div class="fui-cell">

                <div class="fui-cell-label top" style="color: <%style.textcolor%>;">会员</div>

                <div class="fui-cell-text" style="color: <%style.textcolor%>;">

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">VIP </span>

                        <span>可享受 <span class="text-danger" style="color: <%style.textcolorhigh%> !important;">￥10</span> 的价格</span>

                    </div>

                </div>

            </div>



            <div class="fui-cell">

                <div class="fui-cell-label top" style="color: <%style.textcolor%>;">活动</div>

                <div class="fui-cell-text" style="color: <%style.textcolor%>;">

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">满减 </span>

                        <span>全场满<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">&yen;100</span>立减<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">&yen;10</span></span>

                    </div>

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">包邮</span>

                        <span>全场满<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">&yen;100</span>包邮</span>

                    </div>

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">卡路里</span>

                        <span>购买赠送<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">10</span>卡路里</span>

                    </div>

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">复购</span>

                        <span>此商品重复购买可享受<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">8</span>折优惠</span>

                    </div>

                    <div class="sale-line">

                        <span class="sale-tip" style="border-color: <%style.textcolorhigh%>; color: <%style.textcolorhigh%>;">全返</span>

                        <span>此商品享受<span class="text-danger" style="color: <%style.textcolorhigh%> !important;">&yen;100</span>的全返</span>

                    </div>

                </div>

                <div class="fui-cell-remark"></div>

            </div>

            <div class="fui-cell">

                <div class="fui-cell-label" style="color: <%style.textcolor%>;">赠品</div>

                <div class="fui-cell-text" style="color: <%style.textcolor%>;">赠品名称</div>

                <div class="fui-cell-remark"></div>

            </div>



            <div class="fui-cell" style="background: #fafafa;">

                <div class="fui-cell-text">

                    <span class="label label-danger" style="border-radius: 5px;">货到付款</span>

                    <span class="label label-danger" style="border-radius: 5px;">正品保证</span>

                    <span class="label label-danger" style="border-radius: 5px;">保修</span>

                    <span class="label label-danger" style="border-radius: 5px;">发票</span>

                </div>

            </div>

        </div>



        <div class="fui-cell-group fui-cell-click">

            <div class="fui-cell">

                <div class="fui-cell-label" style="width: 60px;">配送区域</div>

                <div class="fui-cell-text">山东、山西、陕西</div>

                <div class="fui-cell-remark"></div>

            </div>

        </div>

    </div>

</script>

<!--商品规格-->

<script type="text/html" id="tpl_show_detail_spec">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-cell-click" style="background: <%style.background%>; margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px;">

            <div class="fui-cell">

                <div class="fui-cell-text option-selector" style="color: <%style.textcolor%>;">请选择颜色、分类等</div>

                <div class="fui-cell-remark"></div>

            </div>

        </div>

    </div>

</script>

<!--相关套餐-->

<script type="text/html" id="tpl_show_detail_package">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-comment-group" style="background: <%style.background%>; margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px;">

            <div class="fui-cell fui-cell-click">

                <div class="fui-cell-text desc" style="color: <%style.textcolor%>;">相关套餐</div>

                <div class="fui-cell-text desc label" style="color: <%style.textcolor%>;">更多套餐</div>

                <div class="fui-cell-remark"></div>

            </div>

            <div class="fui-cell">

                <div class="fui-cell-text comment ">

                    <div class="fui-list package-list">

                        <div class="fui-list-inner package-goods" style="padding: 0;">

                            <img src="../addons/ewei_shopv2/plugin/diypage/static/template/detail1/goods.jpg" class="package-goods-img" alt="戴尔显示器24寸">

                            <span>粉+深蓝情侣套装</span>

                        </div>

                        <div class="fui-list-inner package-goods">

                            <img src="../addons/ewei_shopv2/plugin/diypage/static/template/detail1/goods.jpg" class="package-goods-img" alt="新款个性创意iphone6s手机壳磨砂苹果6情侣保护套i6日韩国简约潮">

                            <span>粉+深蓝情侣套装2</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</script>

<!--店铺信息-->

<script type="text/html" id="tpl_show_detail_shop">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-shop-group" style="background: <%style.background%>; margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px;">

            <div class="fui-list">

                <div class="fui-list-media <%params.logostyle%>">

                    <img src="<%imgsrc params.shoplogo||'../addons/ewei_shopv2/static/images/designer.jpg'%>">

                </div>

                <div class="fui-list-inner">

                    <div class="title" style="color: <%style.shopnamecolor%>;"><%params.shopname||'XX商城'%></div>

                    <div class="subtitle" style="color: <%style.shopdesccolor%>;"><%params.shopdesc||'这里是XX商城简介商城简介商城简介商城简介商城简介'%></div>

                </div>

                <div class="fui-list-angle" style="margin-right: 0">

                    <a class="btn" style="border-color: <%style.rightnavcolor%>; color: <%style.rightnavcolor%>;padding: 6px 10px;"><%params.rightnavtext%></a>

                </div>

            </div>

        </div>

    </div>

</script>

<!--购买可见-->

<script type="text/html" id="tpl_show_detail_buyshow">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group" style="margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px; background: <%style.background%>;">

            <div class="fui-cell">

                <div class="feii-cell-text">

                    <p>此处为购买后可见区域</p>

                    <p>此处为购买后可见区域</p>

                    <p>此处为购买后可见区域</p>

                </div>

            </div>

        </div>

    </div>

</script>

<!--商品评价-->

<script type="text/html" id="tpl_show_detail_comment">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-cell-group fui-comment-group" style="display:none;margin-top: <%style.margintop%>px; margin-bottom: <%style.marginbottom%>px; background: <%style.background%>;">

            <div class="fui-cell fui-cell-click">

                <div class="fui-cell-text desc" style="color: <%style.subcolor%>;">评价(1)</div>

                <div class="fui-cell-text desc label" style="color: <%style.subcolor%>;"><span style="color: <%style.maincolor%>;">100%</span> 好评</div>

                <div class="fui-cell-remark"></div>

            </div>

            <div class="fui-cell" style="padding: 0 0.5rem 0.5rem;">

                <div class="fui-cell-text comment " style="padding-top: 0.5rem">

                    <div class="info">

                        <div class="star">

                            <span class="shine" style="color: <%style.maincolor%>;">★</span>

                            <span class="shine" style="color: <%style.maincolor%>;">★</span>

                            <span class="shine" style="color: <%style.maincolor%>;">★</span>

                            <span class="shine" style="color: <%style.maincolor%>;">★</span>

                            <span class="shine" style="color: <%style.maincolor%>;">★</span>

                        </div>

                        <div class="date" style="color: <%style.subcolor%>;">用户** 2016-10-25 17:09</div>

                    </div>

                    <div class="remark" style="color: <%style.textcolor%>;">商品很不错！我很喜欢！必须5星好评！</div>

                </div>

            </div>

            <div class="show-allshop">

                <span class="btn">查看全部评价</span>

            </div>

        </div>

    </div>

</script>

<!--底部导航-->

<script type="text/html" id="tpl_show_detail_navbar">

    <div class="drag fixed nodelete" data-itemid="<%itemid%>">

        <div class="fui-navbar bottom-buttons" style="background: <%style.background%>;">

            <%if params.hidelike<1%>

            <%if params.hidelike==0%><%define iconclass="icon-like"%><%define icontext="关注"%><%/if%>

            <%if params.hidelike==-1%><%define iconclass="icon-shop"%><%define icontext="店铺"%><%/if%>

            <%if params.hidelike==-2%><%define iconclass="icon-cart"%><%define icontext="购物车"%><%/if%>

            <%if params.hidelike==-3%><%define iconclass=params.likeiconclass||'icon-like'%><%define icontext=params.liketext||'关注'%><%/if%>

            <nav class="nav-item ">

                <span class="icon <%iconclass%>" style="color: <%style.iconcolor%>;"></span>

                <span class="label" style="color: <%style.textcolor%>;"><%icontext%></span>

            </nav>

            <%/if%>

            <%if params.hideshop<1%>

            <%if params.hideshop==0%><%define iconclass="icon-shop"%><%define icontext="店铺"%><%/if%>

            <%if params.hideshop==-1%><%define iconclass="icon-like"%><%define icontext="关注"%><%/if%>

            <%if params.hideshop==-2%><%define iconclass="icon-cart"%><%define icontext="购物车"%><%/if%>

            <%if params.hideshop==-3%><%define iconclass=params.shopiconclass||'icon-shop'%><%define icontext=params.shoptext||'店铺'%><%/if%>

            <nav class="nav-item">

                <span class="icon <%iconclass%>" style="color: <%style.iconcolor%>;"></span>

                <span class="label" style="color: <%style.textcolor%>;"><%icontext%></span>

            </nav>

            <%/if%>

            <%if params.hidecart<1%>

            <%if params.hidecart==0%><%define iconclass="icon-cart"%><%define icontext="购物车"%><%/if%>

            <%if params.hidecart==-1%><%define iconclass="icon-like"%><%define icontext="关注"%><%/if%>

            <%if params.hidecart==-2%><%define iconclass="icon-shop"%><%define icontext="店铺"%><%/if%>

            <%if params.hidecart==-3%><%define iconclass=params.carticonclass||'icon-cart'%><%define icontext=params.carttext||'购物车'%><%/if%>

            <nav class="nav-item">

                <%if params.hidecart==0%>

                <span class="badge" style="background: <%style.dotcolor%>;">3</span>

                <%/if%>

                <span class="icon <%iconclass%>" style="color: <%style.iconcolor%>;"></span>

                <span class="label" style="color: <%style.textcolor%>;"><%icontext%></span>

            </nav>

            <%/if%>

            <%if params.hidecartbtn==0%>

            <nav class="nav-item btn cartbtn" style="background: <%style.cartcolor%>;">加入购物车</nav>

            <%/if%>

            <nav class="nav-item btn buybtn" style="background: <%style.buycolor%>;"><%params.textbuy||'立刻购买'%></nav>

        </div>

    </div>

</script>



<!--选项卡-->

<script type="text/html" id="tpl_show_tabbar">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="fui-tab fui-tab-danger <%if style.showtype =='1'%>style1<%/if%> <%if style.showtype =='2'%>style2<%/if%>">

            <!--样式一-->

            <%if style.showtype =='1'%>

            <%define index=0%>

            <%each data as item%>

            <a <%if index==0%>class="active" style="color: <%style.activecolor%>; border-color: <%style.activecolor%>; background: <%style.activebackground%>;"<%else%>style="color: <%style.color%>;background: <%style.background%>;""<%/if%>><%item.text||'未设置'%></a>

            <%define index++%>

            <%/each%>

            <%/if%>

            <!--样式二-->

            <%if style.showtype =='2'%>

            <%define index=0%>

            <%each data as item%>

            <a <%if index==0%>class="active" style="color: <%style.activecolor%>; border-color: <%style.activecolor%>; background: <%style.activebackground%>;"<%else%>style="color: <%style.color%>;background: <%style.background%>;""<%/if%>><%item.text||'未设置'%></a>

            <%define index++%>

            <%/each%>

            <%/if%>

        </div>

    </div>

</script>

<!--顶部菜单-->

<script type="text/html" id="tpl_show_topmenu">

    <div class="drag fixed" data-itemid="<%itemid%>">

        <div class="fui-tab fui-tab-danger <%if style.showtype =='1'%>style1<%/if%> <%if style.showtype =='2'%>style2<%/if%>">

            <!--样式一-->

            <%if style.showtype =='1'%>

            <%define index=0%>

            <%each data as item%>

            <a <%if index==0%>class="active" style="color: <%style.activecolor%>; border-color: <%style.activecolor%>; background: <%style.activebackground%>;"<%else%>style="color: <%style.color%>;background: <%style.background%>;""<%/if%>><%item.text||'未设置'%></a>

            <%define index++%>

            <%/each%>

            <%/if%>

            <!--样式二-->

            <%if style.showtype =='2'%>

            <%define index=0%>

            <%each data as item%>

            <a <%if index==0%>class="active" style="color: <%style.activecolor%>; border-color: <%style.activecolor%>; background: <%style.activebackground%>;"<%else%>style="color: <%style.color%>;background: <%style.background%>;""<%/if%>><%item.text||'未设置'%></a>

            <%define index++%>

            <%/each%>

            <%/if%>

        </div>

    </div>

    <div style="display: <%params.datatype=='groups' || params.datatype=='category' || params.datatype=='goodsids'? 'block': 'none'%>;">

        <div class="fui-goods-group block" style="background: #f3f3f3;">

            <div class="fui-goods-item" data-goodsid="">

                <div class="image triangle" style="background-image: url('../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-1.jpg');" data-text="推荐">



                </div>

                <div class="detail">

                    <div class="name" style="color: #000000;">

                        这里是商品标题

                    </div>

                    <p class="productprice  noheight">



                    </p>

                    <div class="price">

                            <span class="text" style="color: #ff5555;">

                                <p class="minprice">

                                        ￥20.00

                                </p>

                            </span>

                        <span class="buy" style="border-color: #ff5555;color:#ff5555 ">购买</span>

                    </div>

                </div>

                <div class="salez" style="background-image: url(''); "></div>

            </div>

            <div class="fui-goods-item" data-goodsid="">

                <div class="image triangle" style="background-image: url('../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-2.jpg');" data-text="推荐">

                </div>

                <div class="detail">

                    <div class="name" style="color: #000000;">

                        这里是商品标题

                    </div>

                    <p class="productprice  noheight">



                    </p>

                    <div class="price">

                                <span class="text" style="color: #ff5555;">

                                    <p class="minprice">

                                            ￥20.00

                                    </p>

                                </span>

                        <span class="buy" style="border-color: #ff5555;color:#ff5555 ">购买</span>

                    </div>

                </div>

                <div class="salez" style="background-image: url(''); "></div>

            </div>



            <div class="fui-goods-item" data-goodsid="">

                <div class="image triangle" style="background-image: url('../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-3.jpg');" data-text=" 推荐">

                </div>

                <div class="detail">

                    <div class="name" style="color: #000000;">

                        这里是商品标题

                    </div>

                    <p class="productprice  noheight">

                    </p>

                    <div class="price">

                            <span class="text" style="color: #ff5555;">

                                <p class="minprice">

                                        ￥20.00

                                </p>

                            </span>

                        <span class="buy" style="border-color: #ff5555;color:#ff5555 ">购买</span>

                    </div>

                </div>

                <div class="salez" style="background-image: url(''); "></div>

            </div>



            <div class="fui-goods-item" data-goodsid="">

                <div class="image triangle" style="background-image: url('../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-4.jpg');" data-text="推荐">

                </div>

                <div class="detail">

                    <div class="name" style="color: #000000;">

                        这里是商品标题

                    </div>

                    <p class="productprice  noheight">

                    </p>

                    <div class="price">

                            <span class="text" style="color: #ff5555;">

                                <p class="minprice">

                                        ￥20.00

                                </p>

                            </span>

                        <span class="buy" style="border-color: #ff5555;color:#ff5555 ">购买</span>

                    </div>

                </div>

                <div class="salez" style="background-image: url(''); "></div>

            </div>

        </div>

    </div>

    </div>

</script>

<script type="text/html" id="tpl_show_seckillgroup">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="seckill-group <%params.hideborder==1&&'noborder'%>" style="margin-top: <%style.margintop%>px;">

            <div class="seckill-title">

                <div class="seckill-text">

                    <%if params.iconurl%>

                    <img src="<%imgsrc params.iconurl%>"/>

                    <%/if%>

                    <span class="title" style="color: <%style.titlecolor%>;">10点场 距结束</span>

                    <div class="killtime" style="color: #ffffff;">

                        <span class="item" style="background:<%style.titlecolor%>;border:1px solid <%style.titlecolor%>">09</span>

                        <d style="color:<%style.titlecolor%>">:</d>

                        <span class="item" style="background:<%style.titlecolor%>;border:1px solid <%style.titlecolor%>">08</span>

                        <d style="color:<%style.titlecolor%>">:</d>

                        <span class="item" style="background:<%style.titlecolor%>;border:1px solid <%style.titlecolor%>">07</span>

                    </div>

                </div>

                <div class="seckill-remark" style="color: <%style.titlecolor%>;">更多<span class="icow icow-jiantou-copy"></span></div>

            </div>

            <div class="seckill-goods">

                <div class="item">

                    <div class="thumb">

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/iphone6.jpg"/>

                        <!--<div class="tag">热卖</div>-->

                    </div>

                    <div class="marketprice" style="color: <%style.marketpricecolor%>;">￥199</div>

                    <div class="productprice" style="color: <%style.productpricecolor%>;">￥599</div>

                </div>

                <div class="item">

                    <div class="thumb">

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/iphone6.jpg"/>

                        <!--<div class="tag orange">热卖</div>-->

                    </div>

                    <div class="marketprice" style="color: <%style.marketpricecolor%>;">￥199</div>

                    <div class="productprice" style="color: <%style.productpricecolor%>;">￥599</div>

                </div>

                <div class="item">

                    <div class="thumb">

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/iphone6.jpg"/>

                        <!--<div class="tag purple">热卖</div>-->

                    </div>

                    <div class="marketprice" style="color: <%style.marketpricecolor%>;">￥199</div>

                    <div class="productprice" style="color: <%style.productpricecolor%>;">￥599</div>

                </div>

                <div class="item">

                    <div class="thumb">

                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/iphone6.jpg"/>

                        <!--<div class="tag green">热卖</div>-->

                    </div>

                    <div class="marketprice" style="color: <%style.marketpricecolor%>;">￥199</div>

                    <div class="productprice" style="color: <%style.productpricecolor%>;">￥599</div>

                </div>

            </div>

        </div>

    </div>

</script>

<script type="text/html" id="tpl_show_seckill_times">

    <div class="drag nodelete" data-itemid="<%itemid%>">

        <div class=" seckill-page">

            <div class="swiper-container time-container">

                <div class="swiper-wrapper">

                    <div class="swiper-slide time-slide current" data-index="0" >

                        <span class="time">18:00</span>

                        <span class="text">已结束</span>

                    </div>

                    <div class="swiper-slide time-slide " data-index="1" >

                        <span class="time">19:00</span>

                        <span class="text">抢购中</span>

                    </div>

                    <div class="swiper-slide time-slide" data-index="2" >

                        <span class="time">20:00</span>

                        <span class="text">即将开始</span>

                    </div>

                    <div class="swiper-slide time-slide" data-index="3" >

                        <span class="time">21:00</span>

                        <span class="text">即将开始</span>

                    </div>

                    <div class="swiper-slide time-slide" data-index="4" >

                        <span class="time">22:00</span>

                        <span class="text">即将开始</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_detail_seckill">

    <div class="drag" data-itemid="<%itemid%>">

        <div class="page-goods-detail <%style.theme%>" style="zoom: .7">

            <div class="seckill-container ">

                <div class="fui-list seckill-list" style="flex: 2;">

                    <div class="fui-list-media seckill-price">

                        ¥

                        <span>

                8999.00

              </span>

                    </div>

                    <div class="fui-list-inner">

                        <div class="text">

                <span class="oldprice">

                  9998.00

                </span>

                        </div>

                    </div>

                </div>

                <div class="fui-list seckill-list1">

                    <div class="fui-list-inner">

                        <div class="text ">

                            已出售 20%

                        </div>

                        <div class="text ">

                <span class="process">

                  <div class="process-inner" style="width:20%">

                  </div>

                </span>

                        </div>

                    </div>

                </div>

                <div class="fui-list seckill-list2" style="">

                    <div class="fui-list-inner">

                        <div class="text ">

                            距离结束

                        </div>

                        <div class="text timer">

                <span class="time-hour">

                  04

                </span>

                            :

                            <span class="time-min">

                  11

                </span>

                            :

                            <span class="time-sec">

                  21

                </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</script>





<script type="text/html" id="tpl_show_seckill_rooms">

    <div class="drag" data-itemid="<%itemid%>">

        <div class=" seckill-page">

            <div class="swiper-container room-container">

                <div class="swiper-wrapper" style="height: 40px">

                    <a class="swiper-slide room-slide selected" >主会场</a>

                    <a class="swiper-slide room-slide" >手机</a>

                    <a class="swiper-slide room-slide" >家电</a>

                    <a class="swiper-slide room-slide" >母婴</a>

                    <a class="swiper-slide room-slide" >服装</a>

                </div>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_seckill_advs">

    <div class="drag" data-itemid="<%itemid%>">

        <div class=" seckill-page">

            <div class="swiper-container adv-container" style="">

                <div class="swiper-wrapper">

                    <%each data as item%>

                    <div class="swiper-slide adv-slide" >

                        <img src="<%imgsrc item.imgurl%>"/>

                    </div>

                    <%/each%>

                </div>

            </div>

        </div>

    </div>

</script>



<script type="text/html" id="tpl_show_seckill_list">

    <div class="drag" data-itemid="<%itemid%>">

        <div class=" seckill-page" >

            <div class="swiper-container goods-container" style="">

                <div class="swiper-wrapper">

                    <div class="swiper-slide goods-slide">

                        <div class="fui-list-group">

                            <div class="fui-list-group-title" >

                                <div class="timer">

                                    <d >距结束</d>

                                    <span class="time-hour">08</span>

                                    <d >:</d>

                                    <span class="time-min" >05</span>

                                    <d >:</d>

                                    <span class="time-sec" >22</span>

                                </div>

                                <div style='color:#666;font-size:13px'>抢购中 先下单先得哦</div>

                            </div>

                            <div class="fui-list align-start">

                                <div class="fui-list-media">

                                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-1.jpg"/>

                                </div>

                                <div class="fui-list-inner">

                                    <a class="text ">

                                        秒杀商品的名称秒杀商品的名称秒杀商品的名称秒杀

                                    </a>

                                    <div class="info-container1">

                                        <div class="info">

                                            <a class="btn btn-danger btn-sm ">

                                                去抢购

                                            </a>

                                            <div class="price">

                                                ¥1

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <div class="process">

                                                <div class="inner" style="width:50%;">

                                                </div>

                                            </div>

                                            <span class="process-text">

                      已抢 <span>50%</span> 

                    </span>

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                    </div>

                                    <div class="info-container2">

                                        <div class="info">

                                            <div class="price">

                                                ¥1

                                            </div>

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <a class="btn btn-danger btn-sm ">

                                                去抢购

                                            </a>

                                            <div class="process-container">

                                                <div class="process">

                                                    <div class="inner" style="width:50%;">

                                                    </div>

                                                </div>

                                                <div class="process-text">

                                                    已抢 <span>50%</span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="fui-list align-start">

                                <div class="fui-list-media">

                                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-2.jpg"/>

                                </div>

                                <div class="fui-list-inner">

                                    <a class="text ">

                                        秒杀商品的名称秒杀商品的名称秒杀商品的名称秒杀

                                    </a>

                                    <div class="info-container1">

                                        <div class="info">

                                            <a class="btn btn-success btn-sm ">

                                                等待抢购

                                            </a>

                                            <div class="price">

                                                ¥1

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                    </div>

                                    <div class="info-container2">

                                        <div class="info">

                                            <div class="price">

                                                ¥1

                                            </div>

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <a class="btn btn-success btn-sm ">

                                                等待抢购

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="fui-list align-start">

                                <div class="fui-list-media">

                                        <img src="../addons/ewei_shopv2/plugin/diypage/static/images/default/goods-3.jpg"/>

                                </div>

                                <div class="fui-list-inner">

                                    <a class="text ">

                                        秒杀商品的名称秒杀商品的名称秒杀商品的名称秒杀

                                    </a>

                                    <div class="info-container1">

                                        <div class="info">

                                            <a class="btn btn-default btn-sm ">

                                                已抢完

                                            </a>

                                            <div class="price">

                                                ¥1

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <div class="process">

                                                <div class="inner" style="width:100%;">

                                                </div>

                                            </div>

                                            <span class="process-text">

                      已抢 <span>100%</span>

                    </span>

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                    </div>

                                    <div class="info-container2">

                                        <div class="info">

                                            <div class="price">

                                                ¥1

                                            </div>

                                            <div class="price1">

                                                ¥88

                                            </div>

                                        </div>

                                        <div class="info info1">

                                            <a class="btn btn-default btn-sm ">

                                                已抢完

                                            </a>

                                            <div class="process-container">

                                                <div class="process">

                                                    <div class="inner" style="width:100%;">

                                                    </div>

                                                </div>

                                                <div class="process-text">

                                                    已抢 <span>100%</span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</script>