<?php
if (!(defined('IN_IA'))) {
	exit('Access Denied');
}


require EWEI_SHOPV2_PLUGIN . 'merchmanage/core/inc/page_merchmanage.php';
class Refund_EweiShopV2Page extends MerchmanageMobilePage
{
	protected function opData()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$refund = pdo_fetch('select * from ' . tablename('ewei_shop_order_refund') . ' where id=:id limit 1', array(':id' => $id));
		if (empty($refund)){
		    show_json(0, '未找到售后申请!');
		}
		$item=pdo_get("ewei_shop_order",array("id"=>$refund["orderid"]));

		$r_type = array('退款', '退货退款', '换货');
		return array('id' => $id, 'item' => $item, 'refund' => $refund, 'r_type' => $r_type);
	}

	protected function submit()
	{
		global $_W;
		global $_GPC;
		global $_S;
		$opdata = $this->opData();
		extract($opdata);
		$shopset = $_S['shop'];
		$uniacid = $_W['uniacid'];
// 		if (empty($item['refundstate'])) {
// 			show_json(0, '订单未申请维权，不需处理！');
// 		}

		$order=pdo_get("ewei_shop_order",array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
		
		//获取订单商品id
		if (!empty($refund["goods_id"])){
		    $goods_id=unserialize($refund["goods_id"]);
		    foreach ($goods_id as $k=>$v){
		        $gid[$k]=$v["goods_id"];
		    }
		    
		}
		//用户
		if ($item["user_id"]){
		    $member=m("member")->getMember($item["user_id"]);
		}else{
		    $member=m("member")->getMember($item["openid"]);
		}
		//订单商品金额
		if ($refund["goods_id"]){
		    $gg = pdo_fetch('SELECT sum(o.price) as price,sum(o.dispatchprice) as dispatchprice,sum(o.deductprice) as deductprice,sum(o.discount_price) as discount_price,sum(o.couponprice) as couponprice,sum(o.deductenough) as deductenough FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.id in(:id) and o.uniacid=:uniacid', array(':orderid' => $item['id'], ':uniacid' => $uniacid,':id'=>implode(",", $gid)));
		    // 		   var_dump($uniacid);var_dump($item["id"]); var_dump($gg);var_dump($gid);die;
		}
		
		if (empty($refund['refundno'])) {
			$refund['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
			pdo_update('ewei_shop_order_refund', array('refundno' => $refund['refundno']), array('id' => $refund['id']));
		}


		$refundstatus = intval($_GPC['refundstatus']);
		$refundcontent = trim($_GPC['refundcontent']);
		$time = time();
		$change_refund = array();
		

		if ($refundstatus == 0) {
			show_json(1);
		}
		 else if ($refundstatus == 3) {
			$raid = $_GPC['raid'];
			$message = trim($_GPC['message']);

			if ($raid == 0) {
				$raddress = pdo_fetch('select * from ' . tablename('ewei_shop_refund_address') . ' where isdefault=1 and uniacid=:uniacid and merchid=0 limit 1', array(':uniacid' => $uniacid));
			}
			 else {
				$raddress = pdo_fetch('select * from ' . tablename('ewei_shop_refund_address') . ' where id=:id and uniacid=:uniacid and merchid=0 limit 1', array(':id' => $raid, ':uniacid' => $uniacid));
			}

			if (empty($raddress)) {
				$raddress = pdo_fetch('select * from ' . tablename('ewei_shop_refund_address') . ' where uniacid=:uniacid and merchid=0 order by id desc limit 1', array(':uniacid' => $uniacid));
			}


			unset($raddress['uniacid']);
			unset($raddress['openid']);
			unset($raddress['isdefault']);
			unset($raddress['deleted']);
			$raddress = iserializer($raddress);
			$change_refund['reply'] = '';
			$change_refund['refundaddress'] = $raddress;
			$change_refund['refundaddressid'] = $raid;
			$change_refund['message'] = $message;

			if (empty($refund['operatetime'])) {
				$change_refund['operatetime'] = $time;
			}


			if ($refund['status'] != 4) {
				$change_refund['status'] = 3;
			}


			pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $refund['id']));
			//更新订单操作
			$exchange_goods["refundstatus"]=2;
			pdo_update("ewei_shop_order_goods",$exchange_goods,array("refundid"=>$refund["id"]));
			pdo_update("ewei_shop_order",$exchange_goods,array("refundid"=>$refund["id"]));
			
			m('notice')->sendOrderMessage($item['id'], true);
		}
		 else if ($refundstatus == 5) {
			$change_refund['rexpress'] = $_GPC['rexpress'];
			$change_refund['rexpresscom'] = $_GPC['rexpresscom'];
			$change_refund['rexpresssn'] = trim($_GPC['rexpresssn']);
			$change_refund['status'] = 5;

			if (($refund['status'] != 5) && empty($refund['returntime'])) {
				$change_refund['returntime'] = $time;

				if (empty($refund['operatetime'])) {
					$change_refund['operatetime'] = $time;
				}

			}
			pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $refund['id']));
			//更新订单相关状态--完成
			$exchange_goods["refundstatus"]=1;
			pdo_update("ewei_shop_order_goods",$exchange_goods,array("refundid"=>$refund["id"]));
			pdo_update("ewei_shop_order",$exchange_goods,array("refundid"=>$refund["id"]));
			
			m('notice')->sendOrderMessage($item['id'], true);
			
		}
		 else if ($refundstatus == 10) {
		     ////换货--关闭
			$refund_data['status'] = 1;
			$refund_data['refundtime'] = $time;
			pdo_update('ewei_shop_order_refund', $refund_data, array('id' => $refund["id"], 'uniacid' => $uniacid));
// 			$order_data = array();
// 			$order_data['refundstate'] = 0;
// 			$order_data['status'] = 3;
// 			$order_data['refundtime'] = $time;
// 			pdo_update('ewei_shop_order', $order_data, array('id' => $item['id'], 'uniacid' => $uniacid));
           
            pdo_update("ewei_shop_order_goods",array("refundstatus"=>1),array("refundid"=>$refund["id"]));
            pdo_update("ewei_shop_order",array("refundstatus"=>1,"status"=>3),array("refundid"=>$refund["id"]));
			m('notice')->sendOrderMessage($item['id'], true);
		}
		 else if ($refundstatus == 1) {
			if (0 < $item['parentid']) {
				$parent_item = pdo_fetch('SELECT id,ordersn,ordersn2,price FROM ' . tablename('ewei_shop_order') . ' WHERE id = :id and uniacid=:uniacid Limit 1', array(':id' => $item['parentid'], ':uniacid' => $_W['uniacid']));

				if (empty($parent_item)) {
					show_json(0, '未找到退款订单!');
				}


				$order_price = $parent_item['price'];
				$ordersn = $parent_item['ordersn'];

				if (!(empty($parent_item['ordersn2']))) {
					$var = sprintf('%02d', $parent_item['ordersn2']);
					$ordersn .= 'GJ' . $var;
				}

			}
			 else {
				$order_price = $item['price'];
				$ordersn = $item['ordersn'];

				if (!(empty($item['ordersn2']))) {
					$var = sprintf('%02d', $item['ordersn2']);
					$ordersn .= 'GJ' . $var;
				}

			}

			$realprice = $refund['applyprice'];
			$goods = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $item['id'], ':uniacid' => $uniacid));
			$refundtype = 0;

			if (empty($item['transid']) && ($item['paytype'] == 22) && empty($item['apppay'])) {
				$item['paytype'] = 23;
			}


			if ($item['paytype'] == 1) {
				
				//余额支付
				if ($refund["goods_id"]){
				    m('member')->setCredit($member["id"], 'credit2', $realprice, array(0, $shopset['name'] . '部分商品退款: ' . $realprice . '元 订单号: ' . $item['ordersn']));
				    // 					var_dump("11");die;
				    
				}else{
				    m('member')->setCredit($member["id"], 'credit2', $realprice, array(0, $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $item['ordersn']));
				    
				}
				$result = true;
				
			}
			 else if ($item['paytype'] == 21) {
				if (empty($item['apppay'])) {
					$realprice = round($realprice - $item['deductcredit2'], 2);

					if (0 < $realprice) {
						if (empty($item['isborrow'])) {
							$result = m('finance')->refund($item['openid'], $ordersn, $refund['refundno'], $order_price * 100, $realprice * 100, (!(empty($item['apppay'])) ? true : false));
						}
						 else {
							$result = m('finance')->refundBorrow($item['borrowopenid'], $ordersn, $refund['refundno'], $order_price * 100, $realprice * 100, (!(empty($item['ordersn2'])) ? 1 : 0));
						}
					}

				}
				 else if ($item['apppay'] == 2) {
					$result = m('finance')->wxapp_refund($item['openid'], $ordersn, $refund['refundno'], $order_price * 100, $realprice * 100, (!(empty($item['apppay'])) ? true : false));
				}


				$refundtype = 2;
			}
			 else if ($item['paytype'] == 22) {
				$sec = m('common')->getSec();
				$sec = iunserializer($sec['sec']);

				if (!(empty($item['apppay']))) {
					if (empty($sec['app_alipay']['private_key']) || empty($sec['app_alipay']['appid'])) {
						show_json(0, '支付参数错误，私钥为空或者APPID为空!');
					}


					$params = array('out_trade_no' => $ordersn, 'refund_amount' => $realprice, 'refund_reason' => $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $item['ordersn']);
					$config = array('app_id' => $sec['app_alipay']['appid'], 'privatekey' => $sec['app_alipay']['private_key'], 'publickey' => '', 'alipublickey' => '');
					$result = m('finance')->newAlipayRefund($params, $config);
				}
				 else {
					if (empty($item['transid'])) {
						show_json(0, '仅支持 升级后此功能后退款的订单!');
					}


					$setting = uni_setting($_W['uniacid'], array('payment'));

					if (!(is_array($setting['payment']))) {
						return error(1, '没有设定支付参数');
					}


					$alipay_config = $setting['payment']['alipay'];
					$batch_no_money = $realprice * 100;
					$batch_no = date('Ymd') . 'RF' . $item['id'] . 'MONEY' . $batch_no_money;
					$res = m('finance')->AlipayRefund(array('trade_no' => $item['transid'], 'refund_price' => $realprice, 'refund_reason' => $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $item['ordersn']), $batch_no, $alipay_config);

					if (is_error($res)) {
						show_json(0, $res['message']);
					}


					show_json(1, array('url' => $res));
				}
			}
			 else {
				if ($realprice < 1) {
					show_json(0, '退款金额必须大于1元，才能使用微信企业付款退款!');
				}


				$realprice = round($realprice - $item['deductcredit2'], 2);

				if (0 < $realprice) {
					$result = m('finance')->pay($item['openid'], 1, $realprice * 100, $refund['refundno'], $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $item['ordersn']);
				}


				$refundtype = 1;
			}

			if (is_error($result)) {
				show_json(0, $result['message']);
			}


			$credits = m('order')->getGoodsCredit($goods);

			if (0 < $credits) {
			}


