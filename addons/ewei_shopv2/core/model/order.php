<?php  if( !defined("IN_IA") ) 
{
	exit( "Access Denied" );
}
class Order_EweiShopV2Model 
{
	public function fullback($orderid) 
	{
		global $_W;
		$uniacid = $_W["uniacid"];
		$order_goods = pdo_fetchall("select o.openid,og.optionid,og.goodsid,og.price,og.total from " . tablename("ewei_shop_order_goods") . " as og\r\n                    left join " . tablename("ewei_shop_order") . " as o on og.orderid = o.id\r\n                    where og.uniacid = " . $uniacid . " and og.orderid = " . $orderid . " ");
		foreach( $order_goods as $key => $value ) 
		{
			if( 0 < $value["optionid"] ) 
			{
				$goods = pdo_fetch("select g.hasoption,g.id,go.goodsid,go.isfullback from " . tablename("ewei_shop_goods") . " as g\r\n                left join " . tablename("ewei_shop_goods_option") . " as go on go.goodsid = :id and go.id = " . $value["optionid"] . "\r\n                 where g.id=:id and g.uniacid=:uniacid limit 1", array( ":id" => $value["goodsid"], ":uniacid" => $uniacid ));
			}
			else 
			{
				$goods = pdo_fetch("select * from " . tablename("ewei_shop_goods") . " where id=:id and uniacid=:uniacid limit 1", array( ":id" => $value["goodsid"], ":uniacid" => $uniacid ));
			}
			if( 0 < $goods["isfullback"] ) 
			{
				$fullbackgoods = pdo_fetch("SELECT id,minallfullbackallprice,maxallfullbackallprice,minallfullbackallratio,maxallfullbackallratio,`day`,\r\n                          fullbackprice,fullbackratio,status,hasoption,marketprice,`type`,startday\r\n                          FROM " . tablename("ewei_shop_fullback_goods") . " WHERE uniacid = " . $uniacid . " and goodsid = " . $value["goodsid"] . " limit 1");
				if( !empty($fullbackgoods) && $goods["hasoption"] && 0 < $value["optionid"] ) 
				{
					$option = pdo_fetch("select id,title,allfullbackprice,allfullbackratio,fullbackprice,fullbackratio,`day` from " . tablename("ewei_shop_goods_option") . " \r\n                        where id=:id and goodsid=:goodsid and uniacid=:uniacid and isfullback = 1 limit 1", array( ":uniacid" => $uniacid, ":goodsid" => $value["goodsid"], ":id" => $value["optionid"] ));
					if( !empty($option) ) 
					{
						$fullbackgoods["minallfullbackallprice"] = $option["allfullbackprice"];
						$fullbackgoods["minallfullbackallratio"] = $option["allfullbackratio"];
						$fullbackgoods["fullbackprice"] = $option["fullbackprice"];
						$fullbackgoods["fullbackratio"] = $option["fullbackratio"];
						$fullbackgoods["day"] = $option["day"];
					}
				}
				$fullbackgoods["startday"] = $fullbackgoods["startday"] - 1;
				if( !empty($fullbackgoods) ) 
				{
					$data = array( "uniacid" => $uniacid, "orderid" => $orderid, "openid" => $value["openid"], "day" => $fullbackgoods["day"], "fullbacktime" => strtotime("+" . $fullbackgoods["startday"] . " days"), "goodsid" => $value["goodsid"], "createtime" => time() );
					if( 0 < $fullbackgoods["type"] ) 
					{
						$data["price"] = ($value["price"] * $fullbackgoods["minallfullbackallratio"]) / 100;
						$data["priceevery"] = ($value["price"] * $fullbackgoods["fullbackratio"]) / 100;
					}
					else 
					{
						$data["price"] = $value["total"] * $fullbackgoods["minallfullbackallprice"];
						$data["priceevery"] = $value["total"] * $fullbackgoods["fullbackprice"];
					}
					pdo_insert("ewei_shop_fullback_log", $data);
				}
			}
		}
	}
	public function fullbackstop($orderid) 
	{
		global $_W;
		global $_S;
		$uniacid = $_W["uniacid"];
		$shopset = $_S["shop"];
		$fullback_log = pdo_fetch("select * from " . tablename("ewei_shop_fullback_log") . " where uniacid = " . $uniacid . " and orderid = " . $orderid . " ");
		pdo_update("ewei_shop_fullback_log", array( "isfullback" => 1 ), array( "id" => $fullback_log["id"], "uniacid" => $uniacid ));
	}
	public function payResult($params) 
	{
		global $_W;
		$fee = intval($params["fee"]);
		$data = array( "status" => ($params["result"] == "success" ? 1 : 0) );
		$ordersn_tid = $params["tid"];
		$ordersn = rtrim($ordersn_tid, "TR");
		$order = pdo_fetch("select id,uniacid,ordersn, price,goodsprice,openid,dispatchtype,addressid,carrier,status,isverify,deductcredit2,`virtual`,isvirtual,couponid,isvirtualsend,isparent,paytype,merchid,agentid,createtime,buyagainprice,istrade,tradestatus,iscycelbuy,share_id from " . tablename("ewei_shop_order") . " where  ordersn=:ordersn and uniacid=:uniacid limit 1", array( ":uniacid" => $_W["uniacid"], ":ordersn" => $ordersn ));
		$plugincoupon = com("coupon");
		if( $plugincoupon ) 
		{
			$plugincoupon->useConsumeCoupon($order["id"]);
		}
		if( 1 <= $order["status"] ) 
		{
			return true;
		}
		$orderid = $order["id"];
		$ispeerpay = $this->checkpeerpay($orderid);
		if( !empty($ispeerpay) )
		{
			$peerpay_info = (double) pdo_fetchcolumn("select SUM(price) price from " . tablename("ewei_shop_order_peerpay_payinfo") . " where pid=:pid limit 1", array( ":pid" => $ispeerpay["id"] ));
			if( $peerpay_info < $ispeerpay["peerpay_realprice"] ) 
			{
				return NULL;
			}
			pdo_update("ewei_shop_order", array( "status" => 0 ), array( "id" => $order["id"] ));
			$order["status"] = 0;
			pdo_update("ewei_shop_order_peerpay", array( "status" => 1 ), array( "id" => $ispeerpay["id"] ));
			$params["type"] = "peerpay";
		}
		if( $params["from"] == "return" ) 
		{
			$seckill_result = plugin_run("seckill::setOrderPay", $order["id"]);
			if( $seckill_result == "refund" ) 
			{
				return "seckill_refund";
			}
			$address = false;
			if( empty($order["dispatchtype"]) ) 
			{
				$address = pdo_fetch("select realname,mobile,address from " . tablename("ewei_shop_member_address") . " where id=:id limit 1", array( ":id" => $order["addressid"] ));
			}
			$carrier = false;
			if( $order["dispatchtype"] == 1 || $order["isvirtual"] == 1 ) 
			{
				$carrier = unserialize($order["carrier"]);
			}
			m("verifygoods")->createverifygoods($order["id"]);
			if( $params["type"] == "cash" ) 
			{
				if( $order["isparent"] == 1 ) 
				{
					$change_data = array( );
					$change_data["merchshow"] = 1;
					pdo_update("ewei_shop_order", $change_data, array( "id" => $order["id"] ));
					$this->setChildOrderPayResult($order, 0, 0);
				}
				return true;
			}
			if( $order["istrade"] == 0 ) 
			{
				if( $order["status"] == 0 )
				{
					if( !empty($order["virtual"]) && com("virtual") )
					{
						if (p('lottery') && empty($ispeerpay)) 
							{
								$res = p('lottery')->getLottery($order['openid'], 1, array('money' => $order['price'], 'paytype' => 1));
								if ($res) 
								{
									p('lottery')->getLotteryList($order['openid'], array('lottery_id' => $res));
								}
							}
						return com("virtual")->pay($order, $ispeerpay);
					}
					if( $order["isvirtualsend"] ) 
					{
						if (p('lottery') && empty($ispeerpay))
							{
								$res = p('lottery')->getLottery($order['openid'], 1, array('money' => $order['price'], 'paytype' => 1));
								if ($res) 
								{
									p('lottery')->getLotteryList($order['openid'], array('lottery_id' => $res));
								}
							}
						return $this->payVirtualSend($order["id"], $ispeerpay);
					}
					$isonlyverifygoods = $this->checkisonlyverifygoods($order["id"]);
					$time = time();
					$change_data = array( );
					if( $isonlyverifygoods ) 
					{
						$change_data["status"] = 2;
					}
					else 
					{
					    //vewen
                        //支付成功回掉，写会员支付逻辑
						$change_data["status"] = 1;
					}
					$change_data["paytime"] = $time;
					if( $order["isparent"] == 1 ) 
					{
						$change_data["merchshow"] = 1;
					}
					pdo_update("ewei_shop_order", $change_data, array( "id" => $order["id"] ));
					//判断赏金佣金
					if ($order["share_id"]!=0&&!empty($order["share_id"])&&$order["merchid"]!=0){
					    m("merch")->order($order["id"]);
					}
					
					//fbb 贡献值订单
					m("devote")->rewardorder($order["id"]);
					
					if( $order["iscycelbuy"] == 1 && p("cycelbuy") )
					{
						p("cycelbuy")->cycelbuy_periodic($order["id"]);
					}
					if( $order["isparent"] == 1 )
					{
						$this->setChildOrderPayResult($order, $time, 1);
					}
					$this->setStocksAndCredits($orderid, 1);
					if( com("coupon") ) 
					{
						com("coupon")->sendcouponsbytask($order["id"]);
						com("coupon")->backConsumeCoupon($order["id"]);
					}

					if( $order["isparent"] == 1 )
					{
						$child_list = $this->getChildOrder($order["id"]);
						foreach( $child_list as $k => $v ) 
						{
							m("notice")->sendOrderMessage($v["id"]);
						}
					}
					else 
					{
						m("notice")->sendOrderMessage($order["id"]);
						//购买成功--商家消息
						if ($order["merchid"]!=0){
						    $merch_user=pdo_fetch("select * from ".tablename("ewei_shop_merch_user")." where id=:id",array(':id'=>$order["merchid"]));
						    if (!empty($merch_user["wxopenid"])){
						        //获取商品信息
						        $goods=pdo_fetchall("select * from ".tablename("ewei_shop_order_goods")." where orderid=:orderid",array(':orderid'=>$order["id"]));
						        $goods_name="";
						        foreach ($goods as $g){
						            $good=pdo_fetch("select * from ".tablename("ewei_shop_goods")." where id=:good_id",array(':good_id'=>$g["goodsid"]));
						            if (empty($goos_name)){
						                $goods_name=$good["title"];
						            }else{
						                $goods_name=$goods_name.",".$good["title"];
						            }
						        }
						        $postdata=array(
						            'keyword1'=>array(
						                'value'=>$merch_user["merchname"],
						                'color' => '#ff510'
						            ),
						            'keyword2'=>array(
						                'value'=>$order["ordersn"],
						                'color' => '#ff510'
						            ),
						            'keyword3'=>array(
						                'value'=>$goods_name,
						                'color' => '#ff510'
						            ),
						            'keyword4'=>array(
						                'value'=>$order["goodsprice"],
						                'color' => '#ff510'
						            ),
						            'keyword5'=>array(
						                'value'=>$order["price"],
						                'color' => '#ff510'
						            ),
						            'keyword6'=>array(
						                'value'=>"用户已下单，请登录商家后台及时对订单处理",
						                'color' => '#ff510'
						            )
						            
						        );
						       p("app")->mysendNotice($merch_user["wxopenid"], $postdata, "", "si0GH6bbqNTByQrSRhxRl06CKUSKz473JrbdHwBSbts");
						    }
						}
						
					}
					if( $order["isparent"] == 1 ) 
					{
						$merchSql = "SELECT id,merchid FROM " . tablename("ewei_shop_order") . " WHERE uniacid = " . intval($order["uniacid"]) . " AND parentid = " . intval($order["id"]);
						$merchData = pdo_fetchall($merchSql);
						foreach( $merchData as $mk => $mv ) 
						{
							com_run("printer::sendOrderMessage", $mv["id"]);
						}
					}
					else 
					{
						com_run("printer::sendOrderMessage", $order["id"]);
					}
					if( p("commission") ) 
					{
						p("commission")->checkOrderPay($order["id"]);
					}
					$this->afterPayResult($order, $ispeerpay);
				}
			}
			else 
			{
				$time = time();
				$change_data = array( );
				$count_ordersn = $this->countOrdersn($ordersn_tid);
				if( $order["status"] == 0 && $count_ordersn == 1 ) 
				{
					$change_data["status"] = 1;
					$change_data["tradestatus"] = 1;
					$change_data["paytime"] = $time;
				}
				else 
				{
					if( $order["status"] == 1 && $order["tradestatus"] == 1 && $count_ordersn == 2 ) 
					{
						$change_data["tradestatus"] = 2;
						$change_data["tradepaytime"] = $time;
					}
				}
				pdo_update("ewei_shop_order", $change_data, array( "id" => $order["id"] ));
				if( $order["status"] == 0 && $count_ordersn == 1 ) 
				{
					m("notice")->sendOrderMessage($order["id"]);
				}
			}
			return true;
		}
		else 
		{
			return false;
		}
	}
	public function setChildOrderPayResult($order, $time, $type) 
	{
		global $_W;
		$orderid = $order["id"];
		$list = $this->getChildOrder($orderid);
		if( !empty($list) ) 
		{
			$change_data = array( );
			if( $type == 1 ) 
			{
				$change_data["status"] = 1;
				$change_data["paytime"] = $time;
			}
			$change_data["merchshow"] = 0;
			foreach( $list as $k => $v ) 
			{
				if( $v["status"] == 0 ) 
				{
					pdo_update("ewei_shop_order", $change_data, array( "id" => $v["id"] ));
				}
			}
		}
	}
	public function setOrderPayType($orderid, $paytype, $ordersn = "") 
	{
		global $_W;
		$count_ordersn = 1;
		$change_data = array( );
		if( !empty($ordersn) ) 
		{
			$count_ordersn = $this->countOrdersn($ordersn);
		}
		if( $count_ordersn == 2 ) 
		{
			$change_data["tradepaytype"] = $paytype;
		}
		else 
		{
			$change_data["paytype"] = $paytype;
		}
		pdo_update("ewei_shop_order", $change_data, array( "id" => $orderid ));
		if( !empty($orderid) ) 
		{
			pdo_update("ewei_shop_order", array( "paytype" => $paytype ), array( "parentid" => $orderid ));
		}
	}
	public function getChildOrder($orderid) 
	{
		global $_W;
		$list = pdo_fetchall("select id,ordersn,status,finishtime,couponid,merchid  from " . tablename("ewei_shop_order") . " where  parentid=:parentid and uniacid=:uniacid", array( ":parentid" => $orderid, ":uniacid" => $_W["uniacid"] ));
		return $list;
	}
	public function payVirtualSend($orderid = 0, $ispeerpay = false) 
	{
		global $_W;
		global $_GPC;
		$order = pdo_fetch("select id,uniacid,ordersn, price,openid,dispatchtype,addressid,carrier,status,isverify,deductcredit2,`virtual`,isvirtual,couponid,isvirtualsend,isparent,paytype,merchid,agentid,createtime,buyagainprice,istrade,tradestatus,iscycelbuy from " . tablename("ewei_shop_order") . " where  id=:id and uniacid=:uniacid limit 1", array( ":uniacid" => $_W["uniacid"], ":id" => $orderid ));
		$order_goods = pdo_fetch("select g.virtualsend,g.virtualsendcontent from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where og.orderid=:orderid and og.uniacid=:uniacid limit 1", array( ":uniacid" => $order["uniacid"], ":orderid" => $orderid ));
		$time = time();
		pdo_update("ewei_shop_order", array( "virtualsend_info" => $order_goods["virtualsendcontent"], "status" => "3", "paytime" => $time, "sendtime" => $time, "finishtime" => $time ), array( "id" => $orderid ));
		$this->fullback($order["id"]);
		$this->setStocksAndCredits($orderid, 1);
		$this->setStocksAndCredits($orderid, 3);
		m("member")->upgradeLevel($order["openid"]);
		$this->setGiveBalance($orderid, 1);

        if( com("coupon") )
		{
			com("coupon")->sendcouponsbytask($order["id"]);
		}
		if( com("coupon") && !empty($order["couponid"]) ) 
		{
			com("coupon")->backConsumeCoupon($order["id"]);
		}
		m("notice")->sendOrderMessage($orderid);
		if( p("commission") )
		{
			p("commission")->checkOrderPay($order["id"]);
			p("commission")->checkOrderFinish($order["id"]);
		}
		$this->afterPayResult($order, $ispeerpay);
		return true;
	}
	public function afterPayResult($order, $ispeerpay = false) 
	{
		if( p("task") ) 
		{
			if( 0 < $order["deductcredit2"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductcredit2"]);
			}
			if( 0 < $order["deductcredit"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductprice"]);
			}
			if( $order["agentid"] ) 
			{
				p("task")->checkTaskReward("commission_order", 1);
			}
			p("task")->checkTaskReward("cost_total", $order["price"]);
			p("task")->checkTaskReward("cost_enough", $order["price"]);
			p("task")->checkTaskReward("cost_count", 1);
			$goodslist = pdo_fetchall("SELECT goodsid FROM " . tablename("ewei_shop_order_goods") . " WHERE orderid = :orderid AND uniacid = :uniacid", array( ":orderid" => $order["id"], ":uniacid" => $order["uniacid"] ));
			foreach( $goodslist as $item ) 
			{
				p("task")->checkTaskReward("cost_goods" . $item["goodsid"], 1, $order["openid"]);
			}
			if( 0 < $order["deductcredit2"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductcredit2"]);
			}
			if( 0 < $order["deductcredit"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductprice"]);
			}
			p("task")->checkTaskProgress($order["price"], "order_all", "", $order["openid"]);
			$goodslist = pdo_fetchall("SELECT goodsid FROM " . tablename("ewei_shop_order_goods") . " WHERE orderid = :orderid AND uniacid = :uniacid", array( ":orderid" => $order["id"], ":uniacid" => $order["uniacid"] ));

            $this->write_log('===='.$order['status'].'====');
//            if($order['status']==1){
//                $this->reward($goodslist,$order['openid'],$order);//lihanwen 会员推荐返佣金
//            }
			foreach( $goodslist as $item )
			{
				p("task")->checkTaskProgress(1, "goods", 0, $order["openid"], $item["goodsid"]);
			}
			if( pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_order") . " where openid = '" . $order["openid"] . "' and uniacid = " . $order["uniacid"]) == 1 ) 
			{
				p("task")->checkTaskProgress(1, "order_first", "", $order["openid"]);
			}
		}
		if( p("lottery") && empty($ispeerpay) ) 
		{
			if( 0 < $order["deductcredit2"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductcredit2"]);
			}
			if( 0 < $order["deductcredit"] ) 
			{
				$order["price"] = floatval($order["price"]) + floatval($order["deductprice"]);
			}
			$res = p("lottery")->getLottery($order["openid"], 1, array( "money" => $order["price"], "paytype" => 1 ));
			if( $res ) 
			{
				p("lottery")->getLotteryList($order["openid"], array( "lottery_id" => $res ));
			}
		}

	}


    /**
     * 记录log
     * @param $data
     */
    public function write_log($data){
        $url  = 'log.txt';
        $dir_name = dirname($url);
        if(!file_exists($dir_name)) {
            $res = mkdir(iconv("UTF-8","GBK",$dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
        fwrite($fp,var_export($data,true)."\r\n");//写入文件
        fclose($fp);//关闭资源通道
    }

    /**
     *  会员推荐返佣金,与会员关系绑定
     * @param $goodslist
     */
	public function reward($goodslist,$openid,$order){
	    foreach ($goodslist as $val){
	        if($val['cates']=='4'){
	            m('reward')->addReward($openid);
                $this->write_log('===='.$val['cates'].'====');
            }
            //店主开通店铺

	        if($val['goodsid']=='7') {
                $this->openstore($order['id']);
                //给购买人赠送990卡路里
                m('member')->shop_reward($openid, 5);
            }
	        if($order['agentid']){//会员关系绑定@lihanwen
	            //推荐人信息
                $agentmemberInfo = pdo_get('ewei_shop_member', array('id' =>$order['agentid']));
                if($agentmemberInfo){
                    m('member')->setagent(array('agentopenid'=>$agentmemberInfo["openid"],'openid'=>$openid,'goodsid'=>$val['goodsid']));
                }
            }
            $memberInfo = pdo_get('ewei_shop_member', array('openid' =>$order['openid']));
	        if($memberInfo['agentid']>0){
                m('member')->memberAgentCount($val['goodsid'],$memberInfo['agentid']);
            }
        }
	}

    /**
     * 开通店铺
     */
	public function openstore($orderid){
        global $_W;
        $orderInfo = pdo_fetch("select * from " . tablename("ewei_shop_order") . " where  id = " . $orderid);
        $memberInfo = pdo_fetch("select * from " . tablename("ewei_shop_member") . " where  openid = '".$orderInfo['openid']."'");

        if($orderInfo['carrier']){
            $addressInfo = unserialize($orderInfo['carrier']);
            if(!$addressInfo) return false;
            if(!$addressInfo['carrier_mobile'] || !$addressInfo['carrier_realname']) return false;
            if($addressInfo['carrier_mobile']) $carrier_mobile = $addressInfo['carrier_mobile'];
            if($addressInfo['carrier_realname']) $carrier_realname = $addressInfo['carrier_realname'];
        }else{
            return false;
        }

        $data['mobile'] = $data['merchname'] = $carrier_mobile;
        $data['status'] = 1;
        $data['accounttime'] = time();
        $data['jointime'] = time();
        $data['status'] = 1;
        $data['uniacid'] = $data['groupid'] = 1;
        $data['realname'] = $carrier_realname;
        $data['member_id'] = $memberInfo['id'];
        $data['payopenid'] = $data['wxopenid'] = $orderInfo['openid'];
        $merchInfo = pdo_fetch("select * from " . tablename("ewei_shop_merch_user") . " where  mobile = '" . $data['mobile'] . "'");
        if($merchInfo) return true;

        pdo_insert("ewei_shop_merch_user", $data);
        $id = pdo_insertid();
        $account["merchid"] = $id;
        $salt = "";
        $pwd = "";
        if (empty($account) || empty($account["salt"]) || !empty($_GPC["pwd"])) {
            $salt = random(8);
            while (1) {
                $saltcount = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_merch_account") . " where salt=:salt limit 1", array(":salt" => $salt));
                if ($saltcount <= 0) {
                    break;
                }

                $salt = random(8);
            }
            $pwd = md5('12345678' . $salt);
        } else {
            $salt = $account["salt"];
            $pwd = $account["pwd"];
        }
        $account = array("uniacid" => $_W["uniacid"], "merchid" => $id, "username" => $data['mobile'], "pwd" => $pwd, "salt" => $salt, "status" => 1, "perms" => serialize(array()), "isfounder" => 1);
        pdo_insert("ewei_shop_merch_account", $account);
        $accountid = pdo_insertid();
        pdo_update("ewei_shop_merch_user", array("accountid" => $accountid), array("id" => $id));
        plog("merch.user.add", "添加商户 ID: " . $data["id"] . " 商户名: " . $data["merchname"] . "<br/>帐号: " . $data["username"] . "<br/>子帐号数: " . $data["accounttotal"] . "<br/>到期时间: " . date("Y-m-d", $data["accounttime"]));
        //发送短信
        $this->opensend(2,$data['mobile'],array($data['mobile']));
        return true;
    }

    /**
     *  发送阿里大鱼短信
     * @param $id
     * @param $mobile
     * @param $data
     * @return bool
     */
    public function opensend($id,$mobile,$data)
    {
        $send = false;

        if (!empty($id)) {
            $item = pdo_fetch('SELECT * FROM ' . tablename('ewei_shop_sms') . ' WHERE id=:id', array(':id' => $id));

            if (!empty($item)) {
                $item['data'] = iunserializer($item['data']);
                if (!empty($item['data']) && is_array($item['data'])) {
                    $send = true;
                }
                else {
                    $errmsg = '模板数据错误，请编辑后重试!';
                }
            }
            else {
                $errmsg = '模板不存在，请刷新重试!';
            }
        }
        else {
            $errmsg = '参数错误，请刷新重试!';
        }

        if ($send) {
            $mobile = trim($mobile);
            $postdata = $data;

            if (empty($mobile)) {
                show_json(0, '手机号不能为空!');
            }

            if (empty($postdata)) {
                show_json(0, '数据为空!');
            }

            if ($item['type'] == 'juhe' || $item['type'] == 'dayu' || $item['type'] == 'aliyun' || $item['type'] == 'aliyun_new') {
                $sms_data = array();

                foreach ($item['data'] as $i => $d) {
                    $sms_data[$d['data_temp']] = $postdata[$i];
                }
            }
            else {
                if ($item['type'] == 'emay') {
                    $sms_data = trim($postdata);
                }
            }

            $result = com('sms')->send($mobile, $item['id'], $sms_data, false);
            if (empty($result['status'])) {
                return false;
            }
            else {
               return true;
            }
        }

    }

	public function getGoodsCredit($goods) 
	{
		global $_W;
		$credits = 0;
		foreach( $goods as $g ) 
		{
			$gcredit = trim($g["credit"]);
			if( !empty($gcredit) ) 
			{
				if( strexists($gcredit, "%") ) 
				{
					$credits += intval(floatval(str_replace("%", "", $gcredit)) / 100 * $g["realprice"]);
				}
				else 
				{
					$credits += intval($g["credit"]) * $g["total"];
				}
			}
		}
		return $credits;
	}
	public function setDeductCredit2($order) 
	{
		global $_W;
		if( 0 < $order["deductcredit2"] ) 
		{
		    //修改
		    $member=pdo_fetch("select * from ".tablename("ewei_shop_member")." where openid=:openid or id=:user_id limit 1",array(":openid"=>$order["openid"],":user_id"=>$order["user_id"]));
// 			m("member")->setCredit($order["openid"], "credit2", $order["deductcredit2"], array( "0", $_W["shopset"]["shop"]["name"] . "购物返还抵扣余额 余额: " . $order["deductcredit2"] . " 订单号: " . $order["ordersn"] ));
		    m("member")->setCredit($member["id"], "credit2", $order["deductcredit2"], array( "0", $_W["shopset"]["shop"]["name"] . "购物返还抵扣余额 余额: " . $order["deductcredit2"] . " 订单号: " . $order["ordersn"] ));
		    
		}
	}
	public function setGiveBalance($orderid = "", $type = 0,$order_goodsid="") 
	{
		global $_W;
		$order = pdo_fetch("select id,ordersn,price,openid,user_id,dispatchtype,addressid,carrier,status from " . tablename("ewei_shop_order") . " where id=:id limit 1", array( ":id" => $orderid ));
		if ($order["user_id"]){
		    $member=m("member")->getMember($order["user_id"]);
		}else{
		    $member=m("member")->getMember($order["openid"]);
		}
		if ($order_goodsid){
		$goods = pdo_fetchall("select og.goodsid,og.total,g.totalcnf,og.realprice,g.money,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where og.id in(:orderid) and og.uniacid=:uniacid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $order_goodsid ));
		
		}else{
		$goods = pdo_fetchall("select og.goodsid,og.total,g.totalcnf,og.realprice,g.money,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		}
		$balance = 0;
		foreach( $goods as $g ) 
		{
			$gbalance = trim($g["money"]);
			if( !empty($gbalance) ) 
			{
				if( strexists($gbalance, "%") ) 
				{
					$balance += round(floatval(str_replace("%", "", $gbalance)) / 100 * $g["realprice"], 2);
				}
				else 
				{
					$balance += round($g["money"], 2) * $g["total"];
				}
			}
		}
		if( 0 < $balance ) 
		{
			$shopset = m("common")->getSysset("shop");
			if( $type == 1 ) 
			{
				if( $order["status"] == 3 ) 
				{
					m("member")->setCredit($member["id"], "credit2", $balance, array( 0, $shopset["name"] . "购物赠送余额 订单号: " . $order["ordersn"] ));
				}
			}
			else 
			{
				if( $type == 2 && 1 <= $order["status"] ) 
				{
					m("member")->setCredit($member["id"], "credit2", 0 - $balance, array( 0, $shopset["name"] . "购物取消订单扣除赠送余额 订单号: " . $order["ordersn"] ));
				}
			}
		}
	}
	public function setStocksAndCredits($orderid = "", $type = 0,$order_goodsid="") 
	{
		global $_W;
		$order = pdo_fetch("select id,ordersn,price,openid,dispatchtype,addressid,carrier,status,isparent,paytype,isnewstore,storeid,istrade,status from " . tablename("ewei_shop_order") . " where id=:id limit 1", array( ":id" => $orderid ));
		if ($order["user_id"]){
		    $member=m("member")->getMember($order["user_id"]);
		}else{
		    $member=m("member")->getMember($order["openid"]);
		}
		
		if( !empty($order["istrade"]) ) 
		{
			return NULL;
		}
		if( empty($order["isnewstore"]) ) 
		{
			$newstoreid = 0;
		}
		else 
		{
		    $newstoreid = intval($order["storeid"]);//自提门店ID
		}
		$param = array( );
		$param[":uniacid"] = $_W["uniacid"];
		if( $order["isparent"] == 1 ) 
		{
			$condition = " og.parentorderid=:parentorderid";
			$param[":parentorderid"] = $orderid;
		}
		else 
		{
			$condition = " og.orderid=:orderid";
			$param[":orderid"] = $orderid;
		}
		//部分商品
		if ($order_goodsid){
		    $condition=$condition." and og.id in(:id)";
		    $param[":id"]=$order_goodsid;
		}
		$goods = pdo_fetchall("select og.goodsid,og.seckill,og.total,g.totalcnf,og.realprice,g.credit,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal,g.type from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where " . $condition . " and og.uniacid=:uniacid ", $param);
		$credits = 0;
		foreach( $goods as $g ) 
		{
			if( 0 < $newstoreid ) 
			{
				$store_goods = m("store")->getStoreGoodsInfo($g["goodsid"], $newstoreid);
				if( empty($store_goods) ) 
				{
					return NULL;
				}
				$g["goodstotal"] = $store_goods["stotal"];
			}
			else 
			{
				$goods_item = pdo_fetch("select total as goodstotal from" . tablename("ewei_shop_goods") . " where id=:id and uniacid=:uniacid limit 1", array( ":id" => $g["goodsid"], ":uniacid" => $_W["uniacid"] ));
				$g["goodstotal"] = $goods_item["goodstotal"];//商品库存
			}
			$stocktype = 0;
			if( $type == 0 ) 
			{
				if( $g["totalcnf"] == 0 ) 
				{
					$stocktype = -1;
				}
			}
			else 
			{
				if( $type == 1 ) 
				{
					if( $g["totalcnf"] == 1 ) 
					{
						$stocktype = -1;
					}
				}
				else 
				{
					if( $type == 2 ) 
					{
						if( 1 <= $order["status"] ) 
						{
							if( $g["totalcnf"] != 2 ) 
							{
								$stocktype = 1;
							}
						}
						else 
						{
							if( $g["totalcnf"] == 0 ) 
							{
								$stocktype = 1;
							}
						}
					}
				}
			}
			if( !empty($stocktype) ) 
			{
				$data = m("common")->getSysset("trade");
				if( !empty($data["stockwarn"]) ) 
				{
					$stockwarn = intval($data["stockwarn"]);
				}
				else 
				{
					$stockwarn = 5;
				}
				if( !empty($g["optionid"]) ) 
				{
					$option = m("goods")->getOption($g["goodsid"], $g["optionid"]);
					if( 0 < $newstoreid ) 
					{
					    //门店自提
						$store_goods_option = m("store")->getOneStoreGoodsOption($g["optionid"], $g["goodsid"], $newstoreid);
						if( empty($store_goods_option) ) 
						{
							return NULL;
						}
						$option["stock"] = $store_goods_option["stock"];
					}
					if( !empty($option) && $option["stock"] != -1 ) 
					{
						$stock = -1;
						if( $stocktype == 1 ) 
						{
							$stock = $option["stock"] + $g["total"];
						}
						else 
						{
							if( $stocktype == -1 ) 
							{
								
							$stock = $option['stock'] - $g['total'];
							($stock <= 0) && ($stock = 0);

							if (($stock <= $stockwarn) && ($newstoreid == 0)) {
								m('notice')->sendStockWarnMessage($g['goodsid'], $g['optionid']);
							}
							}
						}
						if( $stock != -1 ) 
						{
							if( 0 < $newstoreid ) 
							{
							    
								pdo_update("ewei_shop_newstore_goods_option", array( "stock" => $stock ), array( "uniacid" => $_W["uniacid"], "goodsid" => $g["goodsid"], "id" => $store_goods_option["id"] ));
							}
							else 
							{
							    //商品库存更改
								pdo_update("ewei_shop_goods_option", array( "stock" => $stock ), array( "uniacid" => $_W["uniacid"], "goodsid" => $g["goodsid"], "id" => $g["optionid"] ));
							}
						}
					}
				}
				if( !empty($g["goodstotal"]) && $g["goodstotal"] != -1 ) 
				{
					$totalstock = -1;
					if( $stocktype == 1 ) 
					{
						$totalstock = $g["goodstotal"] + $g["total"];
					}
					else 
					{
						if( $stocktype == -1 ) 
						{
							$totalstock = $g['goodstotal'] - $g['total'];
						($totalstock <= 0) && ($totalstock = 0);

						if (($totalstock <= $stockwarn) && ($newstoreid == 0)) {
							m('notice')->sendStockWarnMessage($g['goodsid'], 0);
						}
						}
					}
					if( $totalstock != -1 ) 
					{
						if( 0 < $newstoreid ) 
						{
							pdo_update("ewei_shop_newstore_goods", array( "stotal" => $totalstock ), array( "uniacid" => $_W["uniacid"], "id" => $store_goods["id"] ));
						}
						else 
						{
						    //库存
							pdo_update("ewei_shop_goods", array( "total" => $totalstock ), array( "uniacid" => $_W["uniacid"], "id" => $g["goodsid"] ));
						}
					}
				}
			}
			$isgoodsdata = m("common")->getPluginset("sale");
			$isgoodspoint = iunserializer($isgoodsdata["credit1"]);
			if( !empty($isgoodspoint["isgoodspoint"]) && $isgoodspoint["isgoodspoint"] == 1 ) 
			{
			    //购买赠送积分，如果带%号，则为按成交价比例计算
				$gcredit = trim($g["credit"]);
				if( $g["seckill"] != 1 && !empty($gcredit) ) 
				{
					if( strexists($gcredit, "%") ) 
					{
						$credits += intval(floatval(str_replace("%", "", $gcredit)) / 100 * $g["realprice"]);
					}
					else 
					{
						$credits += intval($g["credit"]) * $g["total"];
					}
				}
			}
			if( $type == 0 ) 
			{
			}
			else 
			{
				if( $type == 1 && 1 <= $order["status"] ) 
				{
				    //赠送卡路里记录
					$salesreal = pdo_fetchcolumn("select ifnull(sum(total),0) from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_order") . " o on o.id = og.orderid " . " where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1", array( ":goodsid" => $g["goodsid"], ":uniacid" => $_W["uniacid"] ));
					pdo_update("ewei_shop_goods", array( "salesreal" => $salesreal ), array( "id" => $g["goodsid"] ));
					$table_flag = pdo_tableexists("ewei_shop_order_buysend");
					if( 0 < $credits && $table_flag ) 
					{
						$send_data = array( "uniacid" => $_W["uniacid"], "orderid" => $orderid, "openid" => $member["openid"],"user_id"=>$member["id"], "credit" => $credits, "createtime" => TIMESTAMP );
						$send_record = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_order_buysend") . " WHERE orderid = :orderid AND uniacid = :uniacid AND (openid = :openid or user_id=:user_id)", array( ":orderid" => $orderid, ":uniacid" => $_W["uniacid"], ":openid" => $member["openid"],":user_id"=>$member["id"] ));
						if( $send_record ) 
						{
							pdo_update("ewei_shop_order_buysend", $send_data, array( "id" => $send_record["id"] ));
						}
						else 
						{
							pdo_insert("ewei_shop_order_buysend", $send_data);
						}
					}
				}
			}
		}
		$table_flag = pdo_tableexists("ewei_shop_order_buysend");
		if( $table_flag ) 
		{
			$send_record = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_order_buysend") . " WHERE orderid = :orderid AND uniacid = :uniacid AND (openid = :openid or user_id=:user_id)", array( ":orderid" => $orderid, ":uniacid" => $_W["uniacid"], ":openid" => $member["openid"],":user_id"=>$member["id"] ));
			if( $send_record && 0 < $send_record["credit"] ) 
			{
				$credits = $send_record["credit"];
			}
		}
		if( 0 < $credits ) 
		{
			$shopset = m("common")->getSysset("shop");
			if( $type == 3 ) 
			{
				if( $order["status"] == 3 ) 
				{
					m("member")->setCredit($member["id"], "credit1", $credits, array( 0, $shopset["name"] . "购物卡路里 订单号: " . $order["ordersn"] ));
					m("notice")->sendMemberPointChange($order["openid"], $credits, 0, 3);
				}
			}
			else 
			{
				if( $type == 2 && $order["status"] == 3 ) 
				{
					m("member")->setCredit($member["id"], "credit1", 0 - $credits, array( 0, $shopset["name"] . "购物取消订单扣除卡路里 订单号: " . $order["ordersn"] ));
					m("notice")->sendMemberPointChange($order["openid"], $credits, 1, 3);
				}
			}
		}
		else 
		{
			if( $type == 3 ) 
			{
				if( $order["status"] == 3 ) 
				{
					$money = com_run("sale::getCredit1", $order["openid"], (double) $order["price"], $order["paytype"], 1);
					if( 0 < $money ) 
					{
						m("notice")->sendMemberPointChange($order["openid"], $money, 0, 3);
					}
				}
			}
			else 
			{
				if( $type == 2 && $order["status"] == 3 ) 
				{
					$money = com_run("sale::getCredit1", $order["openid"], (double) $order["price"], $order["paytype"], 1, 1);
					if( 0 < $money ) 
					{
						m("notice")->sendMemberPointChange($order["openid"], $money, 1, 3);
					}
				}
			}
		}
	}
	public function getTotals($merch = 0) 
	{
		global $_W;
		$paras = array( ":uniacid" => $_W["uniacid"] );
		$merch = intval($merch);
		$condition = " and isparent=0";
		if( $merch < 0 ) 
		{
			$condition .= " and merchid=0";
		}
		$totals["all"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0 and deleted=0", $paras);
		$totals["status_1"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0 and status=-1 and refundtime=0 and deleted=0", $paras);
		$totals["status0"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0  and status=0 and paytype<>3 and deleted=0", $paras);
		$totals["status1"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0  and ( status=1 or ( status=0 and paytype=3) ) and deleted=0", $paras);
		$totals["status2"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0  and ( status=2 or (status = 1 and sendtype > 0) ) and deleted=0", $paras);
		$totals["status3"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0  and status=3 and deleted=0", $paras);
		$totals["status4"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0  and refundstate>0 and refundid<>0 and deleted=0", $paras);
		$totals["status5"] = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename("ewei_shop_order") . "" . " WHERE uniacid = :uniacid " . $condition . " and ismr=0 and refundtime<>0 and deleted=0", $paras);
		return $totals;
	}
	public function getFormartDiscountPrice($isd, $gprice, $gtotal = 1) 
	{
		$price = $gprice;
		if( !empty($isd) ) 
		{
			if( strexists($isd, "%") ) 
			{
				$dd = floatval(str_replace("%", "", $isd));
				if( 0 < $dd && $dd < 100 ) 
				{
					$price = round($dd / 100 * $gprice, 2);
				}
			}
			else 
			{
				if( 0 < floatval($isd) ) 
				{
					$price = round(floatval($isd * $gtotal), 2);
				}
			}
		}
		return $price;
	}
	public function getGoodsDiscounts($goods, $isdiscount_discounts, $levelid, $options = array( )) 
	{
		$key = (empty($levelid) ? "default" : "level" . $levelid);
		$prices = array( );
		if( empty($goods["merchsale"]) ) 
		{
			if( !empty($isdiscount_discounts[$key]) ) 
			{
				foreach( $isdiscount_discounts[$key] as $k => $v ) 
				{
					$k = substr($k, 6);
					$op_marketprice = m("goods")->getOptionPirce($goods["id"], $k);
					$gprice = $this->getFormartDiscountPrice($v, $op_marketprice);
					$prices[] = $gprice;
					if( !empty($options) ) 
					{
						foreach( $options as $key => $value ) 
						{
							if( $value["id"] == $k ) 
							{
								$options[$key]["marketprice"] = $gprice;
							}
						}
					}
				}
			}
		}
		else 
		{
			if( !empty($isdiscount_discounts["merch"]) ) 
			{
				foreach( $isdiscount_discounts["merch"] as $k => $v ) 
				{
					$k = substr($k, 6);
					$op_marketprice = m("goods")->getOptionPirce($goods["id"], $k);
					$gprice = $this->getFormartDiscountPrice($v, $op_marketprice);
					$prices[] = $gprice;
					if( !empty($options) ) 
					{
						foreach( $options as $key => $value ) 
						{
							if( $value["id"] == $k ) 
							{
								$options[$key]["marketprice"] = $gprice;
							}
						}
					}
				}
			}
		}
		$data = array( );
		$data["prices"] = $prices;
		$data["options"] = $options;
		return $data;
	}
	public function getGoodsDiscountPrice($g, $level, $type = 0) 
	{
		global $_W;
		if( !empty($level["id"]) ) 
		{
			$level = pdo_fetch("select * from " . tablename("ewei_shop_member_level") . " where id=:id and uniacid=:uniacid and enabled=1 limit 1", array( ":id" => $level["id"], ":uniacid" => $_W["uniacid"] ));
			$level = (empty($level) ? array( ) : $level);
		}
		if( $type == 0 ) 
		{
			$total = $g["total"];
		}
		else 
		{
			$total = 1;
		}
		$gprice = $g["marketprice"] * $total;
		if( empty($g["buyagain_islong"]) ) 
		{
			$gprice = $g["marketprice"] * $total;
		}
		$buyagain_sale = true;
		$buyagainprice = 0;
		$canbuyagain = false;
		if( empty($g["is_task_goods"]) && 0 < floatval($g["buyagain"]) && m("goods")->canBuyAgain($g) ) 
		{
			$canbuyagain = true;
			if( empty($g["buyagain_sale"]) ) 
			{
				$buyagain_sale = false;
			}
		}
		$price = $gprice;
		$price1 = $gprice;
		$price2 = $gprice;
		$taskdiscountprice = 0;
		$lotterydiscountprice = 0;
		if( !empty($g["is_task_goods"]) ) 
		{
			$buyagain_sale = false;
			$price = $g["task_goods"]["marketprice"] * $total;
			if( $price < $gprice ) 
			{
				$d_price = abs($gprice - $price);
				if( $g["is_task_goods"] == 1 ) 
				{
					$taskdiscountprice = $d_price;
				}
				else 
				{
					if( $g["is_task_goods"] == 2 ) 
					{
						$lotterydiscountprice = $d_price;
					}
				}
			}
		}
		$discountprice = 0;
		$isdiscountprice = 0;
		$isd = false;
		$isdiscount_discounts = @json_decode($g["isdiscount_discounts"], true);
		$discounttype = 0;
		$isCdiscount = 0;
		$isHdiscount = 0;
		if( $g["isdiscount"] == 1 && time() <= $g["isdiscount_time"] && $buyagain_sale ) 
		{
			if( is_array($isdiscount_discounts) ) 
			{
				$key = (!empty($level["id"]) ? "level" . $level["id"] : "default");
				if( !isset($isdiscount_discounts["type"]) || empty($isdiscount_discounts["type"]) ) 
				{
					if( empty($g["merchsale"]) ) 
					{
						$isd = trim($isdiscount_discounts[$key]["option0"]);
						if( !empty($isd) ) 
						{
							$price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
						}
					}
					else 
					{
						$isd = trim($isdiscount_discounts["merch"]["option0"]);
						if( !empty($isd) ) 
						{
							$price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
						}
					}
				}
				else 
				{
					if( empty($g["merchsale"]) ) 
					{
						$isd = trim($isdiscount_discounts[$key]["option" . $g["optionid"]]);
						if( !empty($isd) ) 
						{
							$price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
						}
					}
					else 
					{
						$isd = trim($isdiscount_discounts["merch"]["option" . $g["optionid"]]);
						if( !empty($isd) ) 
						{
							$price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
						}
					}
				}
			}
			if( $gprice <= $price1 ) 
			{
				$isdiscountprice = 0;
				$isCdiscount = 0;
			}
			else 
			{
				$isdiscountprice = abs($price1 - $gprice);
				$isCdiscount = 1;
			}
		}
		if( empty($g["isnodiscount"]) && $buyagain_sale ) 
		{
			$discounts = json_decode($g["discounts"], true);
			if( empty($g["discounts"]) && 0 < $g["merchid"] ) 
			{
				$g["discounts"] = array( "type" => "0", "default" => "", "default_pay" => "" );
				if( !empty($level) ) 
				{
					$g["discounts"]["level" . $level["id"]] = "";
					$g["discounts"]["level" . $level["id"] . "_pay"] = "";
				}
				$discounts = $g["discounts"];
			}
			if( is_array($discounts) ) 
			{
				$key = (!empty($level["id"]) ? "level" . $level["id"] : "default");
				if( !isset($discounts["type"]) || empty($discounts["type"]) ) 
				{
					if( !empty($discounts[$key]) ) 
					{
						$dd = floatval($discounts[$key]);
						if( 0 < $dd && $dd < 10 ) 
						{
							$price2 = round($dd / 10 * $gprice, 2);
						}
					}
					else 
					{
						$dd = floatval($discounts[$key . "_pay"] * $total);
						$md = floatval($level["discount"]);
						if( !empty($dd) ) 
						{
							$price2 = round($dd, 2);
						}
						else 
						{
							if( 0 < $md) 
							{
								$price2 = round($md / 10 * $gprice, 2);
							}
						}
					}
				}
				else 
				{
					$isd = trim($discounts[$key]["option" . $g["optionid"]]);
					if( !empty($isd) ) 
					{
						$price2 = $this->getFormartDiscountPrice($isd, $gprice, $total);
					}
				}
			}
			if( $gprice <= $price2 ) 
			{
				$discountprice = 0;
				$isHdiscount = 0;
			}
			else 
			{
				$discountprice = abs($price2 - $gprice);
				$isHdiscount = 1;
			}
		}
		if( $isCdiscount == 1 ) 
		{
			$price = $price1;
			$discounttype = 1;
		}
		else 
		{
			if( $isHdiscount == 1 ) 
			{
				$price = $price2;
				$discounttype = 2;
			}
		}
		$unitprice = round($price / $total, 2);
		$isdiscountunitprice = round($isdiscountprice / $total, 2);
		$discountunitprice = round($discountprice / $total, 2);
		if( $canbuyagain ) 
		{
			if( empty($g["buyagain_islong"]) ) 
			{
				$buyagainprice = ($unitprice * (10 - $g["buyagain"])) / 10;
			}
			else 
			{
				$buyagainprice = ($price * (10 - $g["buyagain"])) / 10;
			}
		}
		$price = $price - $buyagainprice;
		return array( "unitprice" => $unitprice, "price" => $price, "taskdiscountprice" => $taskdiscountprice, "lotterydiscountprice" => $lotterydiscountprice, "discounttype" => $discounttype, "isdiscountprice" => $isdiscountprice, "discountprice" => $discountprice, "isdiscountunitprice" => $isdiscountunitprice, "discountunitprice" => $discountunitprice, "price0" => $gprice, "price1" => $price1, "price2" => $price2, "buyagainprice" => $buyagainprice );
	}
	public function getChildOrderPrice(&$order, &$goods, &$dispatch_array, $merch_array, $sale_plugin, $discountprice_array, $orderid = 0) 
	{
		global $_GPC;
		$tmp_goods = $goods;
		$is_exchange = p("exchange") && $_SESSION["exchange"];
		if( $is_exchange ) 
		{
			foreach( $dispatch_array["dispatch_merch"] as &$dispatch_merch ) 
			{
				$dispatch_merch = 0;
			}
			unset($dispatch_merch);
			$postage = $_SESSION["exchange_postage_info"];
			$exchangepriceset = (array) $_SESSION["exchangepriceset"];
			foreach( $goods as $gk => $one_goods ) 
			{
				$goods[$gk]["ggprice"] = 0;
				$tmp_goods[$gk]["marketprice"] = 0;
			}
			foreach( $exchangepriceset as $pset ) 
			{
				foreach( $goods as $gk => &$one_goods ) 
				{
					if( $one_goods["ggprice"] == 0 && ($one_goods["optionid"] == $pset[0] || $one_goods["goodsid"] == $pset[0]) ) 
					{
						$one_goods["ggprice"] += $pset[2];
						$tmp_goods[$gk]["marketprice"] += $pset[2];
						break;
					}
				}
				unset($one_goods);
			}
		}
		$totalprice = $order["price"];
		$goodsprice = $order["goodsprice"];
		$grprice = $order["grprice"];
		$deductprice = $order["deductprice"];
		$deductcredit = $order["deductcredit"];
		$deductcredit2 = $order["deductcredit2"];
		$deductenough = $order["deductenough"];
		$is_deduct = 0;
		$is_deduct2 = 0;
		$deduct_total = 0;
		$deduct2_total = 0;
		$ch_order = array( );
		if( $sale_plugin ) 
		{
			if( !empty($_GPC["deduct"]) ) 
			{
				$is_deduct = 1;
			}
			if( !empty($_GPC["deduct2"]) ) 
			{
				$is_deduct2 = 1;
			}
		}
		foreach( $goods as $gk => &$g ) 
		{
			$merchid = $g["merchid"];
			$ch_order[$merchid]["goods"][] = $g["goodsid"];
			$ch_order[$merchid]["grprice"] += $g["ggprice"];
			$ch_order[$merchid]["goodsprice"] += $tmp_goods[$gk]["marketprice"] * $g["total"];
			$ch_order[$merchid]["couponprice"] = $discountprice_array[$merchid]["deduct"];
			if( $is_deduct == 1 ) 
			{
				if( $g["manydeduct"] ) 
				{
					$deduct = $g["deduct"] * $g["total"];
				}
				else 
				{
					$deduct = $g["deduct"];
				}
				if( $g["seckillinfo"] && $g["seckillinfo"]["status"] == 0 ) 
				{
				}
				else 
				{
					$deduct_total += $deduct;
					$ch_order[$merchid]["deducttotal"] += $deduct;
				}
			}
			if( $is_deduct2 == 1 ) 
			{
				if( $g["deduct2"] == 0 ) 
				{
					$deduct2 = $g["ggprice"];
				}
				else 
				{
					if( 0 < $g["deduct2"] ) 
					{
						if( $g["ggprice"] < $g["deduct2"] ) 
						{
							$deduct2 = $g["ggprice"];
						}
						else 
						{
							$deduct2 = $g["deduct2"];
						}
					}
				}
				if( $g["seckillinfo"] && $g["seckillinfo"]["status"] == 0 ) 
				{
				}
				else 
				{
					$ch_order[$merchid]["deduct2total"] += $deduct2;
					$deduct2_total += $deduct2;
				}
			}
		}
		unset($g);
		foreach( $ch_order as $k => $v ) 
		{
			if( $is_deduct == 1 && 0 < $deduct_total ) 
			{
				$n = $v["deducttotal"] / $deduct_total;
				$deduct_credit = ceil(round($deductcredit * $n, 2));
				$deduct_money = round($deductprice * $n, 2);
				$ch_order[$k]["deductcredit"] = $deduct_credit;
				$ch_order[$k]["deductprice"] = $deduct_money;
			}
			if( $is_deduct2 == 1 && 0 < $deduct2_total ) 
			{
				$n = $v["deduct2total"] / $deduct2_total;
				$deduct_credit2 = round($deductcredit2 * $n, 2);
				$ch_order[$k]["deductcredit2"] = $deduct_credit2;
			}
			$op = ($grprice == 0 ? 0 : round($v["grprice"] / $grprice, 2));
			$ch_order[$k]["op"] = $op;
			if( 0 < $deductenough ) 
			{
				$deduct_enough = round($deductenough * $op, 2);
				$ch_order[$k]["deductenough"] = $deduct_enough;
			}
		}
		if( $is_exchange ) 
		{
			if( is_array($postage) ) 
			{
				foreach( $ch_order as $mid => $ch ) 
				{
					$flip = array_flip(array_flip($ch["goods"]));
					foreach( $flip as $gid ) 
					{
						$dispatch_array["dispatch_merch"][$mid] += $postage[$gid];
					}
				}
			}
			else 
			{
				$old_dispatch_price = $order["dispatchprice"];
				$_SESSION["exchangepostage"] = $postage * count($dispatch_array["dispatch_merch"]);
				$order["dispatchprice"] = $_SESSION["exchangepostage"];
				pdo_update("ewei_shop_order", array( "dispatchprice" => $order["dispatchprice"], "price" => ($order["price"] + $order["dispatchprice"]) - $old_dispatch_price ), array( "id" => $orderid ));
				foreach( $dispatch_array["dispatch_merch"] as &$dispatch_merch ) 
				{
					$dispatch_merch = $postage;
				}
				unset($dispatch_merch);
			}
		}
		foreach( $ch_order as $k => $v ) 
		{
			$merchid = $k;
			$price = $v["grprice"] - $v["deductprice"] - $v["deductcredit2"] - $v["deductenough"] - $v["couponprice"] + $dispatch_array["dispatch_merch"][$merchid];
			if( 0 < $merchid ) 
			{
				$merchdeductenough = $merch_array[$merchid]["enoughdeduct"];
				if( 0 < $merchdeductenough ) 
				{
					$price -= $merchdeductenough;
					$ch_order[$merchid]["merchdeductenough"] = $merchdeductenough;
				}
			}
			$ch_order[$merchid]["price"] = $price;
		}
		return $ch_order;
	}
	public function getMerchEnough($merch_array) 
	{
		$merch_enough_total = 0;
		$merch_saleset = array( );
		foreach( $merch_array as $key => $value ) 
		{
			$merchid = $key;
			if( 0 < $merchid ) 
			{
				$enoughs = $value["enoughs"];
				if( !empty($enoughs) ) 
				{
					$ggprice = $value["ggprice"];
					foreach( $enoughs as $e ) 
					{
						if( floatval($e["enough"]) <= $ggprice && 0 < floatval($e["money"]) ) 
						{
							$merch_array[$merchid]["showenough"] = 1;
							$merch_array[$merchid]["enoughmoney"] = $e["enough"];
							$merch_array[$merchid]["enoughdeduct"] = $e["money"];
							$merch_saleset["merch_showenough"] = 1;
							$merch_saleset["merch_enoughmoney"] += $e["enough"];
							$merch_saleset["merch_enoughdeduct"] += $e["money"];
							$merch_enough_total += floatval($e["money"]);
							break;
						}
					}
				}
			}
		}
		$data = array( );
		$data["merch_array"] = $merch_array;
		$data["merch_enough_total"] = $merch_enough_total;
		$data["merch_saleset"] = $merch_saleset;
		return $data;
	}
	public function validate_city_express($address) 
	{
		global $_W;
		$city_express_data = array( "state" => 0, "enabled" => 0, "price" => 0, "is_dispatch" => 1 );
		$city_express = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_city_express") . " WHERE uniacid=:uniacid and merchid=0 limit 1", array( ":uniacid" => $_W["uniacid"] ));
		if( !empty($city_express["enabled"]) ) 
		{
			$city_express_data["enabled"] = 1;
			$city_express_data["is_dispatch"] = $city_express["is_dispatch"];
			$city_express_data["is_sum"] = $city_express["is_sum"];
			if( !empty($address) ) 
			{
				if( empty($address["lng"]) || empty($address["lat"]) ) 
				{
					$data = m("util")->geocode($address["province"] . $address["city"] . $address["area"] . $address["street"] . $address["address"], $city_express["geo_key"]);
					if( $data["status"] == 1 && 0 < $data["count"] ) 
					{
						$location = explode(",", $data["geocodes"][0]["location"]);
						$addres = $address;
						list($addres["lng"], $addres["lat"]) = $location;
						pdo_update("ewei_shop_member_address", $addres, array( "id" => $addres["id"], "uniacid" => $_W["uniacid"] ));
						$city_express_data = $this->compute_express_price($city_express, $location[0], $location[1]);
					}
				}
				else 
				{
					$city_express_data = $this->compute_express_price($city_express, $address["lng"], $address["lat"]);
				}
			}
		}
		return $city_express_data;
	}
	public function compute_express_price($city_express, $lng, $lat) 
	{
		$city_express_data = array( "state" => 0, "enabled" => 1, "price" => 0, "is_dispatch" => $city_express["is_dispatch"], "is_sum" => $city_express["is_sum"] );
		$distance = m("util")->GetDistance($city_express["lat"], $city_express["lng"], $lat, $lng);
		if( $distance < $city_express["range"] ) 
		{
			$city_express_data["state"] = 1;
			if( $distance <= $city_express["start_km"] * 1000 ) 
			{
				$city_express_data["price"] = intval($city_express["start_fee"]);
			}
			if( $city_express["start_km"] * 1000 < $distance && $distance <= $city_express["start_km"] * 1000 + $city_express["pre_km"] * 1000 ) 
			{
				$km = $distance - intval($city_express["start_km"] * 1000);
				$city_express_data["price"] = intval($city_express["start_fee"] + $city_express["pre_km_fee"] * ceil($km / 1000));
			}
			if( $city_express["fixed_km"] * 1000 <= $distance ) 
			{
				$city_express_data["price"] = intval($city_express["fixed_fee"]);
			}
		}
		return $city_express_data;
	}
	public function getOrderDispatchPrice($goods, $member, $address, $saleset = false, $merch_array, $t, $loop = 0) 
	{
		global $_W;
		$area_set = m("util")->get_area_config_set();
		$new_area = intval($area_set["new_area"]);
		$realprice = 0;
		$dispatch_price = 0;
		$dispatch_array = array( );
		$dispatch_merch = array( );
		$total_array = array( );
		$totalprice_array = array( );
		$nodispatch_array = array( );
		$goods_num = count($goods);
		$seckill_payprice = 0;
		$seckill_dispatchprice = 0;
		$user_province = "";
		$user_province_code = "";
		if( empty($new_area) )
		{
			if( !empty($address) ) 
			{
				$user_province = $user_province_code = $address["province"];
			}
			else 
			{
				if( !empty($member["province"]) )
				{
					if( !strexists($member["province"], "省") )
					{
						$member["province"] = $member["province"] . "省";
					}
					$user_province = $user_province_code = $member["province"];
				}
			}
		}
		else 
		{
			if( !empty($address) ) 
			{
			    $user_province = $address["province"] . $address["city"];
				$user_province_code = $address["datavalue"];
			}
		}
		$is_merchid = 0;
		foreach( $goods as $g ) 
		{
			$realprice += $g["ggprice"];
			$dispatch_merch[$g["merchid"]] = 0;  //商家的物流费用
			$total_array[$g["goodsid"]] += $g["total"];  //订单每个商品的购物数量
			$totalprice_array[$g["goodsid"]] += $g["ggprice"];
			if( !empty($g["merchid"]) ) 
			{
				$is_merchid = 1;
			}
		}
		$city_express_data["state"] = 0;
		$city_express_data["enabled"] = 0;
		$city_express_data["is_dispatch"] = 1;
		if( $is_merchid == 0 ) 
		{
			$city_express_data = $this->validate_city_express($address);
		}
		foreach( $goods as $g ) 
		{
			$seckillinfo = plugin_run("seckill::getSeckill", $g["goodsid"], $g["optionid"], true, $_W["openid"]);
			if( $seckillinfo && $seckillinfo["status"] == 0 ) 
			{
				$seckill_payprice += $g["ggprice"];
			}
			$isnodispatch = 0;
			$sendfree = false;
			$merchid = $g["merchid"];
			if( $g["type"] == 5 ) 
			{
				$sendfree = true;
			}
			if( $g["issendfree"]  == 1 )
			{
			    //如果包邮  但是是偏远地区邮费又不是空  那么 是偏远地域
			    $area = explode(';',$g['edareas']);
			    //如果他没设置  偏远  普通 默认 没有偏远地域
			    //if(!in_array($user_province,$area) && !empty($g['edareas'])&& $g['remote_dispatchprice'] != 0){
			    if(!in_array($user_province,$area) && !empty($g['edareas'])&& $g['remote_dispatchprice'] >= 0){
			    	$dispatch_price += $g['remote_dispatchprice'];
			        $is_remote = 1;
			    }else{
			    	$is_remote = 0;
			    }
			    $sendfree = true;
			}
			else 
			{
				if( $seckillinfo && $seckillinfo["status"] == 0 ) 
				{
				}
				else 
				{
					if( $g["ednum"] <= $total_array[$g["goodsid"]] && 0 < $g["ednum"] ) 
					{
						if( empty($new_area) ) 
						{
							$gareas = explode(";", $g["edareas"]);
						}
						else 
						{
							$gareas = explode(";", $g["edareas_code"]);
						}
						if( empty($gareas) ) 
						{
							$sendfree = true;
						}
						else 
						{
							if( !empty($address) ) 
							{
							    //如果满件包邮 但是是需要偏远地区加油费
								if( in_array($user_province_code, $gareas) )
								{
									$sendfree = true;
								}
							}
							else 
							{
                                if( !empty($member["province"]) )
                                {
                                    //如果满件包邮 但是是需要偏远地区加油费
                                    if( in_array($member["province"], $gareas) )
                                    {
                                        $sendfree = true;
                                    }
                                }
                                else
                                {
                                    $sendfree = true;
                                }
							}
						}
					}
				}
				if( $seckillinfo && $seckillinfo["status"] == 0 ) 
				{
				}
				else 
				{
					if( floatval($g["edmoney"]) <= $totalprice_array[$g["goodsid"]] && 0 < floatval($g["edmoney"]) ) 
					{
						if( empty($new_area) ) 
						{
							$gareas = explode(";", $g["edareas"]);
						}
						else 
						{
							$gareas = explode(";", $g["edareas_code"]);
						}
						if( empty($gareas) ) 
						{
							$sendfree = true;
						}
						else 
						{
							if( !empty($address) ) 
							{
							    //如果满额包邮 但是是需要偏远地区加油费
								if( in_array($user_province_code, $gareas) )
								{
									$sendfree = true;
								}
							}
							else 
							{
								if( !empty($member["province"]) )
								{
								    //如果满额包邮 但是是需要偏远地区加油费   如果在基础地域  则免邮费
									if( in_array($member["province"], $gareas) )
									{
										$sendfree = true;
									}
								}
								else
								{
									$sendfree = true;
								}
							}
						}
					}
				}
			}
			if( $g["dispatchtype"] == 1 ) 
			{
				if( $city_express_data["state"] == 0 && $city_express_data["is_dispatch"] == 1 ) 
				{
					if( !empty($user_province) )
					{
						if( empty($new_area) ) 
						{
							$citys = m("dispatch")->getAllNoDispatchAreas();
						}
						else 
						{
							$citys = m("dispatch")->getAllNoDispatchAreas("", 1);
						}
						if( !empty($citys) && in_array($user_province_code, $citys) && !empty($citys) )
						{
							$isnodispatch = 1;
							$has_goodsid = 0;
							if( !empty($nodispatch_array["goodid"]) && in_array($g["goodsid"], $nodispatch_array["goodid"]) ) 
							{
								$has_goodsid = 1;
							}
							if( $has_goodsid == 0 ) 
							{
								$nodispatch_array["goodid"][] = $g["goodsid"];
								$nodispatch_array["title"][] = $g["title"];
								$nodispatch_array["city"] = $user_province;
							}
						}
					}
					if( (0 < $g["dispatchprice"] || 0 < $g['remote_dispatchprice']) && !$sendfree && $isnodispatch == 0  )
					{
					    //如果有偏远地域差价  加上他 没有 还是基础价
					    $remote_dispatchprice = $g['remote_dispatchprice'] > 0 ? $g['remote_dispatchprice'] : 0;
						$dispatch_merch[$merchid] += $g["dispatchprice"];
						$gareas = explode(';',$g['edareas']);
                        //if(!empty($address)&&in_array($user_province_code, $gareas) || !empty($member['province'])&&in_array($member['province'],$gareas)){
                        //先判断地址是不是空  基础邮费
                        if(!empty($address) ) {
                            if (in_array($user_province_code, $gareas) || !empty($member['province']) && in_array($member['province'], $gareas)) {
                                if ($seckillinfo && $seckillinfo["status"] == 0) {
                                    $seckill_dispatchprice += $g["dispatchprice"];
                                } else {
                                    $dispatch_price += $g["dispatchprice"];
                                }
                                $is_remote = 0;
                            } else {
                                if ($seckillinfo && $seckillinfo["status"] == 0) {
                                    $seckill_dispatchprice += $g["dispatchprice"] + $remote_dispatchprice;
                                } else {
                                    $dispatch_price += $g["dispatchprice"] + $remote_dispatchprice;
                                }
                                $is_remote = 1;
                            }
                        }else{
                            $dispatch_price = $g["dispatchprice"];
			                $is_remote = 0;
                        }
					}
				}
				else 
				{
					if( $city_express_data["state"] == 1 ) 
					{
						if( ($g["dispatchprice"] >= 0 || $g['remote_dispatchprice'] >= 0) && !$sendfree )
						{
						    //如果有偏远地域差价  加上他 没有 还是基础差价
                            $remote_dispatchprice = $g['remote_dispatchprice'] >= 0 ?$g['remote_dispatchprice'] :0;
							if( $city_express_data["is_sum"] == 1 )
							{
								$dispatch_price += $g["dispatchprice"]+$remote_dispatchprice;
							}
							else
							{
								if( $dispatch_price < $g["dispatchprice"] )
								{
									$dispatch_price = $g["dispatchprice"]+$remote_dispatchprice;
								}
							}
						}
					}
					else 
					{
						$nodispatch_array["goodid"][] = $g["goodsid"];
						$nodispatch_array["title"][] = $g["title"];
						$nodispatch_array["city"] = $user_province;
					}
				}
			}
			else 
			{
				if( $g["dispatchtype"] == 0 ) 
				{
					if( $city_express_data["state"] == 0 && $city_express_data["is_dispatch"] == 1 ) 
					{
						if( empty($g["dispatchid"]) ) 
						{
							$dispatch_data = m("dispatch")->getDefaultDispatch($merchid);
						}
						else 
						{
							$dispatch_data = m("dispatch")->getOneDispatch($g["dispatchid"]);
						}
						if( empty($dispatch_data) ) 
						{
							$dispatch_data = m("dispatch")->getNewDispatch($merchid);
						}
						if( !empty($dispatch_data) ) 
						{
							$isnoarea = 0;
							$dkey = $dispatch_data["id"];
							$isdispatcharea = intval($dispatch_data["isdispatcharea"]);
							if( !empty($user_province) )
							{
							    	//$isdispatcharea  == 0
								if( empty($isdispatcharea) ) 
								{
									if( empty($new_area) ) 
									{
										$citys = m("dispatch")->getAllNoDispatchAreas($dispatch_data["nodispatchareas"]);
									}
									else 
									{
										$citys = m("dispatch")->getAllNoDispatchAreas($dispatch_data["nodispatchareas_code"], 1);
									}
									if( !empty($citys) && in_array($user_province_code, $citys) )
									{
										$isnoarea = 1;
									}
								}
								else 
								{
									if( empty($new_area) ) 
									{
										$citys = m("dispatch")->getAllNoDispatchAreas();
									}
									else 
									{
										$citys = m("dispatch")->getAllNoDispatchAreas("", 1);
									}
									if( !empty($citys) && in_array($user_province_code, $citys) )
									{
										$isnoarea = 1;
									}
									if( empty($isnoarea) ) 
									{
										$isnoarea = m("dispatch")->checkOnlyDispatchAreas($user_province_code, $dispatch_data);
									}
								}
								//$isnoarea   这玩意又是0
								if( !empty($isnoarea) )
								{
									$isnodispatch = 1;
									$has_goodsid = 0;
									if( !empty($nodispatch_array["goodid"]) && in_array($g["goodsid"], $nodispatch_array["goodid"]) ) 
									{
										$has_goodsid = 1;
									}
									if( $has_goodsid == 0 ) 
									{
										$nodispatch_array["goodid"][] = $g["goodsid"];
										$nodispatch_array["title"][] = $g["title"];
										$nodispatch_array["city"] = $user_province;
									}
								}
							}
							if( !$sendfree && $isnodispatch == 0 ) 
							{
								$areas = unserialize($dispatch_data["areas"]);
								if( $dispatch_data["calculatetype"] == 1 ) 
								{
									$param = $g["total"];
								}
								else 
								{
									$param = $g["weight"] * $g["total"];
								}
								if( array_key_exists($dkey, $dispatch_array) ) 
								{
									$dispatch_array[$dkey]["param"] += $param;
								}
								else 
								{
									$dispatch_array[$dkey]["data"] = $dispatch_data;
									$dispatch_array[$dkey]["param"] = $param;
								}
								if( $seckillinfo && $seckillinfo["status"] == 0 ) 
								{
									if( array_key_exists($dkey, $dispatch_array) ) 
									{
										$dispatch_array[$dkey]["seckillnums"] += $param;
									}
									else 
									{
										$dispatch_array[$dkey]["seckillnums"] = $param;
									}
								}
							}
						}
					}
					else 
					{
						if( $city_express_data["state"] == 1 ) 
						{
							if( !$sendfree ) 
							{
								if( $city_express_data["is_sum"] == 1 ) 
								{
									$dispatch_price += $city_express_data["price"] * $g["total"];
								}
								else 
								{
									if( $dispatch_price < $city_express_data["price"] ) 
									{
										$dispatch_price = $city_express_data["price"];
									}
								}
							}
						}
						else 
						{
							$nodispatch_array["goodid"][] = $g["goodsid"];
							$nodispatch_array["title"][] = $g["title"];
							$nodispatch_array["city"] = $user_province;
						}
					}
				}
			}
		}
		if( $city_express_data["state"] == 1 && $g["dispatchtype"] == 0 && $city_express_data["is_sum"] == 0 && $dispatch_price < $city_express_data["price"] ) 
		{
			$dispatch_price = $city_express_data["price"];
		}
		if( !empty($dispatch_array) ) 
		{
			$dispatch_info = array( );
			foreach( $dispatch_array as $k => $v ) 
			{
				$dispatch_data = $dispatch_array[$k]["data"];
				$param = $dispatch_array[$k]["param"];
				$areas = unserialize($dispatch_data["areas"]);
				if( !empty($address) ) 
				{
					$dprice = m("dispatch")->getCityDispatchPrice($areas, $address, $param, $dispatch_data);
				}
				else 
				{
					$dprice = m("dispatch")->getDispatchPrice($param, $dispatch_data);
				}
				$merchid = $dispatch_data["merchid"];
				$dispatch_merch[$merchid] += $dprice;
				if( 0 < $v["seckillnums"] ) 
				{
					$seckill_dispatchprice += $dprice;
				}
				else 
				{
					$dispatch_price += $dprice;
				}
				$dispatch_info[$dispatch_data["id"]]["price"] += $dprice;
				$dispatch_info[$dispatch_data["id"]]["freeprice"] = intval($dispatch_data["freeprice"]);
			}
			//不包邮
			if( !empty($dispatch_info) && !$sendfree)
			{
				foreach( $dispatch_info as $k => $v ) 
				{
					if( 0 < $v["freeprice"] && $v["freeprice"] <= $v["price"] ) 
					{
						$dispatch_price -= $v["price"];
					}
				}
				if( $dispatch_price < 0 ) 
				{
				    $dispatch_price = 0;
				}else{
				    //如果是模板的话 加上偏远地区的差价
		                    $gareas = explode(';',$g['edareas']);
		                    if(in_array($user_province_code, $gareas) || !empty($member['province']) && in_array($member['province'], $gareas)){
		                        $is_remote = 0;
		                        $dispatch_price += $g['dispatchprice'];
		                    }else{
		                        $is_remote = 1;
		                        $dispatch_price += $g['remote_dispatchprice'] + $g['dispatchprice'];
		                    }
				}
			}
		}
		if( !empty($merch_array) ) 
		{
			foreach( $merch_array as $key => $value ) 
			{
				$merchid = $key;
				if( 0 < $merchid ) 
				{
					$merchset = $value["set"];
					if( !empty($merchset["enoughfree"]) ) 
					{
						if( floatval($merchset["enoughorder"]) <= 0 ) 
						{
							$dispatch_price = $dispatch_price - $dispatch_merch[$merchid];
							$dispatch_merch[$merchid] = 0;
						}
						else 
						{
							if( floatval($merchset["enoughorder"]) <= $merch_array[$merchid]["ggprice"] ) 
							{
								if( empty($merchset["enoughareas"]) ) 
								{
									$dispatch_price = $dispatch_price - $dispatch_merch[$merchid];
									$dispatch_merch[$merchid] = 0;
								}
								else 
								{
									$areas = explode(";", $merchset["enoughareas"]);
									if( !empty($address) ) 
									{
										if( !in_array($address["province"], $areas) )
										{
											$dispatch_price = $dispatch_price - $dispatch_merch[$merchid];
											$dispatch_merch[$merchid] = 0;
										}
									}
									else 
									{
										if( !empty($member["province"]) )
										{
											if( !in_array($member["province"], $areas) )
											{
												$dispatch_price = $dispatch_price - $dispatch_merch[$merchid];
												$dispatch_merch[$merchid] = 0;
											}
										}
										else 
										{
											if( empty($member["province"]) )
											{
												$dispatch_price = $dispatch_price - $dispatch_merch[$merchid];
												$dispatch_merch[$merchid] = 0;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if( $saleset && !empty($saleset["enoughfree"]) ) 
		{
			$saleset_free = 0;
			if( $loop == 0 ) 
			{
				if( floatval($saleset["enoughorder"]) <= 0 ) 
				{
					$saleset_free = 1;
				}
				else 
				{
					if( floatval($saleset["enoughorder"]) <= $realprice - $seckill_payprice ) 
					{
						if( empty($saleset["enoughareas"]) ) 
						{
							$saleset_free = 1;
						}
						else 
						{
							if( empty($new_area) ) 
							{
								$areas = explode(";", trim($saleset["enoughareas"], ";"));
							}
							else 
							{
								$areas = explode(";", trim($saleset["enoughareas_code"], ";"));
							}
							if( !empty($user_city_code) && !in_array($user_city_code, $areas) ) 
							{
								$saleset_free = 1;
							}
						}
					}
				}
			}
			if( $saleset_free == 1 ) 
			{
				$is_nofree = 0;
				$new_goods = array( );
				if( !empty($saleset["goodsids"]) ) 
				{
					foreach( $goods as $k => $v ) 
					{
						if( !in_array($v["goodsid"], $saleset["goodsids"]) ) 
						{
							$new_goods[$k] = $goods[$k];
							unset($goods[$k]);
						}
						else 
						{
							$is_nofree = 1;
						}
					}
				}
				if( $is_nofree == 1 && $loop == 0 ) 
				{
					if( $goods_num == 1 ) 
					{
						$new_data1 = $this->getOrderDispatchPrice($goods, $member, $address, $saleset, $merch_array, $t, 1);
						$dispatch_price = $new_data1["dispatch_price"];
					}
					else 
					{
						$new_data2 = $this->getOrderDispatchPrice($new_goods, $member, $address, $saleset, $merch_array, $t, 1);
						$dispatch_price = $dispatch_price - $new_data2["dispatch_price"];
					}
				}
				else 
				{
					if( $saleset_free == 1 ) 
					{
						$dispatch_price = 0;
					}
				}
			}
		}
		if( $dispatch_price == 0 ) 
		{
			foreach( $dispatch_merch as &$dm ) 
			{
				$dm = 0;
			}
			unset($dm);
		}
		if( !empty($nodispatch_array) && !empty($address) ) 
		{
			$nodispatch = "商品“ ";
			foreach( $nodispatch_array["title"] as $k => $v ) 
			{
				$nodispatch .= $v . ",";
			}
			$nodispatch = trim($nodispatch, ",");
			$nodispatch .= " ”不支持配送到" . $nodispatch_array["city"];
			$nodispatch_array["nodispatch"] = $nodispatch;
			$nodispatch_array["isnodispatch"] = 1;
		}
		$data = array( );
		$data["dispatch_price"] = $dispatch_price + $seckill_dispatchprice;
		$data["dispatch_merch"] = $dispatch_merch;
		$data["nodispatch_array"] = $nodispatch_array;
		$data["seckill_dispatch_price"] = $seckill_dispatchprice;
		$data["city_express_state"] = $city_express_data["state"];
		$data['isdispatcharea'] = $is_remote;
		return $data;
	}
	public function changeParentOrderPrice($parent_order) 
	{
		global $_W;
		$id = $parent_order["id"];
		$item = pdo_fetch("SELECT price,ordersn2,dispatchprice,changedispatchprice FROM " . tablename("ewei_shop_order") . " WHERE id = :id and uniacid=:uniacid", array( ":id" => $id, ":uniacid" => $_W["uniacid"] ));
		if( !empty($item) ) 
		{
			$orderupdate = array( );
			$orderupdate["price"] = $item["price"] + $parent_order["price_change"];
			$orderupdate["ordersn2"] = $item["ordersn2"] + 1;
			$orderupdate["dispatchprice"] = $item["dispatchprice"] + $parent_order["dispatch_change"];
			$orderupdate["changedispatchprice"] = $item["changedispatchprice"] + $parent_order["dispatch_change"];
			if( !empty($orderupdate) ) 
			{
				pdo_update("ewei_shop_order", $orderupdate, array( "id" => $id, "uniacid" => $_W["uniacid"] ));
			}
		}
	}
	public function getOrderCommission($orderid, $agentid = 0) 
	{
		global $_W;
		if( empty($agentid) ) 
		{
			$item = pdo_fetch("select agentid from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid Limit 1", array( "id" => $orderid, ":uniacid" => $_W["uniacid"] ));
			if( !empty($item) ) 
			{
				$agentid = $item["agentid"];
			}
		}
		$level = 0;
		$pc = p("commission");
		if( $pc ) 
		{
			$pset = $pc->getSet();
			$level = intval($pset["level"]);
		}
		$commission1 = 0;
		$commission2 = 0;
		$commission3 = 0;
		$m1 = false;
		$m2 = false;
		$m3 = false;
		if( !empty($level) && !empty($agentid) ) 
		{
			$m1 = m("member")->getMember($agentid);
			if( !empty($m1["agentid"]) ) 
			{
				$m2 = m("member")->getMember($m1["agentid"]);
				if( !empty($m2["agentid"]) ) 
				{
					$m3 = m("member")->getMember($m2["agentid"]);
				}
			}
		}
		$order_goods = pdo_fetchall("select g.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn,og.productsn as option_productsn, og.total,og.price,og.optionname as optiontitle, og.realprice,og.changeprice,og.oldprice,og.commission1,og.commission2,og.commission3,og.commissions,og.diyformdata,og.diyformfields from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where og.uniacid=:uniacid and og.orderid=:orderid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		foreach( $order_goods as &$og ) 
		{
			if( !empty($level) && !empty($agentid) ) 
			{
				$commissions = iunserializer($og["commissions"]);
				if( !empty($m1) ) 
				{
					if( is_array($commissions) ) 
					{
						$commission1 += (isset($commissions["level1"]) ? floatval($commissions["level1"]) : 0);
					}
					else 
					{
						$c1 = iunserializer($og["commission1"]);
						$l1 = $pc->getLevel($m1["openid"]);
						$commission1 += (isset($c1["level" . $l1["id"]]) ? $c1["level" . $l1["id"]] : $c1["default"]);
					}
				}
				if( !empty($m2) ) 
				{
					if( is_array($commissions) ) 
					{
						$commission2 += (isset($commissions["level2"]) ? floatval($commissions["level2"]) : 0);
					}
					else 
					{
						$c2 = iunserializer($og["commission2"]);
						$l2 = $pc->getLevel($m2["openid"]);
						$commission2 += (isset($c2["level" . $l2["id"]]) ? $c2["level" . $l2["id"]] : $c2["default"]);
					}
				}
				if( !empty($m3) ) 
				{
					if( is_array($commissions) ) 
					{
						$commission3 += (isset($commissions["level3"]) ? floatval($commissions["level3"]) : 0);
					}
					else 
					{
						$c3 = iunserializer($og["commission3"]);
						$l3 = $pc->getLevel($m3["openid"]);
						$commission3 += (isset($c3["level" . $l3["id"]]) ? $c3["level" . $l3["id"]] : $c3["default"]);
					}
				}
			}
		}
		unset($og);
		$commission = $commission1 + $commission2 + $commission3;
		return $commission;
	}
	public function checkOrderGoods($orderid) 
	{
		global $_W;
		$uniacid = $_W["uniacid"];
		$openid = $_W["openid"];
		$member = m("member")->getMember($openid, true);
		$flag = 0;
		$msg = "订单中的商品" . "<br/>";
		$uniacid = $_W["uniacid"];
		$ispeerpay = m("order")->checkpeerpay($orderid);
		$item = pdo_fetch("select * from " . tablename("ewei_shop_order") . "  where  id = :id and uniacid=:uniacid limit 1", array( ":id" => $orderid, ":uniacid" => $uniacid ));
		if( (empty($order["isnewstore"]) || empty($order["storeid"])) && empty($order["istrade"]) ) 
		{
			$order_goods = pdo_fetchall("select og.id,g.title, og.goodsid,og.optionid,g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups,g.totalcnf,og.seckill from  " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
			foreach( $order_goods as $data ) 
			{
				if( empty($data["status"]) || !empty($data["deleted"]) ) 
				{
					$flag = 1;
					$msg .= $data["title"] . "<br/> 已下架,不能付款!!";
				}
				$unit = (empty($data["unit"]) ? "件" : $data["unit"]);
				$seckillinfo = plugin_run("seckill::getSeckill", $data["goodsid"], $data["optionid"], true, $_W["openid"]);
				if( $seckillinfo && $seckillinfo["status"] == 0 || !empty($ispeerpay) ) 
				{
				}
				else 
				{
					if( $data["totalcnf"] == 1 ) 
					{
						if( !empty($data["optionid"]) ) 
						{
							$option = pdo_fetch("select id,title,marketprice,goodssn,productsn,stock,`virtual` from " . tablename("ewei_shop_goods_option") . " where id=:id and goodsid=:goodsid and uniacid=:uniacid  limit 1", array( ":uniacid" => $uniacid, ":goodsid" => $data["goodsid"], ":id" => $data["optionid"] ));
							if( !empty($option) && $option["stock"] != -1 && empty($option["stock"]) ) 
							{
								$flag = 1;
								$msg .= $data["title"] . "<br/>" . $option["title"] . " 库存不足!";
							}
						}
						else 
						{
							if( $data["stock"] != -1 && empty($data["stock"]) ) 
							{
								$flag = 1;
								$msg .= $data["title"] . "<br/>库存不足!";
							}
						}
					}
				}
			}
		}
		else 
		{
			if( p("newstore") ) 
			{
				$sql = "select g.id,g.title,ng.gstatus,g.deleted" . " from " . tablename("ewei_shop_order_goods") . " og left join  " . tablename("ewei_shop_goods") . " g  on g.id=og.goodsid and g.uniacid=og.uniacid" . " inner join " . tablename("ewei_shop_newstore_goods") . " ng on ng.goodsid = g.id AND ng.storeid=" . $item["storeid"] . " where og.orderid=:orderid and og.uniacid=:uniacid";
				$list = pdo_fetchall($sql, array( ":uniacid" => $uniacid, ":orderid" => $orderid ));
				if( !empty($list) ) 
				{
					foreach( $list as $k => $v ) 
					{
						if( empty($v["gstatus"]) || !empty($v["deleted"]) ) 
						{
							$flag = 1;
							$msg .= $v["title"] . "<br/>";
						}
					}
					if( $flag == 1 ) 
					{
						$msg .= "已下架,不能付款!";
					}
				}
			}
			else 
			{
				$flag = 1;
				$msg .= "门店歇业,不能付款!";
			}
		}
		$data = array( );
		$data["flag"] = $flag;
		$data["msg"] = $msg;
		return $data;
	}
	public function checkpeerpay($orderid) 
	{
		global $_W;
		$sql = "SELECT p.*,o.openid FROM " . tablename("ewei_shop_order_peerpay") . " AS p JOIN " . tablename("ewei_shop_order") . " AS o ON p.orderid = o.id WHERE p.orderid = :orderid AND p.uniacid = :uniacid AND (p.status = 0 OR p.status=1) AND o.status >= 0 LIMIT 1";
		$query = pdo_fetch($sql, array( ":orderid" => $orderid, ":uniacid" => $_W["uniacid"] ));
		return $query;
	}
	public function peerStatus($param) 
	{
		global $_W;
		if( !empty($param["tid"]) ) 
		{
			$sql = "SELECT id FROM " . tablename("ewei_shop_order_peerpay_payinfo") . " WHERE tid = :tid";
			$id = pdo_fetchcolumn($sql, array( ":tid" => $param["tid"] ));
			if( $id ) 
			{
				return $id;
			}
		}
		return pdo_insert("ewei_shop_order_peerpay_payinfo", $param);
	}
	public function getVerifyCardNumByOrderid($orderid) 
	{
		global $_W;
		$num = pdo_fetchcolumn("select SUM(og.total)  from " . tablename("ewei_shop_order_goods") . " og\r\n\t\t inner join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id\r\n\t\t where og.uniacid=:uniacid  and og.orderid =:orderid and g.cardid>0", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		return $num;
	}
	public function checkisonlyverifygoods($orderid) 
	{
		global $_W;
		$num = pdo_fetchcolumn("select COUNT(1)  from " . tablename("ewei_shop_order_goods") . " og\r\n\t\t inner join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id\r\n\t\t where og.uniacid=:uniacid  and og.orderid =:orderid and g.type<>5", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		$num = intval($num);
		if( 0 < $num ) 
		{
			return false;
		}
		$num2 = pdo_fetchcolumn("select COUNT(1)  from " . tablename("ewei_shop_order_goods") . " og\r\n             inner join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id\r\n             where og.uniacid=:uniacid  and og.orderid =:orderid and g.type=5", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		$num2 = intval($num2);
		if( 0 < $num2 ) 
		{
			return true;
		}
		return false;
	}
	public function checkhaveverifygoods($orderid) 
	{
		global $_W;
		$num = pdo_fetchcolumn("select COUNT(1)  from " . tablename("ewei_shop_order_goods") . " og\r\n\t\t inner join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id\r\n\t\t where og.uniacid=:uniacid  and og.orderid =:orderid and g.type=5", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		$num = intval($num);
		if( 0 < $num ) 
		{
			return true;
		}
		return false;
	}
	public function checkhaveverifygoodlog($orderid) 
	{
		global $_W;
		$num = pdo_fetchcolumn("select COUNT(1)  from " . tablename("ewei_shop_verifygoods_log") . " vl\r\n\t\t inner join " . tablename("ewei_shop_verifygoods") . " v on vl.verifygoodsid = v.id\r\n\t\t where v.uniacid=:uniacid  and v.orderid =:orderid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
		$num = intval($num);
		if( 0 < $num ) 
		{
			return true;
		}
		return false;
	}
	public function countOrdersn($ordersn, $str = "TR") 
	{
		global $_W;
		$count = intval(substr_count($ordersn, $str));
		return $count;
	}
	public function getOrderVirtual($order = array( )) 
	{
		global $_W;
		if( empty($order) ) 
		{
			return false;
		}
		if( empty($order["virtual_info"]) ) 
		{
			return $order["virtual_str"];
		}
		$ordervirtual = array( );
		$virtual_type = pdo_fetch("select fields from " . tablename("ewei_shop_virtual_type") . " where id=:id and uniacid=:uniacid and merchid = :merchid limit 1 ", array( ":id" => $order["virtual"], ":uniacid" => $_W["uniacid"], ":merchid" => $order["merchid"] ));
		if( !empty($virtual_type) ) 
		{
			$virtual_type = iunserializer($virtual_type["fields"]);
			$virtual_info = ltrim($order["virtual_info"], "[");
			$virtual_info = rtrim($virtual_info, "]");
			$virtual_info = explode(",", $virtual_info);
			if( !empty($virtual_info) ) 
			{
				foreach( $virtual_info as $index => $virtualinfo ) 
				{
					$virtual_temp = iunserializer($virtualinfo);
					if( !empty($virtual_temp) ) 
					{
						foreach( $virtual_temp as $k => $v ) 
						{
							$ordervirtual[$index][] = array( "key" => $virtual_type[$k], "value" => $v, "field" => $k );
						}
						unset($k);
						unset($v);
					}
				}
				unset($index);
				unset($virtualinfo);
			}
		}
		return $ordervirtual;
	}
	public function dada_sign($data, $app_secret) 
	{
		ksort($data);
		$args = "";
		foreach( $data as $key => $value ) 
		{
			$args .= $key . $value;
		}
		$args = $app_secret . $args . $app_secret;
		$sign = strtoupper(md5($args));
		return $sign;
	}
	public function dada_bulidRequestParams($data, $app_key, $source_id, $app_secret) 
	{
		$requestParams = array( );
		$requestParams["app_key"] = $app_key;
		$requestParams["source_id"] = $source_id;
		$requestParams["body"] = json_encode($data);
		$requestParams["format"] = "json";
		$requestParams["v"] = "1.0";
		$requestParams["timestamp"] = time();
		$requestParams["signature"] = $this->dada_sign($requestParams, $app_secret);
		return $requestParams;
	}
	public function dada_send($order) 
	{
		global $_W;
		$url = "http://newopen.imdada.cn/api/order/addOrder";
		$cityexpress = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_city_express") . " WHERE uniacid=:uniacid AND merchid=:merchid", array( ":uniacid" => $_W["uniacid"], ":merchid" => 0 ));
		if( !empty($cityexpress) ) 
		{
			$config = unserialize($cityexpress["config"]);
			if( $cityexpress["express_type"] == 1 ) 
			{
				$app_key = $config["app_key"];
				$app_secret = $config["app_secret"];
				$source_id = $config["source_id"];
				$shop_no = $config["shop_no"];
				$city_code = $config["city_code"];
				$receiver = unserialize($order["address"]);
				$location_data = m("util")->geocode($receiver["province"] . $receiver["city"] . $receiver["area"] . $receiver["address"]);
				if( $location_data["status"] == 1 && 0 < $location_data["count"] ) 
				{
					$location = explode(",", $location_data["geocodes"][0]["location"]);
					$data = array( "shop_no" => $shop_no, "city_code" => $city_code, "origin_id" => $order["ordersn"], "info" => $order["remark"], "cargo_price" => $order["price"], "receiver_name" => $receiver["realname"], "receiver_address" => $receiver["province"] . $receiver["city"] . $receiver["area"] . $receiver["address"], "receiver_phone" => $receiver["mobile"], "receiver_lng" => $location[0], "receiver_lat" => $location[1], "is_prepay" => 0, "expected_fetch_time" => time() + 600, "callback" => "http://newopen.imdada.cn/inner/api/order/status/notify" );
					$reqParams = $this->dada_bulidRequestParams($data, $app_key, $source_id, $app_secret);
					load()->func("communication");
					$resp = ihttp_request($url, json_encode($reqParams), array( "Content-Type" => "application/json" ));
					$ret = @json_decode($resp["content"], true);
					if( $ret["code"] == 0 ) 
					{
						return array( "state" => 1, "result" => "发货成功" );
					}
					return array( "state" => 0, "result" => $ret["msg"] );
				}
				return array( "state" => 0, "result" => "获取收件人坐标失败，请检查收件人地址" );
			}
			return array( "state" => 1, "result" => "发货成功" );
		}
	}
	public function CheckoodsStock($orderid = "", $type = 0) 
	{
		global $_W;
		$order = pdo_fetch("select id,ordersn,price,openid,dispatchtype,addressid,carrier,status,isparent,paytype,isnewstore,storeid,istrade,status from " . tablename("ewei_shop_order") . " where id=:id limit 1", array( ":id" => $orderid ));
		if( !empty($order["istrade"]) ) 
		{
			return false;
		}
		if( empty($order["isnewstore"]) ) 
		{
			$newstoreid = 0;
		}
		else 
		{
			$newstoreid = intval($order["storeid"]);
		}
		$param = array( );
		$param[":uniacid"] = $_W["uniacid"];
		if( $order["isparent"] == 1 ) 
		{
			$condition = " og.parentorderid=:parentorderid";
			$param[":parentorderid"] = $orderid;
		}
		else 
		{
			$condition = " og.orderid=:orderid";
			$param[":orderid"] = $orderid;
		}
		$goods = pdo_fetchall("select og.goodsid,og.total,g.totalcnf,og.realprice,g.credit,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal,g.type from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where " . $condition . " and og.uniacid=:uniacid ", $param);
		if( !empty($goods) ) 
		{
			foreach( $goods as $g ) 
			{
				if( 0 < $newstoreid ) 
				{
					$store_goods = m("store")->getStoreGoodsInfo($g["goodsid"], $newstoreid);
					if( empty($store_goods) ) 
					{
						return NULL;
					}
					$g["goodstotal"] = $store_goods["stotal"];
				}
				else 
				{
					$goods_item = pdo_fetch("select total as goodstotal from" . tablename("ewei_shop_goods") . " where id=:id and uniacid=:uniacid limit 1", array( ":id" => $g["goodsid"], ":uniacid" => $_W["uniacid"] ));
					$g["goodstotal"] = $goods_item["goodstotal"];
				}
				$stocktype = 0;
				if( $type == 0 ) 
				{
					if( $g["totalcnf"] == 0 ) 
					{
						$stocktype = -1;
					}
				}
				else 
				{
					if( $type == 1 && $g["totalcnf"] == 1 ) 
					{
						$stocktype = -1;
					}
				}
				if( !empty($stocktype) ) 
				{
					$data = m("common")->getSysset("trade");
					if( !empty($data["stockwarn"]) ) 
					{
						$stockwarn = intval($data["stockwarn"]);
					}
					else 
					{
						$stockwarn = 5;
					}
					if( !empty($g["optionid"]) ) 
					{
						$option = m("goods")->getOption($g["goodsid"], $g["optionid"]);
						if( 0 < $newstoreid ) 
						{
							$store_goods_option = m("store")->getOneStoreGoodsOption($g["optionid"], $g["goodsid"], $newstoreid);
							if( empty($store_goods_option) ) 
							{
								return NULL;
							}
							$option["stock"] = $store_goods_option["stock"];
						}
						if( !empty($option) && $option["stock"] != -1 ) 
						{
							if( $stocktype == -1 && $type == 0 ) 
							{
								$stock = $option["stock"] - $g["total"];
								if( $stock < 0 ) 
								{
									return false;
								}
							}
							else 
							{
								if( $stocktype == -1 && $type == 1 ) 
								{
									$open_redis = function_exists("redis") && !is_error(redis());
									if( $open_redis ) 
									{
										$redis_key = (string) $_W["uniacid"] . "_goods_order_option_stock_" . $option["id"];
										$redis = redis();
										if( $redis->setnx($redis_key, $option["stock"]) ) 
										{
											$totalstock = $redis->get($redis_key);
											$newstock = $totalstock - $g["total"];
											if( $newstock < 0 ) 
											{
												return false;
											}
											$redis->set($redis_key, $newstock);
										}
										else 
										{
											$totalstock = $redis->get($redis_key);
											$newstock = $totalstock - $g["total"];
											if( $newstock < 0 ) 
											{
												return false;
											}
											$redis->set($redis_key, $newstock);
										}
									}
									else 
									{
										return true;
									}
								}
							}
						}
					}
					if( !empty($g["goodstotal"]) && $g["goodstotal"] != -1 ) 
					{
						if( $stocktype == -1 && $type == 0 ) 
						{
							$totalstock = $g["goodstotal"] - $g["total"];
							if( $totalstock < 0 ) 
							{
								return false;
							}
						}
						else 
						{
							if( $stocktype == -1 && $type == 1 ) 
							{
								$open_redis = function_exists("redis") && !is_error(redis());
								if( $open_redis ) 
								{
									$redis_key = (string) $_W["uniacid"] . "_goods_order_stock_" . $g["goodsid"];
									$redis = redis();
									if( $redis->setnx($redis_key, $g["goodstotal"]) ) 
									{
										$totalstock = $redis->get($redis_key);
										$newstock = $totalstock - $g["total"];
										if( $newstock < 0 ) 
										{
											return false;
										}
										$redis->set($redis_key, $newstock);
									}
									else 
									{
										$totalstock = $redis->get($redis_key);
										$newstock = $totalstock - $g["total"];
										if( $newstock < 0 ) 
										{
											return false;
										}
										$redis->set($redis_key, $newstock);
									}
								}
								else 
								{
									return true;
								}
							}
						}
					}
					else 
					{
						if( $g["goodstotal"] == 0 ) 
						{
							$totalstock = 0;
							$totalstock = $g["goodstotal"] - $g["total"];
							if( $totalstock < 0 ) 
							{
								return false;
							}
						}
					}
				}
			}
			return true;
		}
		else 
		{
			return false;
		}
	}

    /**
     * @return array
     */
    public function merchData()
    {
        $merch_plugin = p("merch");
        $merch_data = m("common")->getPluginset("merch");
        if( $merch_plugin && $merch_data["is_openmerch"] )
        {
            $is_openmerch = 1;
        }
        else
        {
            $is_openmerch = 0;
        }
        return array( "is_openmerch" => $is_openmerch, "merch_plugin" => $merch_plugin, "merch_data" => $merch_data );
    }

    /**
     * @param $member
     * @return array
     */
    public function diyformData($member)
    {
        global $_W;
        global $_GPC;
        $diyform_plugin = p("diyform");
        $order_formInfo = false;
        $diyform_set = false;
        $orderdiyformid = 0;
        $fields = array( );
        $f_data = array( );
        if( $diyform_plugin )
        {
            $diyform_set = $_W["shopset"]["diyform"];
            if( !empty($diyform_set["order_diyform_open"]) )
            {
                $orderdiyformid = intval($diyform_set["order_diyform"]);
                if( !empty($orderdiyformid) )
                {
                    $order_formInfo = $diyform_plugin->getDiyformInfo($orderdiyformid);
                    $fields = $order_formInfo["fields"];
                    $f_data = $diyform_plugin->getLastOrderData($orderdiyformid, $member);
                }
            }
        }
        $appDatas = array( );
        return array( "diyform_plugin" => $diyform_plugin, "order_formInfo" => $order_formInfo, "diyform_set" => $diyform_set, "orderdiyformid" => $orderdiyformid, "fields" => $appDatas["fields"], "f_data" => $appDatas["f_data"] );
    }

    /**
     * @param $cardid
     * @param $dispatch_price
     * @param $totalprice
     * @param int $discountprice
     * @param int $isdiscountprice
     * @return array
     */
    public function caculatecard($cardid, $dispatch_price, $totalprice, $discountprice = 0, $isdiscountprice = 0)
    {
        if( empty($cardid) )
        {
            return ['status'=>1,'msg'=>'参数不完整','data'=>[]];
        }
        $plugin_membercard = p("membercard");
        if( !$plugin_membercard )
        {
            return NULL;
        }
        $card = $plugin_membercard->getMemberCard($cardid);
        if( empty($card) )
        {
            return NULL;
        }
        if( $card["isdelete"] )
        {
            return ['status'=>AppError::$CardisDel,'msg'=>'','data'=>[]];
        }
        if( $card["shipping"] )
        {
            $dispatch_price = 0;
        }
        $discount_rate = floatval($card["discount_rate"]);
        if( empty($card["member_discount"]) && $discount_rate == 0 )
        {
            $discount_rate = 10;
        }
        if( 0 < $isdiscountprice && empty($card["discount"]) )
        {
            $totalprice += $isdiscountprice;
            $isdiscountprice = 0;
        }
        else
        {
            if( 0 < $discountprice && empty($card["discount"]) )
            {
                $totalprice += $discountprice;
                $discountprice = 0;
            }
        }
        $carddiscountprice = round($totalprice * (10 - $discount_rate) * 0.1, 2);
        $carddiscount_rate = $discount_rate;
        $totalprice -= $carddiscountprice;
        $return_array = array( );
        $return_array["carddiscount_rate"] = $carddiscount_rate;
        $return_array["carddiscountprice"] = $carddiscountprice;
        $return_array["dispatch_price"] = $dispatch_price;
        $return_array["totalprice"] = $totalprice;
        $return_array["discountprice"] = $discountprice;
        $return_array["isdiscountprice"] = $isdiscountprice;
        $return_array["cardname"] = $card["name"];
        $return_array["cardid"] = $cardid;
        return $return_array;
    }

    /**
     * @param $couponid
     * @param $goodsarr
     * @param $totalprice
     * @param $discountprice
     * @param $isdiscountprice
     * @param int $isSubmit
     * @param array $discountprice_array
     * @param int $merchisdiscountprice
     * @param int $real_price
     * @return array
     */
    public function caculatecoupon($couponid, $goodsarr, $totalprice, $discountprice, $isdiscountprice, $isSubmit = 0, $discountprice_array = array( ), $merchisdiscountprice = 0, $real_price = 0)
    {
        global $_W;
        $openid = $_W["openid"];
        $uniacid = $_W["uniacid"];
        if( empty($goodsarr) )
        {
            return false;
        }
        $sql = "SELECT d.id,d.couponid,c.enough,c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.merchid,c.limitgoodtype,c.limitgoodcatetype,c.limitgoodids,c.limitgoodcateids,c.limitdiscounttype  FROM " . tablename("ewei_shop_coupon_data") . " d";
        $sql .= " left join " . tablename("ewei_shop_coupon") . " c on d.couponid = c.id";
        $sql .= " where d.id=:id and d.uniacid=:uniacid and d.openid=:openid and d.used=0  limit 1";
        $data = pdo_fetch($sql, array( ":uniacid" => $uniacid, ":id" => $couponid, ":openid" => $openid ));
        $merchid = intval($data["merchid"]);
        if( empty($data) )
        {
            return NULL;
        }
        //店主专享商品大于一件禁止购买
        if($data['couponid']==2 && count($goodsarr)>1) return NULL;
        if( is_array($goodsarr) )
        {
            $goods = array( );
            foreach( $goodsarr as $g )
            {
                if( empty($g) )
                {
                    continue;
                }
                if( 0 < $merchid && $g["merchid"] != $merchid )
                {
                    continue;
                }
                $cates = explode(",", $g["cates"]);
                $limitcateids = explode(",", $data["limitgoodcateids"]);
                $limitgoodids = explode(",", $data["limitgoodids"]);
                $pass = 0;
                if( $data["limitgoodcatetype"] == 0 && $data["limitgoodtype"] == 0 )
                {
                    $pass = 1;
                }
                if( $data["limitgoodcatetype"] == 1 )
                {
                    $result = array_intersect($cates, $limitcateids);
                    if( 0 < count($result) )
                    {
                        $pass = 1;
                    }
                }
                if( $data["limitgoodtype"] == 1 )
                {
                    $isin = in_array($g["goodsid"], $limitgoodids);
                    if( $isin )
                    {
                        $pass = 1;
                    }
                }
                if( $pass == 1 )
                {
                    $goods[] = $g;
                }
            }
            $limitdiscounttype = intval($data["limitdiscounttype"]);
            $coupongoodprice = 0;
            $gprice = 0;
            foreach( $goods as $k => $g )
            {
                $gprice = (double) $g["marketprice"] * (double) $g["total"];
                switch( $limitdiscounttype )
                {
                    case 1: $coupongoodprice += $gprice - (double) $g["discountunitprice"] * (double) $g["total"];
                        $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice - (double) $g["discountunitprice"] * (double) $g["total"];
                        if( $g["discounttype"] == 1 )
                        {
                            $isdiscountprice -= (double) $g["isdiscountunitprice"] * (double) $g["total"];
                            $discountprice += (double) $g["discountunitprice"] * (double) $g["total"];
                            if( $isSubmit == 1 )
                            {
                                $totalprice = $totalprice - $g["ggprice"] + $g["price2"];
                                $discountprice_array[$g["merchid"]]["ggprice"] = $discountprice_array[$g["merchid"]]["ggprice"] - $g["ggprice"] + $g["price2"];
                                $goodsarr[$k]["ggprice"] = $g["price2"];
                                $discountprice_array[$g["merchid"]]["isdiscountprice"] -= (double) $g["isdiscountunitprice"] * (double) $g["total"];
                                $discountprice_array[$g["merchid"]]["discountprice"] += (double) $g["discountunitprice"] * (double) $g["total"];
                                if( !empty($data["merchsale"]) )
                                {
                                    $merchisdiscountprice -= (double) $g["isdiscountunitprice"] * (double) $g["total"];
                                    $discountprice_array[$g["merchid"]]["merchisdiscountprice"] -= (double) $g["isdiscountunitprice"] * (double) $g["total"];
                                }
                            }
                        }
                        break;
                    case 2: $coupongoodprice += $gprice - (double) $g["isdiscountunitprice"] * (double) $g["total"];
                        $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice - (double) $g["isdiscountunitprice"] * (double) $g["total"];
                        if( $g["discounttype"] == 2 )
                        {
                            $discountprice -= (double) $g["discountunitprice"] * (double) $g["total"];
                            if( $isSubmit == 1 )
                            {
                                $totalprice = $totalprice - $g["ggprice"] + $g["price1"];
                                $discountprice_array[$g["merchid"]]["ggprice"] = $discountprice_array[$g["merchid"]]["ggprice"] - $g["ggprice"] + $g["price1"];
                                $goodsarr[$k]["ggprice"] = $g["price1"];
                                $discountprice_array[$g["merchid"]]["discountprice"] -= (double) $g["discountunitprice"] * (double) $g["total"];
                            }
                        }
                        break;
                    case 3: $coupongoodprice += $gprice;
                        $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice;
                        if( $g["discounttype"] == 1 )
                        {
                            $isdiscountprice -= (double) $g["isdiscountunitprice"] * (double) $g["total"];
                            if( $isSubmit == 1 )
                            {
                                $totalprice = $totalprice - $g["ggprice"] + $g["price0"];
                                $discountprice_array[$g["merchid"]]["ggprice"] = $discountprice_array[$g["merchid"]]["ggprice"] - $g["ggprice"] + $g["price0"];
                                $goodsarr[$k]["ggprice"] = $g["price0"];
                                if( !empty($data["merchsale"]) )
                                {
                                    $merchisdiscountprice -= $g["isdiscountunitprice"] * (double) $g["total"];
                                    $discountprice_array[$g["merchid"]]["merchisdiscountprice"] -= $g["isdiscountunitprice"] * (double) $g["total"];
                                }
                                $discountprice_array[$g["merchid"]]["isdiscountprice"] -= $g["isdiscountunitprice"] * (double) $g["total"];
                            }
                        }
                        else
                        {
                            if( $g["discounttype"] == 2 )
                            {
                                $discountprice -= (double) $g["discountunitprice"] * (double) $g["total"];
                                if( $isSubmit == 1 )
                                {
                                    $totalprice = $totalprice - $g["ggprice"] + $g["price0"];
                                    $goodsarr[$k]["ggprice"] = $g["price0"];
                                    $discountprice_array[$g["merchid"]]["ggprice"] = $discountprice_array[$g["merchid"]]["ggprice"] - $g["ggprice"] + $g["price0"];
                                    $discountprice_array[$g["merchid"]]["discountprice"] -= (double) $g["discountunitprice"] * (double) $g["total"];
                                }
                            }
                        }
                        break;
                    default: if( $g["discounttype"] == 1 )
                    {
                        $coupongoodprice += $gprice - (double) $g["isdiscountunitprice"] * (double) $g["total"];
                        $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice - (double) $g["isdiscountunitprice"] * (double) $g["total"];
                    }
                    else
                    {
                        if( $g["discounttype"] == 2 )
                        {
                            $coupongoodprice += $gprice - (double) $g["discountunitprice"] * (double) $g["total"];
                            $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice - (double) $g["discountunitprice"] * (double) $g["total"];
                        }
                        else
                        {
                            if( $g["discounttype"] == 0 )
                            {
                                $coupongoodprice += $gprice;
                                $discountprice_array[$g["merchid"]]["coupongoodprice"] += $gprice;
                            }
                        }
                    }
                        break;
                }
            }
            $deduct = (double) $data["deduct"];
            $discount = (double) $data["discount"];
            $backtype = (double) $data["backtype"];
            $deductprice = 0;
            $coupondeduct_text = "";
            if( $real_price )
            {
                $coupongoodprice = $real_price;
            }
            if( 0 < $deduct && $backtype == 0 && 0 < $coupongoodprice )
            {
                if( $coupongoodprice < $deduct )
                {
                    $deduct = $coupongoodprice;
                }
                if( $deduct <= 0 )
                {
                    $deduct = 0;
                }
                $deductprice = $deduct;
                $coupondeduct_text = "优惠券优惠";
                foreach( $discountprice_array as $key => $value )
                {
                    $discountprice_array[$key]["deduct"] = (double) $value["coupongoodprice"] / (double) $coupongoodprice * $deduct;
                }
            }
            else
            {
                if( 0 < $discount && $backtype == 1 )
                {
                    $deductprice = $coupongoodprice * (1 - $discount / 10);
                    if( $coupongoodprice < $deductprice )
                    {
                        $deductprice = $coupongoodprice;
                    }
                    if( $deductprice <= 0 )
                    {
                        $deductprice = 0;
                    }
                    foreach( $discountprice_array as $key => $value )
                    {
                        $discountprice_array[$key]["deduct"] = (double) $value["coupongoodprice"] * (1 - $discount / 10);
                    }
                    if( 0 < $merchid )
                    {
                        $coupondeduct_text = "店铺优惠券折扣(" . $discount . "折)";
                    }
                    else
                    {
                        $coupondeduct_text = "优惠券折扣(" . $discount . "折)";
                    }
                }
            }
        }
        $totalprice -= $deductprice;
        $return_array = array( );
        $return_array["isdiscountprice"] = $isdiscountprice;
        $return_array["discountprice"] = $discountprice;
        $return_array["deductprice"] = $deductprice;
        $return_array["coupongoodprice"] = $coupongoodprice;
        $return_array["coupondeduct_text"] = $coupondeduct_text;
        $return_array["totalprice"] = $totalprice;
        $return_array["discountprice_array"] = $discountprice_array;
        $return_array["merchisdiscountprice"] = $merchisdiscountprice;
        $return_array["couponmerchid"] = $merchid;
        $return_array["goodsarr"] = $goodsarr;
        if($data['couponid']==2) {

            //$return_array["deductprice"] = $totalprice;
        }
        return $return_array;
    }

    /**
     * @param $orderid
     * @param $openid
     * @return array
     */
    public function success($orderid,$openid)
    {
        global $_W;
        global $_GPC;
        //$openid = $_W["openid"];
        $uniacid = $_W["uniacid"];
        $member = m("member")->getMember($openid, true);
        if( empty($orderid) )
        {
            app_error1(AppError::$ParamsError,'',[]);
        }
//        //订单绑定推荐会员
//        if($_GPC['mid'] && !$member['agentid']){
//            $agentmemberInfo = pdo_get('ewei_shop_member', array('id' =>$_GPC['mid']));
//            if($agentmemberInfo)  m('member')->setagent(array('agentopenid'=>trim($agentmemberInfo["openid"]),'openid'=>$member['openid']));
//        }
        $order = pdo_fetch("select * from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and (openid=:openid or user_id = :user_id) limit 1", array( ":id" => $orderid, ":uniacid" => $uniacid, ":openid" => $member['openid'],':user_id'=>$member['id'] ));
        $merchid = $order["merchid"];
        $goods = pdo_fetchall("select og.goodsid,g.cates,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,og.optionname as optiontitle,g.isverify,g.storeids from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on g.id=og.goodsid " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array( ":uniacid" => $uniacid, ":orderid" => $orderid ));
        $address = false;
        if( !empty($order["addressid"]) )
        {
            $address = iunserializer($order["address"]);
            if( !is_array($address) )
            {
                $address = pdo_fetch("select * from  " . tablename("ewei_shop_member_address") . " where id=:id limit 1", array( ":id" => $order["addressid"] ));
            }
        }
        $carrier = @iunserializer($order["carrier"]);
        if( !is_array($carrier) || empty($carrier) )
        {
            $carrier = false;
        }
        $store = false;
        if( !empty($order["storeid"]) )
        {
            if( 0 < $merchid )
            {
                $store = pdo_fetch("select * from  " . tablename("ewei_shop_merch_store") . " where id=:id limit 1", array( ":id" => $order["storeid"] ));
            }
            else
            {
                $store = pdo_fetch("select * from  " . tablename("ewei_shop_store") . " where id=:id limit 1", array( ":id" => $order["storeid"] ));
            }
        }
        $stores = false;
        if( $order["isverify"] )
        {
            $storeids = array( );
            foreach( $goods as $g )
            {
                if( !empty($g["storeids"]) )
                {
                    $storeids = array_merge(explode(",", $g["storeids"]), $storeids);
                }
            }
            if( empty($storeids) )
            {
                if( 0 < $merchid )
                {
                    $stores = pdo_fetchall("select * from " . tablename("ewei_shop_merch_store") . " where  uniacid=:uniacid and merchid=:merchid and status=1 and `type` in (2,3)", array( ":uniacid" => $_W["uniacid"], ":merchid" => $merchid ));
                }
                else
                {
                    $stores = pdo_fetchall("select * from " . tablename("ewei_shop_store") . " where  uniacid=:uniacid and status=1 and `type` in (2,3)", array( ":uniacid" => $_W["uniacid"] ));
                }
            }
            else
            {
                if( 0 < $merchid )
                {
                    $stores = pdo_fetchall("select * from " . tablename("ewei_shop_merch_store") . " where id in (" . implode(",", $storeids) . ") and uniacid=:uniacid and merchid=:merchid and status=1", array( ":uniacid" => $_W["uniacid"], ":merchid" => $merchid ));
                }
                else
                {
                    $stores = pdo_fetchall("select * from " . tablename("ewei_shop_store") . " where id in (" . implode(",", $storeids) . ") and uniacid=:uniacid and status=1", array( ":uniacid" => $_W["uniacid"] ));
                }
            }
        }
        $text = "";
        if( !empty($address) )
        {
            $text = "您的包裹整装待发";
        }
        if( !empty($order["dispatchtype"]) && empty($order["isverify"]) )
        {
            $text = "您可以到您选择的自提点取货了";
        }
        if( !empty($order["isverify"]) )
        {
            $text = "您可以到适用门店去使用了";
        }
        if( !empty($order["virtual"]) )
        {
            $text = "您购买的商品已自动发货";
        }
        if( !empty($order["isvirtual"]) && empty($order["virtual"]) )
        {
            if( !empty($order["isvirtualsend"]) )
            {
                $text = "您购买的商品已自动发货";
            }
            else
            {
                $text = "您已经支付成功";
            }
        }
        if( $_GPC["result"] == "seckill_refund" )
        {
            $icon = "e75a";
        }
        else
        {
            if( !empty($address) )
            {
                $icon = "e623";
            }
            if( !empty($order["dispatchtype"]) && empty($order["isverify"]) )
            {
                $icon = "e7b9";
            }
            if( !empty($order["isverify"]) )
            {
                $icon = "e7b9";
            }
            if( !empty($order["virtual"]) )
            {
                $icon = "e7a1";
            }
            if( !empty($order["isvirtual"]) && empty($order["virtual"]) )
            {
                if( !empty($order["isvirtualsend"]) )
                {
                    $icon = "e7a1";
                }
                else
                {
                    $icon = "e601";
                }
            }
        }
        $seckill_color = "";
        if( 0 < $order["seckilldiscountprice"] )
        {
            $where = " WHERE uniacid=:uniacid AND type = 5";
            $params = array( ":uniacid" => $_W["uniacid"] );
            $page = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_wxapp_page") . $where . " LIMIT 1 ", $params);
            if( !empty($page) )
            {
                $data = base64_decode($page["data"]);
                $diydata = json_decode($data, true);
                $seckill_color = $diydata["page"]["seckill"]["color"];
            }
        }
        $result = array("goods"=>$goods[0], "order" => array( "id" => $orderid,  "status" => ($order["paytype"] == 3 ? "订单提交支付" : "订单支付成功"), "text" => $text, "price" => $order["price"] ), "paytype" => ($order["paytype"] == 3 ? "需到付" : "实付金额".$order["price"]), "carrier" => $carrier, "address" => $address );
        $result['goods'] = $goods[0];
        app_error1(0,'',$result);
    }

    /**
     * @param $orderid
     * @param $user_id
     * @return array|bool
     */
    public function order_pay($orderid,$user_id)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $member = m('member')->getMember($user_id);
        //查找订单信息  并且 报错订单信息
        $order = pdo_fetch("select * from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and (openid=:openid or user_id = :user_id) limit 1", array( ":id" => $orderid, ":uniacid" => $uniacid, ":openid" => $member['openid'] , ':user_id'=>$member['id'] ));
        if( empty($order) )
        {
            return ['status'=>AppError::$OrderNotFound,'msg'=>'','data'=>[]];
        }
        //订单状态  已取消
        if( $order["status"] == -1 )
        {
            return ['status'=>AppError::$OrderCannotPay,'msg'=>'','data'=>[]];
        }
        else
        {
            //订单状态已支付
            if( 1 <= $order["status"] )
            {
                return ['status'=>AppError::$OrderAlreadyPay,'msg'=>'','data'=>[]];
            }
        }
        //订单预支付记录
        $log = pdo_fetch("SELECT * FROM " . tablename("core_paylog") . " WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1", array( ":uniacid" => $uniacid, ":module" => "ewei_shopv2", ":tid" => $order["ordersn"] ));
        if( !empty($log) && $log["status"] != "0" )
        {
            return ['status'=>AppError::$OrderAlreadyPay,'msg'=>'','data'=>[]];
        }
        if( !empty($log) && $log["status"] == "0" )
        {
            pdo_delete("core_paylog", array( "plid" => $log["plid"] ));
            $log = NULL;
        }
        //lihanwen   店主优惠券
        if($order['couponid']>0){
            $coupon_info = pdo_fetch("SELECT couponid FROM " . tablename("ims_ewei_shop_coupon_data") . " WHERE `uniacid`=:uniacid AND `id`=:id limit 1", array( ":uniacid" => $uniacid, ":id" => $order['couponid']));
            if($coupon_info['couponid']==2){//店主会员免费商品
                $order["price"] = 0.00;
            }
        }
        //如果订单的预支付记录不存在 插入订单
        if( empty($log) )
        {
            $log = array( "uniacid" => $uniacid, "openid" => $member["openid"], "module" => "ewei_shopv2", "tid" => $order["ordersn"], "fee" => $order["price"], "status" => 0 );
            pdo_insert("core_paylog", $log);
            $plid = pdo_insertid();
        }
        return $order;
    }

    /**
     * @param $orderid
     * @param $user_id
     * @param string $type
     * @param string $iswxapp
     * @return array
     */
    public function order_complete($orderid,$user_id,$type = "credit",$iswxapp)
    {
        $set = m("common")->getSysset(array( "shop", "pay" ));
        $set["pay"]["weixin"] = (!empty($set["pay"]["weixin_sub"]) ? 1 : $set["pay"]["weixin"]);
        $set["pay"]["weixin_jie"] = (!empty($set["pay"]["weixin_jie_sub"]) ? 1 : $set["pay"]["weixin_jie"]);
        global $_W;
        $uniacid = $_W['uniacid'];
        $member = m('member')->getMember($user_id);
        //查找订单信息
        $order = pdo_fetch("select * from " . tablename("ewei_shop_order") . " where id=:id and uniacid=:uniacid and (openid=:openid or user_id = :user_id) limit 1", array( ":id" => $orderid, ":uniacid" => $uniacid, ":openid" => $member['openid'],':user_id'=>$member['id'] ));
        if( empty($order) )
        {
            return ['status'=>AppError::$OrderNotFound,'msg'=>'','data'=>[]];
        }
        //订单状态大于1  不应该已经是  已支付吗
        if( 1 <= $order["status"] )
        {
            m('order')->success($orderid,$user_id);
        }
        //
        $log = pdo_fetch("SELECT * FROM " . tablename("core_paylog") . " WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid limit 1", array( ":uniacid" => $uniacid, ":module" => "ewei_shopv2", ":tid" => $order["ordersn"] ));
        if( empty($log) )
        {
            return ['status'=>AppError::$OrderPayFail,'msg'=>'','data'=>[]];
        }
        $order_goods = pdo_fetchall("select og.id,g.title, og.goodsid,og.optionid,g.total as stock,og.total as buycount,g.status,g.deleted,g.maxbuy,g.usermaxbuy,g.istime,g.timestart,g.timeend,g.buylevels,g.buygroups,g.totalcnf from  " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_goods") . " g on og.goodsid = g.id " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array( ":uniacid" => $_W["uniacid"], ":orderid" => $orderid ));
        foreach( $order_goods as $data )
        {
            if( empty($data["status"]) || !empty($data["deleted"]) )
            {
                return ['status'=>AppError::$OrderPayFail, 'msg'=>$data["title"]."<br/> 已下架!",'data'=>[]];
            }
            $unit = (empty($data["unit"]) ? "件" : $data["unit"]);
            if( 0 < $data["minbuy"] && $data["buycount"] < $data["minbuy"] )
            {
                return ['status'=>AppError::$OrderCreateMinBuyLimit, 'msg'=>$data["title"] . "<br/> " . $data["min"] . $unit . "起售!",'data'=>[]];
            }
            if( 0 < $data["maxbuy"] && $data["maxbuy"] < $data["buycount"] )
            {
                return ['status'=>AppError::$OrderCreateOneBuyLimit, 'msg'=>$data["title"] . "<br/> 一次限购 " . $data["maxbuy"] . $unit . "!",'data'=>[]];
            }
            if( 0 < $data["usermaxbuy"] )
            {
                $order_goodscount = pdo_fetchcolumn("select ifnull(sum(og.total),0)  from " . tablename("ewei_shop_order_goods") . " og " . " left join " . tablename("ewei_shop_order") . " o on og.orderid=o.id " . " where og.goodsid=:goodsid and  o.status>=1 and (o.openid=:openid or o.user_id = :user_id)  and og.uniacid=:uniacid ", array( ":goodsid" => $data["goodsid"], ":uniacid" => $uniacid, ":openid" => $member['openid'] ,':user_id'=>$member['id'] ));
                if( $data["usermaxbuy"] <= $order_goodscount )
                {
                    return ['status'=>AppError::$OrderCreateMaxBuyLimit, 'msg'=>$data["title"] . "<br/> 最多限购 " . $data["usermaxbuy"] . $unit,'data'=>[]];
                }
            }
            //限购秒杀商品
            if( $data["istime"] == 1 )
            {
                //限购秒杀未开始
                if( time() < $data["timestart"] )
                {
                    return ['status'=>AppError::$OrderCreateTimeNotStart, 'msg'=>$data["title"] . "<br/> 限购时间未到!",'data'=>[]];
                }
                //限购秒杀一结束
                if( $data["timeend"] < time() )
                {
                    return ['status'=>AppError::$OrderCreateTimeEnd, 'msg'=>$data["title"] . "<br/> 限购时间已过!",'data'=>[]];
                }
            }
            //判断会员等级是否有购买权限
            if( $data["buylevels"] != "" )
            {
                $buylevels = explode(",", $data["buylevels"]);
                if( !in_array($member["agentlevel"], $buylevels) )
                {
                    return ['status'=>AppError::$OrderCreateMemberLevelLimit, 'msg'=>"您的会员等级无法购买<br/>" . $data["title"] . "!",'data'=>[]];
                }
            }
            //判断会员分组购买权限
            if( $data["buygroups"] != "" )
            {
                $buygroups = explode(",", $data["buygroups"]);
                if( !in_array($member["groupid"], $buygroups) )
                {
                    return ['status'=>AppError::$OrderCreateMemberGroupLimit, 'msg'=>"您所在会员组无法购买<br/>" . $data["title"] . "!", 'data'=>[]];
                }
            }
            // totalcnf  减库存方式 0 拍下减库存 1 付款减库存 2 永不减库存
            if( $data["totalcnf"] == 1 )
            {
                //如果是支付立减  如果有属性id  查找该属性的库存
                if( !empty($data["optionid"]) )
                {
                    $option = pdo_fetch("select id,title,marketprice,goodssn,productsn,stock,`virtual` from " . tablename("ewei_shop_goods_option") . " where id=:id and goodsid=:goodsid and uniacid=:uniacid  limit 1", array( ":uniacid" => $uniacid, ":goodsid" => $data["goodsid"], ":id" => $data["optionid"] ));
                    if( !empty($option) && $option["stock"] != -1 && empty($option["stock"]) )
                    {
                        return ['status'=>AppError::$OrderCreateStockError, 'msg'=>$data["title"] . "<br/>" . $option["title"] . " 库存不足!", 'data'=>[]];
                    }
                }
                else
                {
                    //如果是库存-1  如果 没有库存 报错
                    if( $data["stock"] != -1 && empty($data["stock"]) )
                    {
                        return ['status'=>AppError::$OrderCreateStockError, 'msg'=>$data["title"] . "<br/>" . " 库存不足!", 'data'=>[]];
                    }
                }
            }
        }
        //礼包商品的支付
        if(pdo_exists('ewei_shop_gift_log',['order_sn'=>$order['ordersn']])){
            $gift = pdo_get('ewei_shop_gift_log',['order_sn'=>$order['ordersn']]);
            //查看这个礼包记录的周始末
            $week = m('util')->week($gift['createtime']);
            //如果当前时间在周始末  则可以领取  并更新状态为2 领取成功 如果不在也更新状态  为0 取消
            if(time() >= $week['start'] && $week['end'] >= time()){
                pdo_update('ewei_shop_gift_log',['status'=>2],['order_sn'=>$order['ordersn']]);
            }else{
                pdo_update('ewei_shop_gift_log',['status'=>0],['order_sn'=>$order['ordersn']]);
                app_error(AppError::$OrderPayFail, "该订单是".$week['start']."--".$week['end']."礼包的商品，已经过了购买期");
            }
        }
        //货到付款
        if( $type == "cash" )
        {
            //货到付款没有开启
            if( empty($set["pay"]["cash"]) )
            {
                return ['status'=>AppError::$OrderPayFail, 'msg'=>"未开启货到付款",'data'=>[]];
            }
            m("order")->setOrderPayType($order["id"], 3);
            $ret = array( );
            $ret["result"] = "success";
            $ret["type"] = "cash";
            $ret["from"] = "return";
            $ret["tid"] = $log["tid"];
            $ret["user"] = $order["openid"];
            $ret["fee"] = $order["price"];
            $ret["weid"] = $_W["uniacid"];
            $ret["uniacid"] = $_W["uniacid"];
            $res = $pay_result = m("order")->payResult($ret);
            $result = m("notice")->sendOrderMessage($orderid);
            if($res && $result) return ['status'=>0,'msg'=>'','data'=>[]];
            //m('order')->success($orderid,$user_id);
        }
        $ps = array( );
        $ps["tid"] = $log["tid"];
        $ps["user"] = $member['openid'];
        $ps["fee"] = $log["fee"];
        $ps["title"] = $log["title"];
        //如果支付金额小于0  则报错  金额错误
        if( $ps["fee"] < 0 )
        {
            return ['status'=>AppError::$OrderPayFail, 'msg'=>"金额错误", 'data'=>[]];
        }
        //余额支付
        if( $type == "credit" )
        {
            //未开始余额支付
            if( empty($set["pay"]["credit"]) && 0 < $ps["fee"] )
            {
                return ['status'=>AppError::$OrderPayFail, 'msg'=>"未开启余额支付", 'data'=>[]];
            }
            //当前用户的余额
            $credits = $member["credit2"];
            //余额 和 订单金额 对比
            if( $credits < $ps["fee"] )
            {
                return ['status'=>AppError::$OrderPayFail, 'msg'=>"余额不足,请充值",'data'=>[]];
            }
            $fee = floatval($ps["fee"]);
            $shopset = m("common")->getSysset("shop");
            $result = m("member")->setCredit($member['openid'], "credit2", 0 - $fee, array( $_W["member"]["uid"], $shopset["name"] . "APP 消费余额" . $fee),5 );
            m('pay')->creditpay_log($member['openid'], $fee, $orderid,'credit');
            //支付失败  及  失败原因
            if( is_error($result) )
            {
                return ['status'=>AppError::$OrderPayFail, 'msg'=>$result["message"] ,'data'=>[]];
            }
            $record = array( );
            $record["status"] = "1";
            $record["type"] = "credit2";
            pdo_update("core_paylog", $record, array( "plid" => $log["plid"] ));
            $ret = array( );
            $ret["result"] = "success";
            $ret["type"] = $log["type"];
            $ret["from"] = "return";
            $ret["tid"] = $log["tid"];
            $ret["user"] = $log["openid"];
            $ret["fee"] = $log["fee"];
            $ret["weid"] = $log["weid"];
            $ret["uniacid"] = $log["uniacid"];
            @session_start();
            $_SESSION[EWEI_SHOPV2_PREFIX . "_order_pay_complete"] = 1;
            $res = m("order")->setOrderPayType($order["id"], 1);
            $pay_result = m("order")->payResult($ret);
            if($res && $pay_result) return ['status'=>0,'msg'=>'','data'=>[]];
            //m('order')->success($orderid,$user_id);
        }
        else {
            //RVC 支付
            if ($type == "RVC") {
                //用户RVC余额
                $credits = $member["RVC"];
                if ($credits < $ps["fee"]) {
                    return ['status'=>AppError::$OrderPayFail, 'msg'=>"RVC不足,请充值",'data'=>[]];
                }
                $fee = floatval($ps["fee"]);
                $shopset = m("common")->getSysset("shop");
                $result = m("member")->setCredit($member['openid'], "RVC", 0 - $fee, array($_W["member"]["uid"], $shopset["name"] . "APP 消费 RVC" . $fee),5);
                m('pay')->creditpay_log($member['openid'], $fee, $orderid,'RVC');
                if (is_error($result)) {
                    return ['status'=>AppError::$OrderPayFail, 'msg'=>$result["message"],'data'=>[]];
                }
                $record = array();
                $record["status"] = "1";
                $record["type"] = "RVC";
                pdo_update("core_paylog", $record, array("plid" => $log["plid"]));
                $ret = array();
                $ret["result"] = "success";
                $ret["type"] = $log["type"];
                $ret["from"] = "return";
                $ret["tid"] = $log["tid"];
                $ret["user"] = $log["openid"];
                $ret["fee"] = $log["fee"];
                $ret["weid"] = $log["weid"];
                $ret["uniacid"] = $log["uniacid"];
                @session_start();
                $_SESSION[EWEI_SHOPV2_PREFIX . "_order_pay_complete"] = 1;
                $res = m("order")->setOrderPayType($order["id"], 6);
                $pay_result = m("order")->payResult($ret);
                if($res && $pay_result) return ['status'=>0,'msg'=>'','data'=>[]];
                //m('order')->success($orderid,$user_id);
            } else {
                if ($type == "wechat") {
                    if (empty($set["pay"]["wxapp"]) && $iswxapp) {
                        return ['status'=>AppError::$OrderPayFail, 'msg'=>"未开启微信支付",'data'=>[]];
                    }
                    $ordersn = $order["ordersn"];
                    if (!empty($order["ordersn2"])) {
                        $ordersn .= "GJ" . sprintf("%02d", $order["ordersn2"]);
                    }
                    //订单查询接口
                    $payquery = m('pay')->isWeixinPay($ordersn, $order["price"]);
                    if (!is_error($payquery)) {
                        $record = array();
                        $record["status"] = "1";
                        $record["type"] = "wechat";
                        pdo_update("core_paylog", $record, array("plid" => $log["plid"]));
                        m("order")->setOrderPayType($order["id"], 21);
                        $ret = array();
                        $ret["result"] = "success";
                        $ret["type"] = "wechat";
                        $ret["from"] = "return";
                        $ret["tid"] = $log["tid"];
                        $ret["user"] = $log["openid"];
                        $ret["fee"] = $log["fee"];
                        $ret["weid"] = $log["weid"];
                        $ret["uniacid"] = $log["uniacid"];
                        //$ret["deduct"] = intval($_GPC["deduct"]) == 1;
                        $pay_result = m("order")->payResult($ret);
                        @session_start();
                        $_SESSION[EWEI_SHOPV2_PREFIX . "_order_pay_complete"] = 1;
                        $res = pdo_update("ewei_shop_order", array("apppay" => 2), array("id" => $order["id"]));
                        //m('order')->success($orderid,$user_id);
                        if($res && $pay_result) return ['status'=>0,'msg'=>'','data'=>[]];
                    }
                    return ['status'=>AppError::$OrderPayFail,'msg'=>'','data'=>[]];
                } else {
                    if ($type == "alipay") {
                        if (empty($set["pay"]["app_alipay"])) {
                            return ['status'=>AppError::$OrderPayFail, 'msg'=>"未开启支付宝支付",'data'=>[]];
                        }
                        $sec = m("common")->getSec();
                        $sec = iunserializer($sec["sec"]);
                        //支付宝公钥
                        //$public_key = $sec["nativeapp"]["alipay"]["public_key"];
                        $public_key_rsa2 = $sec["app_alipay"]["public_key_rsa2"];
                        //if (empty($public_key)) {
                        if (empty($public_key_rsa2)) {
                            return ['status'=>AppError::$OrderPayFail, 'msg'=>"支付宝公钥为空",'data'=>[]];
                        }
                        //$alidata = htmlspecialchars_decode($alidata);
                        //$alidata = json_decode($alidata, true);
                        $alidata = explode('&',$alidata);
                        $ali_param = [];
                        foreach ($alidata as $ali){
                            $ali_param[] = explode('=',$ali);
                        }
                        $params = array_column($ali_param,'1','0');
                        $newalidata['app_id'] = $params['app_id'];
                        $newalidata['biz_content'] = $params['biz_content'];
                        $newalidata['charset'] = $params['charset'];
                        $newalidata['format'] = $params['format'];
                        $newalidata['method'] = $params['method'];
                        $newalidata['notify_url'] = $params['notify_url'];
                        $newalidata['timestamp'] = $params['timestamp'];
                        $newalidata["sign_type"] = $params["sign_type"];
                        $newalidata["sign"] = $params["sign"];
                        //$alisign = m("finance")->RSAVerify($newalidata, $public_key, false, true);
                        $alisign = m("finance")->RSAVerify($newalidata, $public_key_rsa2, false, true);
                        if ($alisign) {
                            $record = array();
                            $record["status"] = "1";
                            $record["type"] = "alipay";
                            pdo_update("core_paylog", $record, array("plid" => $log["plid"]));
                            $ret = array();
                            $ret["result"] = "success";
                            $ret["type"] = "alipay";
                            $ret["from"] = "return";
                            $ret["tid"] = $log["tid"];
                            $ret["user"] = $log["openid"];
                            $ret["fee"] = $log["fee"];
                            $ret["weid"] = $log["weid"];
                            $ret["uniacid"] = $log["uniacid"];
                            //$ret["deduct"] = intval($_GPC["deduct"]) == 1;
                            $res = m("order")->setOrderPayType($order["id"], 22);
                            $pay_result = m("order")->payResult($ret);
                            $result = pdo_update("ewei_shop_order", array("apppay" => 2), array("id" => $order["id"]));
                            if($res && $pay_result && $result) return ['status'=>0,'msg'=>'','data'=>[]];
                            //m('order')->success($order["id"],$user_id);
                        }
                    }
                }
            }
        }
    }
}
?>