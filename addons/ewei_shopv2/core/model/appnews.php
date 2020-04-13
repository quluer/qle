<?php
if( !defined("IN_IA") )
{
    exit( "Access Denied" );
}
class Appnews_EweiShopV2Model
{
    //判断用户登录信息
    public function member($openid,$type){
        if ($type==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                app_error(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (empty($member)){
            return false;
        }else {
            if (!$member["openid"]){
                $member["openid"]=0;
            }
            return $member;
        }
    }
   //获取图片
   public function img($data){
       preg_match_all('/<img.*?src="(.*?)".*?>/is',$data,$array);
       return $array[1];
   }
   //拼团列表
   public function group_list($goods_id,$first,$total){
       $good=pdo_fetch("select id,ccate,title,freight,thumb_url,price,groupsprice,single,singleprice,groupnum,content,more_spec,merchid,gid from ".tablename("ewei_shop_groups_goods")." where id=:goods_id and status=1 and deleted=0",array(":goods_id"=>$goods_id));
       $group=pdo_fetchall("select * from ".tablename("ewei_shop_groups_order")." where goodid=:goodid and status=1 and success=0 and heads=1 and is_team=1 and endtime>:endtime order by createtime desc limit ".$first.",".$total,array(":goodid"=>$goods_id,":endtime"=>time()));
//         var_dump($group);
       $list=array();
       foreach ($group as $k=>$v){
           $list[$k]["teamid"]=$v["id"];
           $list[$k]["endtime"]=$v["endtime"];
           if ($v["user_id"]){
               $m=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));
           }else{
               $m=pdo_get("ewei_shop_member",array("id"=>$v["openid"]));
           }
           $list[$k]["nickname"]=$m["nickname"]?$m["nickname"]:"昵称";
           //获取总数量
           $count=pdo_fetchcolumn("select count(*) from ".tablename("ewei_shop_groups_order")." where is_team=1 and status=1 and teamid=:teamid",array(":teamid"=>$v["id"]));
           $list[$k]["count"]=$count;
           $list[$k]["groupnum"]=$good["groupnum"];
           $list[$k]["number"]=$good["groupnum"]-$count;
           //获取头像
           $team=pdo_fetchall("select openid,user_id from ".tablename("ewei_shop_groups_order")." where  is_team=1 and status=1 and teamid=:teamid",array(":teamid"=>$v["id"]));
           $list[$k]["avatar"]=array();
           $list[$k]["userid"]=array();
           foreach ($team as $kk=>$vv){
               if ($vv["user_id"]){
                   $team_member=pdo_get("ewei_shop_member",array("id"=>$vv["user_id"]));
               }else{
                   $team_member=pdo_get("ewei_shop_member",array("openid"=>$vv["openid"]));
               }
               
               $list[$k]["avatar"][$kk]=$team_member["avatar"];
               $list[$k]["userid"][$kk]=$team_member["id"];
           }
       }
       
       return $list;
   }
   //评价
   public function group_comment($goods_id,$first,$num,$label,$user_id){
       $good=pdo_get("ewei_shop_groups_goods",array("id"=>$goods_id));
       $condition="and  checked=0 and deleted=0 and (goodsid=:gid or group_goodsid=:good_id)";
       $param=array(":gid"=>$good["gid"],":good_id"=>$goods_id);
       if ($label){
           $condition=$condition." and label like :label"; 
           $param[":label"]="%".$label."%";
       }
     
       $total=pdo_fetchcolumn("select count(*) from ".tablename("ewei_shop_order_comment")." where 1 ".$condition,$param);
       
       $list=pdo_fetchall("select id,openid,level,type,user_id,content,images,createtime,append_content,orderid,group_orderid from ".tablename("ewei_shop_order_comment")." where 1 ".$condition." order by createtime desc limit ".$first.",".$num,$param);
       
       foreach ($list as $k=>$v){
           if ($v["anonymous"]==0){
               if ($v["user_id"]){
                   $member=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));
                   
               }else{
                   $member=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
                   
               }
               $list[$k]["nickname"]=$member["nickname"];
               $list[$k]["avatar"]=$member["avatar"];
           }else{
               $list[$k]["nickname"]="匿名";
               $list[$k]["avatar"]="";
           }
           //获取规格
           if ($v["type"]==0){
               $order_goods=pdo_get("ewei_shop_order_goods",array("orderid"=>$v["orderid"],"goodsid"=>$good["gid"]));
               $list[$k]["optionname"]=$order_goods["optionname"];
           }else{
               $order_goods=pdo_get("ewei_shop_groups_order_goods",array("groups_goods_id"=>$goods_id,"groups_order_id"=>$v["group_orderid"]));
               $list[$k]["optionname"]=$order_goods["option_name"];
           }
           $list[$k]["createtime"]=date("Y-m-d",$v["createtime"]);
           $image=iunserializer($v["images"]); 
//            var_dump($image); 
           $list[$k]["images"]=array();
           foreach ($image as $kk=>$vv){
               $list[$k]["images"][$kk]=tomedia($vv);
           }
           if ($user_id){
               $log=pdo_get("ewei_shop_order_comment_fav",array("user_id"=>$user_id,"ocid"=>$v["id"],"status"=>1));
               if ($log){
                   $list[$k]["zan"]=1;
               }else{
                   $list[$k]["zan"]=0;
               }
           }else{
               $list[$k]["zan"]=0;
           }
       }
       //获取商品规格
       $good_cate=pdo_get("ewei_shop_category",array("id"=>$good["ccate"]));
       
       if ($good_cate["label"]){
           $list_label=explode(",", $good_cate["label"]);
           
           foreach ($list_label as $k=>$v){
               //获取评价数目
               $c="and  checked=0 and deleted=0 and (goodsid=:gid or group_goodsid=:good_id)";
               $p=array(":gid"=>$good["gid"],":good_id"=>$goods_id);
               $c=$c." and label like :label";
               $p[":label"]="%".$v."%";
               $t=pdo_fetchcolumn("select count(*) from ".tablename("ewei_shop_order_comment")." where 1 ".$c,$p);
               $relabel[$k]["label"]=$v;
               $relabel[$k]["total"]=$t;
           }
       }else{
           $relabel["label"]=array();
       }
       $res["label"]=$relabel;
      
       $res["list"]=$list;
       $res["total"]=$total;
       $res["pagetotal"]=ceil($total/$num);
       $res["pagesize"]=$num;
       //获取好评率
       $condition="and  checked=0 and deleted=0 and (goodsid=:gid or group_goodsid=:good_id)";
       $param=array(":gid"=>$good["gid"],":good_id"=>$goods_id);
       $goodnum=pdo_fetchcolumn("select count(*) from ".tablename("ewei_shop_order_comment")." where 1 ".$condition,$param);

       if ($total==0){
           $res["rate"]=100;
       }else{
           $res["rate"]=($goodnum%$total)*100;
       }
       if ($res["rate"]==0){
           $res["rate"]=100;
       }
      
       return $res;
   }
   //拼团
   public function groupfail_message($orderid=""){
       if (empty($orderid)){
           return  false;
       }
       $order=pdo_get("ewei_shop_groups_order",array("id"=>$orderid));
       if (!$order){
           return false;
       }
       $good=pdo_get("ewei_shop_groups_goods",array("id"=>$order["goodid"]));
       $datas=array(
           'keyword1'=>array(
               'value'=>$order["orderno"],
               'color' => '#ff510'
           ),
           'keyword2'=>array(
               'value'=>$good["title"],
               'color' => '#ff510'
           ),
           'keyword3'=>array(
               'value'=>$order["price"]."元",
               'color' => '#ff510'
           ),
           'keyword4'=>array(
               'value'=>$order["price"]."元",
               'color' => '#ff510'
           ),
           'keyword5'=>array(
               'value'=>"未在规定时间内拼团成功",
               'color' => '#ff510'
           )
       );
       $page="packageA/pages/changce/spelltuan/orderDetails/orderDetails?order_id=".$orderid;
       $res=p("app")->newsendMessage($order["openid"],$datas,$page,"GPpLHUPcyYhvioH2LCD7VHOfxBs_7ln_Xr2ZLDTfEZ8");
       return $res;
   }
   //拼团成功
   public function groupsuccess_message($orderid=""){
       if (empty($orderid)){
           return  false;
       }
       $order=pdo_get("ewei_shop_groups_order",array("id"=>$orderid));
       if (!$order){
           return false;
       }
       $good=pdo_get("ewei_shop_groups_goods",array("id"=>$order["goodid"]));
       $datas=array(
           'keyword1'=>array(
               'value'=>$order["orderno"],
               'color' => '#ff510'
           ),
           'keyword2'=>array(
               'value'=>$good["title"],
               'color' => '#ff510'
           ),
           'keyword3'=>array(
               'value'=>$order["price"]."元",
               'color' => '#ff510'
           ),
           'keyword4'=>array(
               'value'=>date("Y-m-d H:i:s",$order["createtime"]),
               'color' => '#ff510'
           ),
           'keyword5'=>array(
               'value'=>date("Y-m-d",time()),
               'color' => '#ff510'
           )
       );
       $page="packageA/pages/changce/spelltuan/orderDetails/orderDetails?order_id=".$orderid;
       $res=p("app")->newsendMessage($order["openid"],$datas,$page,"wZso9aJ5EdV54Lv9NA49b0Nr-x8s5vCm-zRTTokZg7I");
       return $res;
   }
   //反馈问题
   public function feedback($question_id=""){
       if ($question_id==0){
           return false;
       }
       $question=pdo_get("ewei_shop_notive_question",array("id"=>$question_id));
       if (!$question){
           return false;
       }
       if (empty($question["openid"])){
           $member=pdo_get("ewei_shop_member",array("id"=>$question["user_id"]));
           $openid=$member["openid"];
       }else{
           $openid=$question["openid"];
       }
       if ($openid){
           $datas=array(
               'keyword1'=>array(
                   'value'=>$question["content"],
                   'color' => '#ff510'
               ),
               'keyword2'=>array(
                   'value'=>date("Y-m-d",$question["create_time"]),
                   'color' => '#ff510'
               ),
               'keyword3'=>array(
                   'value'=>"回复内容：".$question["answer"].",感谢您为我们提供宝贵的建议",
                   'color' => '#ff510'
               )
           );
           $res=p("app")->newsendMessage($openid,$datas,"","8vGrKvgioGJdY8HxJwsTUsYKQsbS1fDcseduK_x_sFE");
           
       }
       return true;
   }
   //账户余额
   public function balance_message($openid,$money,$reason,$remark){
       $datas=array(
           'keyword1'=>array(
               'value'=>$money,
               'color' => '#ff510'
           ),
           'keyword2'=>array(
               'value'=>$reason,
               'color' => '#ff510'
           ),
           'keyword3'=>array(
               'value'=>date("Y-m-d H:i:s",time()),
               'color' => '#ff510'
           ),
           'keyword4'=>array(
               'value'=>$remark,
               'color' => '#ff510'
           )
       );
       $res=p("app")->newsendMessage($openid,$datas,"","OEbSLy7FngJdmPkXpz1Cas5dwuuyTF7ZuXKpQSOju5o");
       return $res;
   }
   //订单发货
   public function ordersend_message($order_id=""){
       if (empty($order_id)){
           return false;
       }
       $order=pdo_get("ewei_shop_order",array("id"=>$order_id));
       if (!$order){
           return false;
       }
       $good=pdo_fetchall("select goodsid from ".tablename("ewei_shop_order_goods")." where orderid=:orderid and status!=-1",array(":orderid"=>$order_id));
       $goodname="";
       foreach ($good as $k=>$v){
           $g=pdo_get("ewei_shop_goods",array("id"=>$v["goodsid"]));
           if (empty($goodname)){
               $goodname=$g["title"];
           }else{
               $goodname=$goodname.",".$g["title"];
           }
       }
       $datas=array(
           'keyword1'=>array(
               'value'=>$order["ordersn"],
               'color' => '#ff510'
           ),
           'keyword2'=>array(
               'value'=>$goodname,
               'color' => '#ff510'
           ),
           'keyword3'=>array(
               'value'=>$order["expresssn"],
               'color' => '#ff510'
           ),
           'keyword4'=>array(
               'value'=>$order["expresscom"],
               'color' => '#ff510'
           ),
           'keyword5'=>array(
               'value'=>date("Y-m-d H:i:s",$order["sendtime"]),
               'color' => '#ff510'
           )
       );
       if (empty($order["openid"])){
           $member=pdo_get("ewei_shop_member",array("id"=>$order["user_id"]));
           $openid=$member["openid"];
       }else{
           $openid=$order["openid"];
       }
       $res=p("app")->newsendMessage($openid,$datas,"","mNJmhTJDOy7HfI1egQzfMwwCzGCAGvWldtoJ6EmrYpg");
       return $res;
   }
   
}