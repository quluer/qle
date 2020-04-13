<?php
if (!defined("IN_IA")) {
    exit("Access Denied");
}
require(EWEI_SHOPV2_PLUGIN . "app/core/page_mobile.php");

class Index_EweiShopV2Page extends AppMobilePage{
    //动态圈
    public function index(){
        global $_W;
        global $_GPC;
        $openid=$_GPC["openid"];
        if (empty($openid)){
            apperror(1,"未传openid");
        }
        if ($_GPC["type"]==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (!$member){
            apperror(1,"不存在该用户");
        }
        
        
        $l=array();
       
        $page=$_GPC["page"];
        $first=($page-1)*10;
        $list=pdo_fetchall("select id,openid,user_id,content,img,goods_id,view_count,comment_count,zan_count,create_day from ".tablename("ewei_shop_member_drcircle")."  where is_del=0 and is_view=0 order by create_time desc limit ".$first.", 10");
        foreach ($list as $k=>$v){
            //图片
            if ($v["img"]){
                
            $img=unserialize($v["img"]);
            $list[$k]["img"]=array();
            foreach ($img as $key=>$val){
                $list[$k]["img"][$key]=tomedia($val);
            }
            $list[$k]["img_len"]=sizeof($list[$k]["img"]);
            
            }else{
                $list[$k]["img"]=array();
                $list[$k]["img_len"]=0;
            }
            //判断是否带赞
//             $support=pdo_get("ewei_shop_member_drsupport",array("type"=>1,"content_id"=>$v["id"],"openid"=>$openid));
            $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=1 and content_id=:content_id and (openid=:openid or user_id=:user_id) limit 1",array(":content_id"=>$v["id"],":openid"=>$member["openid"],":user_id"=>$member["id"]));
            if ($support){
                $list[$k]["support"]=1;
            }else{
                $list[$k]["support"]=0;
            }
            //获取商品
            if ($v["goods_id"]){
               $goods=pdo_get("ewei_shop_goods",array("id"=>$v["goods_id"]));
               $list[$k]["good"]["goods_id"]=$v["goods_id"];
               $list[$k]["good"]["img"]=tomedia($goods["thumb"]);
               $list[$k]["good"]["title"]=$goods["title"];
               $list[$k]["good"]["subtitle"]=$goods["subtitle"];
               $list[$k]["good"]["productprice"]=$goods["productprice"];
               $list[$k]["good"]["marketprice"]=$goods["marketprice"];
               $list[$k]["good"]["sales"]=$goods["sales"];
            }else{
                $list[$k]["good"]=new ArrayObject();  
            }
            //获取用户信息
            if ($v["openid"]){
            $m=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
            }else{
             $m=pdo_get("ewei_shop_member",array("user_id"=>$v["user_id"]));
            }
            $list[$k]["nickname"]=$m["nickname"];
            $list[$k]["avatar"]=$m["avatar"];
            
        }
        //记录查看时间
//         $log=pdo_get("ewei_shop_member_drlog",array("openid"=>$openid));
        $log=pdo_fetch("select * from ".tablename("ewei_shop_member_drlog")." where openid=:openid or user_id=:user_id",array(":openid"=>$member["openid"],":user_id"=>$member["id"]));
        if ($log){
            $count=pdo_fetch("select count(*) as a from ".tablename("ewei_shop_member_drcircle")." where create_time>:create_time  and  is_del=0 and is_view=0",array(":create_time"=>$log["create_time"]));
//             var_dump(pdo_fetchall("select * from ".tablename("ewei_shop_member_drcircle")." where create_time>:create_time  and  is_del=0 and is_view=0",array(":create_time"=>$log["create_time"])));
//             var_dump($count);var_dump("11");die;
            pdo_update("ewei_shop_member_drlog",array("create_time"=>time(),"user_id"=>$member["id"]),array("id"=>$log["id"]));
            
        }else{
            $count=pdo_fetch("select count(*) as a from ".tablename("ewei_shop_member_drcircle")." where is_del=0 and is_view=0");
//              var_dump($count);var_dump("22");die;
            $d["openid"]=$member["openid"]; 
            $d["user_id"]=$member["id"];
            $d["create_time"]=time();
            pdo_insert("ewei_shop_member_drlog",$d);
        }
        if ($count["a"]>10){
            $count["a"]=10;
        }
        
        $l["count"]=$count["a"];
        
        $l["list"]=$list;
        $a=pdo_fetch("select count(*) as a from ".tablename("ewei_shop_member_drcircle")."  where is_del=0 and is_view=0 ");
        $l["total"]=$a["a"];
        if ($_GPC["type"]==1){
            apperror(0,"",$l);
        }else{
        app_error(0,$l);
        }
    }
    //详情
    public function detail(){
        global $_W;
        global $_GPC;
        $openid=$_GPC["openid"];
        
        if (empty($openid)){
            apperror(1,"未传openid");
        }
        if ($_GPC["type"]==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (!$member){
            apperror(1,"不存在该用户");
        }
        
        
        $ciclre_id=$_GPC["ciclre_id"];
        $detail=pdo_get("ewei_shop_member_drcircle",array("id"=>$ciclre_id));
        if (empty($detail)){
            apperror(1,"不存在该动态");
        }
        if ($detail["is_del"]==1||$detail["is_view"]==1){
            apperror(1,"该动态已被删除");
        }
        //更新查看数目
        $update["view_count"]=$detail["view_count"]+1;
        pdo_update("ewei_shop_member_drcircle",$update,array("id"=>$ciclre_id));
        //图片
        //var_dump($detail["img"]);
        if ($detail["img"]){
            
            $img=unserialize($detail["img"]);
          //  var_dump($img);
            $detail["img"]=array();
            foreach ($img as $k=>$v){
                $detail["img"][$k]=tomedia($v);
            }
          //  var_dump($detail["img"]);die;
            $detail["img_len"]=sizeof($detail["img"]);
        }else{
            $detail["img"]=array();
            $detail["img_len"]=0;
        }
        //判断是否带赞
//         $support=pdo_get("ewei_shop_member_drsupport",array("type"=>1,"content_id"=>$detail["id"],"openid"=>$openid));
        $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=1 and content_id=:content_id and (openid=:openid or user_id=:user_id)",array(":content_id"=>$detail["id"],":openid"=>$member["openid"],":user_id"=>$member["id"]));
        if ($support){
            $detail["support"]=1;
        }else{
            $detail["support"]=0;
        }
        //获取商品
        if ($detail["goods_id"]){
            $goods=pdo_get("ewei_shop_goods",array("id"=>$detail["goods_id"]));
            $detail["good"]["goods_id"]=$detail["goods_id"];
            $detail["good"]["img"]=tomedia($goods["thumb"]);
            $detail["good"]["title"]=$goods["title"];
            $detail["good"]["subtitle"]=$goods["subtitle"];
            $detail["good"]["productprice"]=$goods["productprice"];
            $detail["good"]["marketprice"]=$goods["marketprice"];
            $detail["good"]["sales"]=$goods["sales"];
        }else{
            $detail["good"]=new ArrayObject();
            
        }
        //获取用户信息
        if ($detail["openid"]){
        $m=pdo_get("ewei_shop_member",array("openid"=>$detail["openid"]));
        }else{
        $m=pdo_get("ewei_shop_member",array("id"=>$detail["user_id"]));
        }
        $detail["nickname"]=$m["nickname"];
        $detail["avatar"]=$m["avatar"];
        //点赞列表
        $zan=pdo_fetchall("select openid,user_id from ".tablename("ewei_shop_member_drsupport")." where type=1 and content_id=:content_id order by create_time desc",array(":content_id"=>$detail["id"]));
        $detail["zan_list"]=array();
        foreach ($zan as $k=>$v){
            if ($v["openid"]){
            $mem=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
            }else{
            $mem=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));
            }
            $detail["zan_list"][$k]=$mem["avatar"];
        }
        
       if ($_GPC["type"]==1){
           apperror(0,"",$detail);
       }else{
       app_error(0,$detail);
       }
    }
    