// 			if (0 < $item['deductcredit']) {
// 				m('member')->setCredit($item['openid'], 'credit1', $item['deductcredit'], array('0', $shopset['name'] . '购物返还抵扣卡路里 卡路里: ' . $item['deductcredit'] . ' 抵扣金额: ' . $item['deductprice'] . ' 订单号: ' . $item['ordersn']));
// 			}
            
			//用户消费卡路里修改
			if ($refund["goods_id"]){
			    //部分商品
			    if ($gg["deductprice"]>0){
			        m('member')->setCredit($member["id"], 'credit1', $gg["deductprice"], array(0, $shopset['name'] . '购物（部分商品）返还抵扣卡路里 卡路里' . $item["deductprice"] . '订单号: ' . $item['ordersn']));
			    }
			}else{
			    //全部
			    if ($item["deductprice"]>0){
			        m('member')->setCredit($member["id"], 'credit1', $item["deductprice"], array(0, $shopset['name'] . '购物返还抵扣卡路里 卡路里' . $item["deductprice"] . '订单号: ' . $item['ordersn']));
			    }
			}
			
			//折扣宝修改
			if ($refund["goods_id"]){
			    //部分
			    if ($gg["discount_price"]>0){
			        m('member')->setCredit($member["id"], 'credit3', $gg["discount_price"], array(0, $shopset['name'] . '购物(部分)返还抵扣折扣宝 折扣宝' . $order["discount_price"] . '订单号: ' . $item['ordersn']));
			    }
			}else{
			    
			    if ($order["discount_price"]>0){
			        m('member')->setCredit($member["id"], 'credit3', $order["discount_price"], array(0, $shopset['name'] . '购物返还抵扣折扣宝 折扣宝' . $order["discount_price"] . '订单号: ' . $item['ordersn']));
			    }
			    
			}
			

			if (!(empty($refundtype))) {
				if ($realprice < 0) {
					$item['deductcredit2'] = $refund['applyprice'];
				}


				m('order')->setDeductCredit2($item);
			}


			$change_refund['reply'] = '';
			$change_refund['status'] = 1;
			$change_refund['refundtype'] = $refundtype;
			$change_refund['price'] = $realprice;
			$change_refund['refundtime'] = $time;

			if (empty($refund['operatetime'])) {
				$change_refund['operatetime'] = $time;
			}


			pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $refund['id']));
			m('order')->setGiveBalance($item['id'], 2);
			m('order')->setStocksAndCredits($item['id'], 2);

			if ($refund['orderprice'] == $refund['applyprice']) {
				if (com('coupon') && !(empty($item['couponid']))) {
					com('coupon')->returnConsumeCoupon($item['id']);
				}

			}

			//订单更改
			if ($refund["goods_id"]){
			    //订单商品处理
			    if ($item["status"]==1){
			        //待发货状态
			        pdo_query("update ".tablename("ewei_shop_order_goods")." set status=-1,refundstatus=1 "." where refundid=:refundid",array(":refundid"=>$refund["id"]));
			        //更新订单
			        $d["goodsprice"]=$item["goodsprice"]-$gg["price"];
			        $d["deductprice"]=$item["deductprice"]-$gg["deductprice"];
			        $d["discount_price"]=$item["discount_price"]-$gg["discount_price"];
			        $d["dispatchprice"]=$item["dispatchprice"]-$gg["dispatchprice"];
			        $d["deductenough"]=$item["deductenough"]-$gg["deductenough"];
			        $d["couponprice"]=$item["couponprice"]-$gg["couponprice"];
			        $d["price"]=$item["price"]-($gg["price"]-$gg["deductprice"]-$gg["discount_price"]-$gg["deductenough"]-$gg["couponprice"]+$gg["dispatchprice"]);
			        pdo_update("ewei_shop_order",$d,array("id"=>$item["id"]));
			    }else{
			        pdo_query("update ".tablename("ewei_shop_order_goods")." set  refundstatus=1 "." where refundid=:refundid",array(":refundid"=>$refund["id"]));
			        
			    }
			    
			}else{
			    
			    pdo_update('ewei_shop_order', array('status' => -1,'refundstatus'=>1, 'refundtime' => $time), array('id' => $item['id'], 'uniacid' => $uniacid));
			    pdo_update("ewei_shop_order_goods",array("refundstatus"=>1),array("orderid"=>$item["id"],"refundid"=>$refund["id"]));
			}
			
			foreach ($goods as $g ) {
				$salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['id'], ':uniacid' => $uniacid));
				pdo_update('ewei_shop_goods', array('salesreal' => $salesreal), array('id' => $g['id']));
			}

			$log = '订单退款 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn'];

			if (0 < $item['parentid']) {
				$log .= ' 父订单号:' . $ordersn;
			}


			plog('order.op.refund.submit', $log);
			m('notice')->sendOrderMessage($item['id'], true);
			if ($order["share_id"]!=0&&$order["share_price"]!=0){
			    //订单赏金
			    $share_member=pdo_get("ewei_shop_member",array("id"=>$order["share_id"]));
			    pdo_update("ewei_shop_member",array('frozen_credit2'=>$share_member["frozen_credit2"]-$order["share_price"]),array('id'=>$order["share_id"]));
			    pdo_update("ewei_shop_member_credit2",array('frozen'=>-1),array("orderid"=>$item['id']));
			}
			
		}
		 else if ($refundstatus == -1) {
			pdo_update('ewei_shop_order_refund', array('reply' => $refundcontent, 'status' => -1, 'endtime' => $time), array('id' => $refund["id"]));
			plog('order.op.refund.submit', '订单退款拒绝 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn'] . ' 原因: ' . $refundcontent);
		      
			//修改订单状态
			pdo_update("ewei_shop_order",array("refundstatus"=>-1),array("refundid"=>$refund["id"]));
			pdo_update("ewei_shop_order_goods",array("refundstatus"=>-1),array("refundid"=>$refund["id"]));
			// 				pdo_update('ewei_shop_order', array('refundstate' => 0), array('id' => $item['id'], 'uniacid' => $uniacid));
			
			m('notice')->sendOrderMessage($item['id'], true);
		}
		else if ($refundstatus == 2) {
		    //手动退款
		    
		    $refundtype = 2;
		    $change_refund['reply'] = '';
		    $change_refund['status'] = 1;
		    $change_refund['refundtype'] = $refundtype;
		    $change_refund['price'] = $refund['applyprice'];
		    $change_refund['refundtime'] = $time;
		    if (empty($refund['operatetime']))
		    {
		        $change_refund['operatetime'] = $time;
		    }
		    
		    pdo_update('ewei_shop_order_refund', $change_refund, array('id' =>$refund["id"]));
		    
		    if (empty($refund["goods_id"])){
		        //订单全部退款
		        m('order')->setGiveBalance($item['id'], 2);
		        m('order')->setStocksAndCredits($item['id'], 2);
		        if ($refund['orderprice'] == $refund['applyprice'])
		        {
		            if (com('coupon') && !(empty($item['couponid'])))
		            {
		                com('coupon')->returnConsumeCoupon($item['id']);
		            }
		        }
		        pdo_update('ewei_shop_order', array('refundstatus' => 1, 'status' => -1, 'refundtime' => $time), array('id' => $item['id'], 'uniacid' => $uniacid));
		        $goods = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.uniacid=:uniacid', array(':orderid' => $item['id'], ':uniacid' => $uniacid));
		        $credits = m('order')->getGoodsCredit($goods);
		        plog('order.op.refund.submit', '订单退款 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn'] . ' 手动退款!');
		        if ($item['status'] == 3)
		        {
		            if (0 < $credits)
		            {
		                m('member')->setCredit($item['openid'], 'credit1', -$credits, array(0, $shopset['name'] . '退款扣除购物赠送卡路里: ' . $credits . ' 订单号: ' . $item['ordersn']));
		            }
		        }
		        foreach ($goods as $g )
		        {
		            $salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['id'], ':uniacid' => $uniacid));
		            pdo_update('ewei_shop_goods', array('salesreal' => $salesreal), array('id' => $g['id']));
		        }
		        m('notice')->sendOrderMessage($item['id'], true);
		        
		        if ($order["share_id"]!=0&&$order["share_price"]!=0){
		            //订单赏金
		            $share_member=pdo_get("ewei_shop_member",array("id"=>$order["share_id"]));
		            pdo_update("ewei_shop_member",array('frozen_credit2'=>$share_member["frozen_credit2"]-$order["share_price"]),array('id'=>$order["share_id"]));
		            pdo_update("ewei_shop_member_credit2",array('frozen'=>-1),array("orderid"=>$item['id']));
		        }
		    }else{
		        //订单部分退款
		        $order_goodsid=implode(",", $gid);
		        m('order')->setGiveBalance($item['id'], 2,$order_goodsid);//余额返现
		        m('order')->setStocksAndCredits($item['id'], 2,$order_goodsid);
		        
		        // 				    pdo_update('ewei_shop_order', array('refundstate' => 0, 'status' => -1, 'refundtime' => $time), array('id' => $item['id'], 'uniacid' => $uniacid));
		        
		        
		        $goods = pdo_fetchall('SELECT g.id,g.credit, o.total,o.realprice FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.id in(:id) and o.uniacid=:uniacid', array(':orderid' => $item['id'], ':uniacid' => $uniacid,':id'=>$order_goodsid));
		        $gg = pdo_fetch('SELECT sum(o.price) as price,sum(o.dispatchprice) as dispatchprice,sum(o.deductprice) as deductprice,sum(o.discount_price) as discount_price,sum(o.couponprice) as couponprice,sum(o.deductenough) as deductenough FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.orderid=:orderid and o.id in(:id) and o.uniacid=:uniacid', array(':orderid' => $item['id'], ':uniacid' => $uniacid,':id'=>$order_goodsid));
		        
		        // 				    var_dump($order_goodsid);var_dump($goods);die;
		        //订单商品处理
		        if ($item["status"]==1){
		            //待发货状态
		            pdo_query("update ".tablename("ewei_shop_order_goods")." set status=-1,refundstatus=1 "." where refundid=:refundid",array(":refundid"=>$refund["id"]));
		            //更新订单
		            $d["goodsprice"]=$item["goodsprice"]-$gg["price"];
		            $d["deductprice"]=$item["deductprice"]-$gg["deductprice"];
		            $d["discount_price"]=$item["discount_price"]-$gg["discount_price"];
		            $d["dispatchprice"]=$item["dispatchprice"]-$gg["dispatchprice"];
		            $d["deductenough"]=$item["deductenough"]-$gg["deductenough"];
		            $d["couponprice"]=$item["couponprice"]-$gg["couponprice"];
		            $d["price"]=$item["price"]-($gg["price"]-$gg["deductprice"]-$gg["discount_price"]-$gg["deductenough"]-$gg["couponprice"]+$gg["dispatchprice"]);
		            pdo_update("ewei_shop_order",$d,array("id"=>$item["id"]));
		        }else{
		            pdo_query("update ".tablename("ewei_shop_order_goods")." set  refundstatus=1 "." where refundid=:refundid",array(":refundid"=>$refund["id"]));
		            
		        }
		        // 				    var_dump($goods);var_dump($d);die;
		        $credits = m('order')->getGoodsCredit($goods);
		        plog('order.op.refund.submit', '订单退款 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn'] . ' 手动退款!');
		        if ($item['status'] == 3)
		        {
		            if (0 < $credits)
		            {
		                m('member')->setCredit($member["id"], 'credit1', -$credits, array(0, $shopset['name'] . '退款扣除购物赠送卡路里: ' . $credits . ' 订单号: ' . $item['ordersn']));
		            }
		        }
		        foreach ($goods as $g )
		        {
		            $salesreal = pdo_fetchcolumn('select ifnull(sum(total),0) from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid ' . ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1', array(':goodsid' => $g['id'], ':uniacid' => $uniacid));
		            pdo_update('ewei_shop_goods', array('salesreal' => $salesreal), array('id' => $g['id']));
		            
		        }
		        
		        m('notice')->sendOrderMessage($item['id'], true);
		        
		        if ($order["share_id"]!=0&&$order["share_price"]!=0){
		            //订单赏金
		            $share_member=pdo_get("ewei_shop_member",array("id"=>$order["share_id"]));
		            pdo_update("ewei_shop_member",array('frozen_credit2'=>$share_member["frozen_credit2"]-$order["share_price"]),array('id'=>$order["share_id"]));
		            pdo_update("ewei_shop_member_credit2",array('frozen'=>-1),array("orderid"=>$item['id']));
		        }
		        
		        
		        
		    }
		    
		}


		show_json(1);
	}

	public function main()
	{
		global $_W;
		global $_GPC;
		global $_S;

		if (!(cv('order.op.refund'))) {
			$this->message('您没有维权处理权限');
		}


		$opdata = $this->opData();
		extract($opdata);

		if ($_W['ispost']) {
			if (!(cv('order.op.refund.submit'))) {
				$this->message('您没有维权处理权限');
			}


			$this->submit();
		}


		$step_array = array();
		$step_array[1]['step'] = 1;
		$step_array[1]['title'] = '客户申请维权';
		$step_array[1]['time'] = $refund['createtime'];
		$step_array[1]['done'] = 1;
		$step_array[2]['step'] = 2;
		$step_array[2]['title'] = '商家处理维权申请';
		$step_array[2]['done'] = 1;
		$step_array[3]['step'] = 3;
		$step_array[3]['done'] = 0;

		if (0 <= $refund['status']) {
			if ($refund['rtype'] == 0) {
				$step_array[3]['title'] = '退款完成';
			}
			 else if ($refund['rtype'] == 1) {
				$step_array[3]['title'] = '客户退回物品';
				$step_array[4]['step'] = 4;
				$step_array[4]['title'] = '退款退货完成';
			}
			 else if ($refund['rtype'] == 2) {
				$step_array[3]['title'] = '客户退回物品';
				$step_array[4]['step'] = 4;
				$step_array[4]['title'] = '商家重新发货';
				$step_array[5]['step'] = 5;
				$step_array[5]['title'] = '换货完成';
			}


			if ($refund['status'] == 0) {
				$step_array[2]['done'] = 0;
				$step_array[3]['done'] = 0;
			}


			if ($refund['rtype'] == 0) {
				if (0 < $refund['status']) {
					$step_array[2]['time'] = $refund['refundtime'];
					$step_array[3]['done'] = 1;
					$step_array[3]['time'] = $refund['refundtime'];
				}

			}
			 else {
				$step_array[2]['time'] = $refund['operatetime'];
				if (($refund['status'] == 1) || (4 <= $refund['status'])) {
					$step_array[3]['done'] = 1;
					$step_array[3]['time'] = $refund['sendtime'];
				}


				if (($refund['status'] == 1) || ($refund['status'] == 5)) {
					$step_array[4]['done'] = 1;

					if ($refund['rtype'] == 1) {
						$step_array[4]['time'] = $refund['refundtime'];
					}
					 else if ($refund['rtype'] == 2) {
						$step_array[4]['time'] = $refund['returntime'];

						if ($refund['status'] == 1) {
							$step_array[5]['done'] = 1;
							$step_array[5]['time'] = $refund['refundtime'];
						}

					}

				}

			}
		}
		 else if ($refund['status'] == -1) {
			$step_array[2]['done'] = 1;
			$step_array[2]['time'] = $refund['endtime'];
			$step_array[3]['done'] = 1;
			$step_array[3]['title'] = '拒绝' . $r_type[$refund['rtype']];
			$step_array[3]['time'] = $refund['endtime'];
		}
		 else if ($refund['status'] == -2) {
			if (!(empty($refund['operatetime']))) {
				$step_array[2]['done'] = 1;
				$step_array[2]['time'] = $refund['operatetime'];
			}


			$step_array[3]['done'] = 1;
			$step_array[3]['title'] = '客户取消' . $r_type[$refund['rtype']];
			$step_array[3]['time'] = $refund['refundtime'];
		}

		//获取商品
		if ($refund["goods_id"]){
		    $gid=unserialize($refund["goods_id"]);
		}
		$goods = pdo_fetchall('SELECT g.*,o.id as order_goodsid, o.goodssn as option_goodssn, o.productsn as option_productsn,o.total,g.type,o.optionname,o.optionid,o.price as orderprice,o.realprice,o.changeprice,o.oldprice,o.commission1,o.commission2,o.commission3,o.commissions ' . $diyformfields . ' FROM ' . tablename('ewei_shop_order_goods') . ' o left join ' . tablename('ewei_shop_goods') . ' g on o.goodsid=g.id ' . ' WHERE o.refundid=:refundid and o.uniacid=:uniacid ', array(':refundid' => $refund["id"], ':uniacid' => $_W['uniacid']));

		foreach ($goods as &$r ) {
			if (!(empty($r['option_goodssn']))) {
				$r['goodssn'] = $r['option_goodssn'];
			}


			if (!(empty($r['option_productsn']))) {
				$r['productsn'] = $r['option_productsn'];
			}


			if (p('diyform')) {
				$r['diyformfields'] = iunserializer($r['diyformfields']);
				$r['diyformdata'] = iunserializer($r['diyformdata']);
			}

		}
         
		unset($r);
		$item['goods'] = $goods;
		$member = m('member')->getMember($item['openid']);
		$express_list = m('express')->getExpressList();
		$refund_address = pdo_fetchall('select * from ' . tablename('ewei_shop_refund_address') . ' where uniacid=:uniacid and merchid=0', array(':uniacid' => $_W['uniacid']));
		include $this->template();
	}
}


?>