<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';
class Comment_EweiShopV2Page extends AppMobilePage
{
	public function __construct()
	{
		parent::__construct();
		$trade = m('common')->getSysset('trade');

		if (!empty($trade['closecomment'])) {
			app_error(AppError::$OrderCanNotComment);
		}
	}

	public function main()
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$openid = $_GPC['openid'];
		$orderid = intval($_GPC['id']);
		if ($_GPC["type"]==1){
		    $member_id=m('member')->getLoginToken($openid);
		    if ($member_id==0){
		        apperror(1,"无此用户");
		    }
		    $openid=$member_id;
		}
		$member=m("member")->getMember($openid);
		if (empty($member)){
		    apperror(1,"无此用户");
		}
		
		$order = pdo_fetch('select id,status,iscomment,merchid from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and (openid=:openid or user_id=:user_id) limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $member["openid"],':user_id'=>$member["id"]));

		if (empty($order)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单不存在");
		    }else{
			app_error(AppError::$OrderNotFound);
		    }
		}

		if ($order['status'] != 3 && $order['status'] != 4) {
		    if ($_GPC["type"]==1){
		        apperror(1,"订单未收货，不可评价");
		    }else{
			app_error(AppError::$OrderCanNotComment, '订单未收货，不能评价!');
		    }
			
		}

		if (2 <= $order['iscomment']) {
		    if ($_GPC["type"]==1){
		        apperror(1,"已评价");
		    }else{
			app_error(AppError::$OrderCanNotComment, '您已经评价过了!');
		    }
		}

		$goods = pdo_fetchall('select og.id,og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle,g.ccate from ' . tablename('ewei_shop_order_goods') . ' og ' . ' left join ' . tablename('ewei_shop_goods') . ' g on g.id=og.goodsid ' . ' left join ' . tablename('ewei_shop_goods_option') . ' o on o.id=og.optionid ' . ' where og.orderid=:orderid and og.uniacid=:uniacid and og.status!=-1', array(':uniacid' => $uniacid, ':orderid' => $orderid));
		$goods = set_medias($goods, 'thumb');
		foreach ($goods as $k=>$v){
		    if ($v["ccate"]!=0){
		        $cate=pdo_get("ewei_shop_category",array("id"=>$v["ccate"]));
		        if ($cate["label"]){
 		        $label=explode(",", $cate["label"]);
 		        foreach ($label as $kk=>$v){
 		            $goods[$k]["label"][$kk]["name"]=$v;
 		        }
		        }else{
		         $goods[$k]["label"]=array();
		        }
		    }else{
		        $goods[$k]["label"]=array();
		    }
		}
		//获取店铺名称
		
		$merch=pdo_get("ewei_shop_merch_user",array("id"=>$order["merchid"]));
		
		if ($_GPC["type"]==1){
		    $res["order"]=$order;
		    $res["goods"]=$goods;
		    $res["shopname"]=$merch?$merch["merchname"]:"跑库自营";
		    apperror(0,"",$res);
		}else{
		    app_json(array('order' => $order, 'goods' => $goods, 'shopname' => $merch?$merch["merchname"]:"跑库自营"));
		}
	}

	public function submit()
	{
		global $_W;
		global $_GPC;
		$openid = $_GPC['openid'];
		$uniacid = $_W['uniacid'];
		$orderid = intval($_GPC['orderid']);
		if ($_GPC["type"]==1){
		    $member_id=m('member')->getLoginToken($openid);
		    if ($member_id==0){
		        apperror(1,"无此用户");
		    }
		    $openid=$member_id;
		}
		$member = m('member')->getMember($openid);
		$order = pdo_fetch('select id,status,iscomment from ' . tablename('ewei_shop_order') . ' where id=:id and uniacid=:uniacid and (openid=:openid or user_id=:user_id) limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $member["openid"],":user_id"=>$member["id"]));

		if (empty($order)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"不存在该订单");
		    }else{
			app_error(AppError::$OrderNotFound);
		    }
		}

		
		$comments = $_GPC['comments'];
	

		if (is_string($comments)) {
			$comments_string = htmlspecialchars_decode(str_replace('\\', '', $comments));
			$comments = @json_decode($comments_string, true);
		}

		if (!is_array($comments)) {
		    if ($_GPC["type"]==1){
		        apperror(1,"数据出错,请重试!");
		    }else{
			app_error(AppError::$SystemError, '数据出错,请重试!');
		    }
		}

		$trade = m('common')->getSysset('trade');

		if (!empty($trade['commentchecked'])) {
			$checked = 0;
		}
		else {
			$checked = 1;
		}
//        apperror(1,"",$comments);die;
		foreach ($comments as $c) {
			$old_c = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_order_comment') . ' where uniacid=:uniacid and orderid=:orderid and goodsid=:goodsid limit 1', array(':uniacid' => $_W['uniacid'], ':goodsid' => $c['goodsid'], ':orderid' => $orderid));

			if (empty($old_c)) {
			    $comment = array('uniacid' => $uniacid, 'orderid' => $orderid, 'goodsid' => $c['goodsid'], 'level' => $c['level'], 'content' => trim($c['content']), 'images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()), 'openid' => $member["openid"], 'user_id'=>$member["id"],'nickname' => $member['nickname'], 'headimgurl' => $member['avatar'], 'createtime' => time(), 'checked' => $checked,'label'=>trim($c["label"]),'deliverry_service'=>intval($_GPC["deliverry_service"]),'service_attitude'=>intval($_GPC["service_attitude"]),"anonymous"=>$c["anonymous"]);
				pdo_insert('ewei_shop_order_comment', $comment);
			}
			else {
				$comment = array('append_content' => trim($c['content']), 'append_images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()), 'replychecked' => $checked);
				pdo_update('ewei_shop_order_comment', $comment, array('uniacid' => $_W['uniacid'], 'goodsid' => $c['goodsid'], 'orderid' => $orderid));
			}
		}

		if ($order['iscomment'] <= 0) {
			$d['iscomment'] = 1;
		}
		else {
			$d['iscomment'] = 2;
		}

		pdo_update('ewei_shop_order', $d, array('id' => $orderid, 'uniacid' => $uniacid));
		if ($_GPC["type"]==1){
		    apperror(0,"成功");
		}else{
		app_json();
		}
	}
}

?>