    function timeFormat( $timestamp ) {
        $curTime = time();
        $space = $curTime - $timestamp;
        if($space < 60) { // 一分钟以内
            $string = "刚刚";
            return $string;
        } elseif( $space < 3600 ) { // 一小时前之内
            $string = floor($space / 60) . "分钟前";
            return $string;
        }
        $curtimeArray = getdate($curTime);
        $timeArray = getDate($timestamp);
        if( $curtimeArray['year'] == $timeArray['year'] ) { // 同一年
            if($curtimeArray['yday'] == $timeArray['yday']) { // 同一天
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "今天 {$string}";
            } elseif(($curtimeArray['yday'] - 1) == $timeArray['yday']) { // 昨天
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "昨天 {$string}";
            } else  {
                $string = sprintf("%d月%d日 %d:%d", $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], $timeArray['minutes']);
                return $string;
            }
        }
        $string = sprintf("%d年%d月%d日 %d:%d", $timeArray['year'], $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], $timeArray['minutes']);
        return $string;
    }
    //评论列表
    public function comment(){
        global $_W;
        global $_GPC;
        $openid=$_GPC["openid"];
        if (empty($openid)){
            app_error(1,"请填写openid");
        }
        if ($_GPC["apptype"]==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (!$member){
            apperror(1,"不存在该用户");
        }
        
        
        $ciclre_id=$_GPC["ciclre_id"];
        $type=$_GPC["type"];
        $page=$_GPC["page"]?$_GPC["page"]:1;
        $first=($page-1)*10;
        if ($type==1){
            $sort="desc";
        }else{
            $sort="asc";
        }
        $list=pdo_fetchall("select id,openid,user_id,content,comment_count,zan_count,create_time from ".tablename("ewei_shop_member_drcomment")." where type=1 and is_del=0 and is_view=0 and parent_id=:parent_id order by create_time ".$sort." limit ".$first.",10",array(":parent_id"=>$ciclre_id));
//         var_dump($list);die;
        $total=pdo_fetch("select count(*) as a from ".tablename("ewei_shop_member_drcomment")." where type=1 and is_del=0 and is_view=0 and parent_id=:parent_id",array(":parent_id"=>$ciclre_id));
        
        foreach ($list as $k=>$v){
            $list[$k]["create_time"]=$this->timeFormat($v["create_time"]);
            if ($v["openid"]){
            $m=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
            }else{
            $m=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));
            }
            $list[$k]["nickname"]=$m["nickname"];
            $list[$k]["avatar"]=$m["avatar"];
            //判断是否带赞
//             $support=pdo_get("ewei_shop_member_drsupport",array("type"=>2,"content_id"=>$v["id"],"openid"=>$openid));
            $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=2 and content_id=:content_id and (openid=:openid or user_id=:user_id) limit 1",array(":content_id"=>$v['id'],":openid"=>$member["openid"],":user_id"=>$member["id"]));
            if ($support){
                $list[$k]["support"]=1;
            }else{
                $list[$k]["support"]=0;
            }
            
            //获取下级评论
            $list[$k]["comment"]=pdo_fetchall("select  id,openid,comment_openid,user_id,content from ".tablename("ewei_shop_member_drcomment")." where type=2 and is_del=0 and is_view=0 and classA_id=:classA_id order by create_time asc limit 2",array(":classA_id"=>$v["id"]));
          // var_dump($list[$k]["comment"]);die;
            foreach ($list[$k]["comment"] as $key=>$val){
                if ($val["openid"]){
                $m=pdo_get("ewei_shop_member",array("openid"=>$val["openid"]));
                }else{
                  $m=pdo_get("ewei_shop_member",array("id"=>$val["user_id"]));
                }
                $list[$k]["comment"][$key]["nickname"]=$m["nickname"];
                if ($val["comment_openid"]){
//                     $mm=pdo_get("ewei_shop_member",array("openid"=>$val["comment_openid"]));
                    $mm=pdo_fetch("select * from ".tablename("ewei_shop_member")." where openid=:openid or id=:user_id",array(":openid"=>$val["comment_openid"],":user_id"=>$val["comment_openid"]));
                    $list[$k]["comment"][$key]["bnickname"]=$mm["nickname"];
                }else{
                    $list[$k]["comment"][$key]["bnickname"]="";
                }
            }
            
        }
        if (empty($list)){
            $list=array();
        }
        $l["list"]=$list;
        $l["total"]=$total["a"];
        if ($_GPC["type"]==1){
            apperror(0,"",$l);
        }else{
        app_error(0,$l);
        }
    }
    //评论详情
    public function comment_detail(){
        global $_W;
        global $_GPC;
        $openid=$_GPC["openid"];
        if (empty($openid)){
            apperror(1,"请填写openid");
        }
        if ($_GPC["apptype"]==1){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (!$member){
            apperror(1,"不存在该用户");
        }
        
        
        $comment_id=$_GPC["comment_id"];
        $detail=pdo_get("ewei_shop_member_drcomment",array("id"=>$comment_id,"is_view"=>0,"is_del"=>0,"type"=>1));
        if (empty($detail)){
            apperror(1,"不存在该评论");
        }
        
        $detail["create_time"]=$this->timeFormat($detail["create_time"]);
        //获取用户信息
        if ($detail["openid"]){
        $m=pdo_get("ewei_shop_member",array("openid"=>$detail["openid"]));
        }else{
         $m=pdo_get("ewei_shop_member",array("id"=>$detail["user_id"]));
        }
        $detail["nickname"]=$m["nickname"];
        $detail["avatar"]=$m["avatar"];
        //是否点赞
        //判断是否带赞
//         $support=pdo_get("ewei_shop_member_drsupport",array("type"=>2,"content_id"=>$detail["id"],"openid"=>$openid));
        $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=2 and content_id=:content_id and (openid=:openid or user_id=:user_id)",array(":content_id"=>$detail["id"],":openid"=>$member["openid"],":user_id"=>$member["id"]));
        if ($support){
            $detail["support"]=1;
        }else{
            $detail["support"]=0;
        }
        
        $type=$_GPC["type"];
        $page=$_GPC["page"];
        $first=($page-1)*10;
        if ($type==1){
            $sort="desc";
        }else{
            $sort="asc";
        }
        
        $list=pdo_fetchall("select id,openid,user_id,content,comment_count,zan_count,create_time,comment_openid from ".tablename("ewei_shop_member_drcomment")." where type=2 and is_del=0 and is_view=0 and classA_id=:parent_id order by create_time ".$sort." limit ".$first.",10",array(":parent_id"=>$detail["id"]));
       
        if (empty($list)){
        $detail["comment"]=array();
        }else{
        $detail["comment"]=$list;
        }
        
        $total=pdo_fetch("select count(*) as a  from ".tablename("ewei_shop_member_drcomment")." where type=2 and is_del=0 and is_view=0 and classA_id=:parent_id",array(":parent_id"=>$detail["id"]));
        $detail["comment_total"]=$total["a"];
        if ($list){
        
        foreach ($detail["comment"] as $k=>$v){
            if ($v["openid"]){
            $m=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
            }else{
             $m=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));   
            }
            $detail["comment"][$k]["nickname"]=$m["nickname"];
            $detail["comment"][$k]["avatar"]=$m["avatar"];
            
//             $m=pdo_get("ewei_shop_member",array("openid"=>$v["comment_openid"]));
            $m=pdo_fetch("select * from ".tablename("ewei_shop_member")." where openid=:openid or id=:user_id limit 1",array(":openid"=>$v["comment_openid"],":user_id"=>$v["comment_openid"]));
            $detail["comment"][$k]["bnickname"]=$m["nickname"];
            $detail["comment"][$k]["create_time"]=$this->timeFormat($v["create_time"]);
            //判断的hi否点赞
            //判断是否带赞
//             $support=pdo_get("ewei_shop_member_drsupport",array("type"=>2,"content_id"=>$v["id"],"openid"=>$openid));
            $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=2 and content_id=:content_id and (openid=:openid or user_id=:user_id)",array(":content_id"=>$v["id"],":openid"=>$member["openid"],":user_id"=>$member["id"]));
            if ($support){
                $detail["comment"][$k]["support"]=1;
            }else{
                $detail["comment"][$k]["support"]=0;
            }
        }
        
        
        }
        if ($_GPC["type"]==1){
            apperror(0,"",$detail);
        }else{
        app_error(0,$detail);
        }
    }
    
    //热评
    public function hot_comment(){
        global $_W;
        global $_GPC;
        $openid=$_GPC["openid"];
        if (empty($openid)){
            app_error(1,"请填写openid");
        }
        if ($_GPC["type"]){
            $member_id=m('member')->getLoginToken($openid);
            if ($member_id==0){
                apperror(1,"无此用户");
            }
            $openid=$member_id;
        }
        $member=m("member")->getMember($openid);
        if (!$member){
            apperror(1,"不存在该用户");
        }
        
        $ciclre_id=$_GPC["ciclre_id"];
        $detail=pdo_get("ewei_shop_member_drcircle",array("id"=>$ciclre_id));
        if (empty($detail)){
            apperror(1,"不存在该动态");
        }
        if ($detail["is_del"]==1||$detail["is_view"]==1){
            apperror(1,"该动态已被删除");
        }
        //获取热评
        $comemnt=pdo_fetchall("select id,openid,user_id,content,comment_count,zan_count,create_time from ".tablename("ewei_shop_member_drcomment")." where is_view=0 and is_del=0 and type=1 and comment_count>=10 and parent_id=:parent_id order by comment_count desc limit 3",array(":parent_id"=>$ciclre_id));
        if ($comemnt){
        $detail["hot"]=$comemnt;
        }else{
            $detail["hot"]=array();
        }
        foreach ($detail["hot"] as $k=>$v){
            $detail["hot"][$k]["create_time"]=$this->timeFormat($v["create_time"]);
            if ($v["openid"]){
            $m=pdo_get("ewei_shop_member",array("openid"=>$v["openid"]));
            }else{
            $m=pdo_get("ewei_shop_member",array("id"=>$v["user_id"]));
            }
            $detail["hot"][$k]["nickname"]=$m["nickname"];
            $detail["hot"][$k]["avatar"]=$m["avatar"];
            
            //判断是否带赞
//             $support=pdo_get("ewei_shop_member_drsupport",array("type"=>2,"content_id"=>$v["id"],"openid"=>$openid));
            $support=pdo_fetch("select * from ".tablename("ewei_shop_member_drsupport")." where type=2 and content_id=:content_id and (openid=:openid or user_id=:user_id)",array(":content_id"=>$v["id"],":openid"=>$member["openid"],":user_id"=>$member["id"]));
            if ($support){
                $detail["hot"][$k]["support"]=1;
            }else{
                $detail["hot"][$k]["support"]=0;
            }
        }
        if ($_GPC["type"]==1){
            apperror(0,"",$detail);
        }else{
        app_error(0,$detail);
        }
        
    }
    public function cs(){
        global $_W;
        global $_GPC;
        var_dump($_W['attachurl']);
    }
}