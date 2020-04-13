<?php
class Credits_EweiShopV2Model
{
	public function get_sum_credit($type,$openid){
		$member = m('member')->getMember($openid);
		$addwhere='';
		if (empty($type)){
			return 0;
		}elseif ($type==1){
			//好友助力
			$addwhere.=" and (remark_type=1 or remark_type=2)";
			
		}elseif ($type==2){
			$addwhere.=" and remark_type=3";
		}elseif ($type==3){
			//步数兑换
			$addwhere.=" and remark_type=4";
		}elseif ($type==4){
			//订单消费
			$addwhere.=" and remark_type = 5";
		}

		$condition = " and (openid=:openid or user_id = :user_id) and credittype=:credittype and module=:module   ".$addwhere;
		$params = array(':openid' => $member['openid'], ':user_id' => $member['id'], ':credittype' => 'credit1', ':module' => 'ewei_shopv2');
		$sum = pdo_fetchcolumn('select sum(a) from (select num as a from ' . tablename('mc_credits_record') . ' where 1 ' . $condition.') as b', $params);
		return $sum;
	}
	
	public function get_sum_creditdiscount($type,$openid){
        $member = m('member')->getMember($openid);
	    $addwhere='';
	    if (empty($type)){
	        return 0;
	    }elseif ($type==1){
	        //好友助力
	        $addwhere.=" and (remark_type=1 or remark_type=2)";
	        
	    }elseif ($type==2){
	        $addwhere.=" and remark_type=3";
	    }elseif ($type==3){
	        //步数兑换
	        $addwhere.=" and remark_type=4";
	    }elseif ($type==4){
	        //订单消费
	        $addwhere.=" and remark_type = 5 ";
	    }
	    
	    $condition = " and (openid=:openid or user_id = :user_id) and credittype=:credittype and module=:module   ".$addwhere;
	    $params = array(':openid' => $member['openid'], ':user_id' => $member['id'], ':credittype' => 'credit3', ':module' => 'ewei_shopv2');
	    $sum = pdo_fetchcolumn('select sum(a) from (select num as a from ' . tablename('mc_credits_record') . ' where 1 ' . $condition.') as b', $params);
	    return $sum;
	}

}
?>
