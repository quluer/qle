<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('merchmanage/common', TEMPLATE_INCLUDEPATH)) : (include template('merchmanage/common', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=TcxKakL9HTpATQ3ptrjEPDK1Gh11oG1D"></script>
<div class='fui-page fui-page-current'>
    <div class="fui-header fui-header-success">
        <div class="fui-header-left">
            <a class="back"></a>
        </div>
        <div class="title">店铺设置</div>
        <div class="fui-header-right"></div>
    </div>
    <div class='fui-content navbar'>

        <div class="fui-list-group">
            <div class="fui-list">
                <input type="file"  name="file-shoplogo" id="file-shoplogo" />
                <input type="hidden" id="shoplogo" value="<?php  echo $shopset['logo'];?>" />
                <div class="fui-list-inner">
                    <div class="title">商户logo</div>
                </div>
                <div class="fui-list-media">
                    <img src="<?php  echo tomedia($shopset['logo'])?>" class="round" id="showlogo" />
                </div>
            </div>
        </div>

        <div class="fui-cell-group">
            <div class="fui-cell">
                <div class="fui-cell-label">商户名称</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入商户名称" class="fui-input" value="<?php  echo $shopset['merchname'];?>" id="shopname" />
                </div>
            </div>
            <div class="fui-cell fui-cell-textarea">
                <div class="merch-desc">商户简介</div>
                <div class="fui-cell-info">
                    <textarea rows="5" placeholder="请输入商户简介" id="shopdesc"><?php  echo $shopset['desc'];?></textarea>
                </div>
            </div>
            <div class="fui-cell">
                <div class="fui-cell-label">联系人</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入联系人" class="fui-input" value="<?php  echo $shopset['realname'];?>" id="realname" />
                </div>
            </div>
            <div class="fui-cell">
                <div class="fui-cell-label">联系电话</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入联系电话" class="fui-input" value="<?php  echo $shopset['mobile'];?>" id="mobile" />
                </div>
            </div>
			<div class="fui-cell merch-address">
                <div class="fui-cell-label">快速定位</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入地址" class="fui-input" value="<?php  echo $shopset['address'];?>" id="address" />
					<div class='merch-address-weizhi' id='addressclick'><img src="http://paokucoin.com/img/backgroup/weizhi.png" alt=""></div>
					 <input type="hidden"  class="fui-input" value="<?php  echo $shopset['lng'];?>" id="lng" />
					 <input type="hidden"  class="fui-input" value="<?php  echo $shopset['lat'];?>" id="lat" />
                </div>
            </div>
            <div class="fui-cell merch-address">
                <div class="fui-cell-label">详细地址</div>
                <div class="fui-cell-info">
                    <input type="text" placeholder="请输入详细地址" class="fui-input" value="" id="detail_addr" />
                </div>
            </div>
        </div>


       
        <div class="btn btn-success block" id="btn-submit">保存设置</div>
        <div class="btn btn-danger block" id="btn-logout">退出登录</div>
    </div>

    <script language="javascript">
        require(['../addons/ewei_shopv2/plugin/merchmanage/static/js/base.js'],function(modal){
            modal.initShop();
        });
    </script>
</div>

<div class='address-mask' id='merchmask' style='width:100%;height:95%;position:fixed;top:0;left:0;background:#fff;font-size:26px;display:none'>
	<div class='mask-con' style='width:100%;height:90%'>
		<div id="l-map" style='height:280px;width:100%;'></div>
		<div id="r-result" style='width:100%;'>请输入:<input type="text" id="suggestId" size="20" value="百度" style="width:100%;height:1.5rem" /></div>
		<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
	</div>
	<div class='address-mask-close' id='maskclose' style='width:5rem;height:1.5rem;margin:auto;background:#008be4;border-radius:5px;color:#fff;font-size:22px;line-height:1.5rem;text-align:center'>确认</div>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=TcxKakL9HTpATQ3ptrjEPDK1Gh11oG1D"></script>
	<script type="text/javascript">
	var addressbtn=document.getElementById("addressclick");
	var mask=document.getElementById("merchmask");
	var maskclose=document.getElementById("maskclose");
	addressbtn.onclick=function(){
		mask.style.display="block";
	}
	maskclose.onclick=function(){
		mask.style.display="none";
	}
	

	// 百度地图API功能
		function G(id) {
			return document.getElementById(id);
		}

		var addr=document.getElementById("address").value;
		var pp;
		var map = new BMap.Map("l-map");
		
		if(addr==""){
			 //定位
			   var geolocation = new BMap.Geolocation();
				geolocation.getCurrentPosition(function(r){
					if(this.getStatus() == BMAP_STATUS_SUCCESS){
						var mk = new BMap.Marker(r.point);
						map.addOverlay(mk);
						map.panTo(r.point);
						var point = new BMap.Point(r.point.lng,r.point.lat);
						map.centerAndZoom(point,18);
						console.log(r.point)
						
					}
					else {
						alert('failed'+this.getStatus());
					}        
				},{enableHighAccuracy: true})
				
		}else{
		        console.log(document.getElementById("lng").value);
		        console.log(document.getElementById("lat").value);
		        if(document.getElementById("lng").value!=""&&document.getElementById("lat").value!=""){
			    
		         pp = new BMap.Point(document.getElementById("lng").value,document.getElementById("lat").value);
				map.centerAndZoom(pp, 18);
				map.addOverlay(new BMap.Marker(pp));    //添加标注
		        }else{
		        	map.centerAndZoom(addr,12);                   // 初始化地图,设置城市和地图级别。
		        }
		        
		}
	  
		
	   //获取经纬度
	    function showInfo(e){
		   //去除标注点
		   map.clearOverlays(); //删除所有点
		    console.log(e);
		    //经纬度
		    document.getElementById("lng").value=e.point.lng;
			document.getElementById("lat").value=e.point.lat;
			 pp = new BMap.Point(e.point.lng,e.point.lat);
			map.centerAndZoom(pp, 18);
			map.addOverlay(new BMap.Marker(pp));    //添加标注
		}
		map.addEventListener("click", showInfo);
		
		//获取地址
		var geocoder= new BMap.Geocoder(); 

	    map.addEventListener("click",function(e){ //给地图添加点击事件

	        geocoder.getLocation(e.point,function(rs){

	            console.log(rs.address); //地址描述(string)
	            //地址
	            document.getElementById("address").value=rs.address;
	           
	        });

	    });

		var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
			{"input" : "suggestId"
			,"location" : map
		});

		ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
		var str = "";
			var _value = e.fromitem.value;
			var value = "";
			if (e.fromitem.index > -1) {
				value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			}    
			str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
			
			value = "";
			if (e.toitem.index > -1) {
				_value = e.toitem.value;
				value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			}    
			str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
			G("searchResultPanel").innerHTML = str;
		});

		var myValue;
		ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
		var _value = e.item.value;
			myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
			console.log(myValue)
			 //地址
	         document.getElementById("address").value=myValue;
	           
			setPlace();
		});

		function setPlace(){
			map.clearOverlays();    //清除地图上所有覆盖物
			function myFun(){
				var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
				
				console.log(pp.lng);
				console.log(pp.lat);
				  //经纬度
			    document.getElementById("lng").value=pp.lng;
				document.getElementById("lat").value=pp.lat;
				 map.clearOverlays(); //删除所有点
				map.centerAndZoom(pp, 18);
				map.addOverlay(new BMap.Marker(pp));    //添加标注
			}
			var local = new BMap.LocalSearch(map, { //智能搜索
			  onSearchComplete: myFun
			});
			local.search(myValue);
		}

</script>

</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('merchmanage/_menu', TEMPLATE_INCLUDEPATH)) : (include template('merchmanage/_menu', TEMPLATE_INCLUDEPATH));?>