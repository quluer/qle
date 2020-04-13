<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Op_EweiShopV2Page extends AppMobilePage
{
	/**
     * 取消订单
     * @global type $_W
     * @global type $_GPC
     */
	public function cancel()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);

		if (empty($orderid)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单id不正确");
		    }else{
			app_error(AppError::$ParamsError);
		    }
		}
		//修改
        $openid=$_GPC["openid"];
        if ($_GPC["type"]==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }  
        $member=m("member")->getMember($openid);
        
		$order = pdo_fetch('select id,ordersn,openid,user_id,status,deductcredit,deductcredit2,deductprice,discount_price,couponid,`virtual`,`virtual_info`,merchid  from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid  limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));

		if (empty($order)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单不存在");
		    }else{
			app_error(AppError::$OrderNotFound);
		    }
		}
        if ($order["openid"]!=$member["openid"]&&$order["user_id"]!=$member["id"]){
            if ($_GPC["type"]==1){
                apperror(1,"无权限");
            }else{
            app_error(AppError::$OrderNotFound);
            }
            
        }
		if (0 < $order['status']) {
		    if ($_GPC["type"]==1){
		        apperror(1,"该订单不可取消");
		    }else{
			app_error(AppError::$OrderCannotCancel);
		    }
		}

		if ($order['status'] < 0) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单不可取消");
		    }else{
			app_error(AppError::$OrderCannotCancel);
		    }
		}

		if (!empty($order['virtual']) && $order['virtual'] != 0) {
			$goodsid = pdo_fetch('SELECT goodsid FROM ' . tablename('ewei_shop_order_goods') . ' WHERE uniacid = ' . $_W['uniacid'] . ' AND orderid = ' . $order['id']);
			$typeid = $order['virtual'];
			$vkdata = ltrim($order['virtual_info'], '[');
			$vkdata = rtrim($vkdata, ']');
			$arr = explode('}', $vkdata);

			foreach ($arr as $k => $v) {
				if (!$v) {
					unset($arr[$k]);
				}     
			}

			$vkeynum = count($arr);
			pdo_query('update ' . tablename('ewei_shop_virtual_data') . ' set openid="",usetime=0,orderid=0,ordersn="",price=0,merchid=' . $order['merchid'] . ' where typeid=' . intval($typeid) . ' and orderid = ' . $order['id']);
			pdo_query('update ' . tablename('ewei_shop_virtual_type') . ' set usedata=usedata-' . $vkeynum . ' where id=' . intval($typeid));
		}

		m('order')->setStocksAndCredits($orderid, 2);

		if (0 < $order['deductprice']) {
			m('member')->setCredit($member["id"], 'credit1', $order['deductcredit'], array('0', $_W['shopset']['shop']['name'] . ('购物返还抵扣卡路里 卡路里: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn'])));
		}
          //折扣宝
		if (0 < $order['discount_price']) {
		    m('member')->setCredit($member["id"], 'credit3', $order['discount_price'], array('0', $_W['shopset']['shop']['name'] . ('购物返还抵扣折扣宝 折扣宝: ' . $order['discount_price'] . ' 抵扣金额: ' . $order['discount_price'] . ' 订单号: ' . $order['ordersn'])));
		}
		
		m('order')->setDeductCredit2($order);
		if (com('coupon') && !empty($order['couponid'])) {
			com('coupon')->returnConsumeCoupon($orderid);
		}

		pdo_update('ewei_shop_order', array('status' => -1, 'canceltime' => time(), 'closereason' => trim($_GPC['remark'])), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		pdo_update('ewei_shop_gift_log',['status'=>0],['order_sn'=>$order['ordersn']]);
		if ($_GPC["type"]!=1){
		    //小程序消息
		m('notice')->sendOrderMessage($orderid);
		}else{
		    //app
		    
		}
		if ($_GPC["type"]==1){
		    apperror(0,"成功");
		}else{
		app_json();
		}
	}

	/**
     * 确认收货
     * @global type $_W
     * @global type $_GPC
     */
	public function finish()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);

		if (empty($orderid)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单id未传");
		    }else{
			app_error(AppError::$ParamsError);
		    }
		}
        $openid=$_GPC["openid"];
        if ($_GPC["type"]==1){
        $member_id=m('member')->getLoginToken($openid);
        if ($member_id==0){
            apperror(1,"无此用户");
        }
        $openid=$member_id;
        }
        
        $member=m("member")->getMember($openid);
		$order = pdo_fetch('select id,ordersn,status,price,merchid,openid,user_id,couponid,refundstate,refundid,share_price,share_id from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and (openid=:openid or user_id=:user_id) limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid'],':openid'=>$member["openid"],':user_id'=>$member["id"]));
        
		if (empty($order)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单不存在");
		    }else{
			app_error(AppError::$OrderNotFound);
		    }
		}

		if ($order['status'] != 2) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单不可操作");
		    }else{
			app_error(AppError::$OrderCannotFinish);
		    }
		}
         //获取订单商品
        $good=pdo_fetchall("select * from ".tablename("ewei_shop_order_goods")." where orderid=:orderid and refundstatus!=1",array(":orderid"=>$order["id"]));
		if (0 < $order['refundstate'] && !empty($order['refundid']&&$order["refundstatus"]!=1)) {
			$change_refund = array();
			$change_refund['status'] = -2;
			$change_refund['refundtime'] = time();
			pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $order['refundid'], 'uniacid' => $_W['uniacid']));
		}
		foreach ($good as $k=>$v){
		    //删除申请中的售后
		   if ($v["refundid"]){
		       pdo_delete("ewei_shop_order_refund",array("id"=>$v["refundid"]));
		    //更新商品申请的售后
		    $d["refundid"]=0;
		    $d["rstate"]=0;
		    pdo_update("ewei_shop_order_goods",$d,array("id"=>$v["id"]));
		   }
		}
        //订单赏金
        if ($order["share_price"]!=0){
            //更新用户赏金
            $share_member=pdo_get("ewei_shop_member",array('id'=>$order["share_id"]));
            if (!empty($share_member)){
                //用户佣金
                m('member')->setCredit($share_member["id"], 'credit2', $order["share_price"],"赏金任务佣金，用户确认收货");
                pdo_update("ewei_shop_member",array('frozen_credit2'=>$share_member["frozen_credit2"]-$order["share_price"]),array('id'=>$order["share_id"]));
                //更新记录
                pdo_update("ewei_shop_member_credit2",array("frozen"=>1),array('orderid'=>$orderid));
            }
        }
        //折扣宝订单  花多少钱给多少钱
        $order_goods = pdo_fetchall('select id,orderid,goodsid from '.tablename('ewei_shop_order_goods').'where orderid = :orderid',[':orderid'=>$orderid]);
		$zhekoubao = 0;
		//如果该订单内  有折扣包商品  折扣金额为0  那么奖励该商品的价格数给用户的折扣宝
		foreach ($order_goods as $item){
           $good = pdo_get('ewei_shop_goods',['id'=>$item['goodsid']]);
           if($good['deduct_type'] == 2 && $good['deduct'] == 0 && $good['merchid'] == 0){
               $zhekoubao = $zhekoubao + $good['marketprice'];
		   }
		}
		if($zhekoubao > 0){
			m('member')->setCredit($member["id"],'credit3',$zhekoubao,"购买折扣宝商品奖励折扣宝");
		}
		pdo_update('ewei_shop_order', array('status' => 3, 'finishtime' => time(), 'refundstate' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		m('order')->setStocksAndCredits($orderid, 3);
// 		m('member')->upgradeLevel($order['openid'], $orderid);
		m('member')->upgradeLevel($member["id"], $orderid);
		m('order')->setGiveBalance($orderid, 1);

		if (com('coupon')) {
			com('coupon')->sendcouponsbytask($orderid);
		}

		if (com('coupon') && !empty($order['couponid'])) {
			com('coupon')->backConsumeCoupon($orderid);
		}

		m('notice')->sendOrderMessage($orderid);
        //商家消息
        if ($order["merchid"]!=0){
            $merch=pdo_fetch("select * from ".tablename("ewei_shop_merch_user")." where id=:id",array(':id'=>$order["merchid"]));
            if (!empty($merch)&&!empty($merch["wxopenid"])){
                $postdata=array(
                    'keyword1'=>array(
                        'value'=>$order["price"],
                        'color' => '#ff510'
                    ),
                    'keyword2'=>array(
                        'value'=>"编号为：".$order["ordersn"]."的订单，已被用户确认收货",
                        'color' => '#ff510'
                    ),
                    'keyword3'=>array(
                        'value'=>date("Y-m-d",time()),
                        'color' => '#ff510'
                    ),
                    'keyword4'=>array(
                        'value'=>"订单被确认收货，请到商家中心查看",
                        'color' => '#ff510'
                    )
                    
                );
                p("app")->mysendNotice($merch["wxopenid"], $postdata, "", "nSJSBKVYwLYN_LcsUXyvTLVjseO46nQA8RqKsRnsiRs");
            }
        }
		if (p('commission')) {
			p('commission')->checkOrderFinish($orderid);
		}
		if ($_GPC["type"]==1){
		    apperror(0,"成功");
		}else{
		app_json();
		}
	}

	/**
     * 删除或恢复订单
     * @global type $_W
     * @global type $_GPC
     */
	public function delete()
	{
		global $_W;
		global $_GPC;
		$orderid = intval($_GPC['id']);
		$userdeleted = intval($_GPC['userdeleted']);
        $type=$_GPC["type"]; 
		if (empty($orderid)) {
		    if ($type==1){
		        apperror(1,"orderid不正确");
		    }else{
			app_error(AppError::$ParamsError);
		    }
		}

		$order = pdo_fetch('select id,status,refundstate,refundid from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid  limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));

		if (empty($order)) {
		    if ($type==1){
		        apperror(1,"订单不存在");
		    }else{
			app_error(AppError::$OrderNotFound);
		    }
		}

		if ($userdeleted == 0) {
			if ($order['status'] != 3) {
			    if ($type==1){
			        apperror(1,"该订单不可删除");
			    }else{
				app_error(AppError::$OrderCannotRestore);
			    }
			}
		}
		else {
			if ($order['status'] != 3 && $order['status'] != -1) {
				if ($type==1){
				    apperror(1,"该订单不可删除");
				}else{
			    app_error(AppError::$OrderCannotDelete);
				}
			}

			if (0 < $order['refundstate'] && !empty($order['refundid'])) {
				$change_refund = array();
				$change_refund['status'] = -2;
				$change_refund['refundtime'] = time();
				pdo_update('ewei_shop_order_refund', $change_refund, array('id' => $order['refundid'], 'uniacid' => $_W['uniacid']));
			}
		}

		pdo_update('ewei_shop_order', array('userdeleted' => $userdeleted, 'refundstate' => 0), array('id' => $order['id'], 'uniacid' => $_W['uniacid']));
		if ($type==1){
		    apperror(0,"删除成功");
		}else{
		app_json();
		}
	}
}

?>
