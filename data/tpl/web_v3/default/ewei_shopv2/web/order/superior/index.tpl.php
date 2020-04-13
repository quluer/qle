<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<meta charset="UTF-8">
<style type='text/css'>

    .trhead td {  background:#efefef;text-align: center}

    .trbody td {  text-align: center; vertical-align:top;border-left:1px solid #f2f2f2;overflow: hidden; font-size:12px;}

    .trorder { background:#f8f8f8;border:1px solid #f2f2f2;text-align:left;}

    .ops { border-right:1px solid #f2f2f2; text-align: center;}

    .ops a,.ops span{

        margin: 3px 0;

    }

    .table-top .op:hover{

        color: #000;

    }

    .tables{

        border:1px solid #e5e5e5;

        font-size: 12px;

        line-height: 18px;

    }

    .tables:hover{

        border:1px solid #b1d8f5;

    }

    .table-row,.table-header,.table-footer,.table-top{

        display: -webkit-box;

        display: -webkit-flex;

        display: -ms-flexbox;

        display: flex;

        justify-content: center;

        -webkit-justify-content: center;

        -webkit-align-content: space-around;

        align-content: space-around;

    }

    .tables  .table-row>div{

        padding: 14px 0 !important;

    }

    .tables  .table-row.table-top>div{

        padding: 11px 0;

    }

    .tables    .table-row .ops.list-inner{

        border-right:none;

    }

    .tables .list-inner{

       border-right: 1px solid #efefef;

        vertical-align: middle;

    }

    .table-row .goods-des .title{

        width:180px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

    }

    .table-row .goods-des{

        width:300px;

        border-right: 1px solid #efefef;

        vertical-align: middle;

    }

    .table-row .list-inner{

        -webkit-box-flex: 1;

        -webkit-flex: 1;

        -ms-flex: 1;

        flex: 1;

        text-align: center;

        display: -webkit-box;

        display: -webkit-flex;

        display: -ms-flexbox;

        display: flex;

        -webkit-align-items: center;

        align-items: center;

        -webkit-justify-content: center;

        justify-content: center;

        -webkit-flex-direction: column;

        flex-direction: column;

    }

    .saler>div{

        width:130px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;

    }

    .table-row .list-inner.ops,  .table-row .list-inner.paystyle{

        -webkit-flex-direction: column;

        flex-direction: column;

       -webkit-justify-content: center;

       justify-content: center;

    }

    .table-header .others{

        -webkit-box-flex: 1;

        -webkit-flex: 1;

        -ms-flex: 1;

        flex: 1;

        text-align: center;

    }

    .table-footer{

        border-top: 1px solid #efefef;

    }

    .table-footer>div, .table-top>div{

        -webkit-box-flex: 1;

        -webkit-flex: 1;

        -ms-flex: 1;

        flex: 1;

        height:100%;

    }

    .fixed-header div{

        padding:0;

    }

    .fixed-header.table-header{

        display: none;

    }

    .fixed-header.table-header.active{

        display: -webkit-box;

        display: -webkit-flex;

        display: -ms-flexbox;

        display: flex;

    }

    .shop{

        display: inline-block;

        width:48px;

        height:18px;

        text-align: center;

        border:1px solid #1b86ff;

        color: #1b86ff;

        margin-right: 10px;

    }

    .min_program{

        display: inline-block;

        width:48px;

        height:18px;

        text-align: center;

        border:1px solid #ff5555;

        color: #ff5555;

        margin-right: 10px;

    }

</style>



<div class="page-header">

    当前位置：<span class="text-primary">优品云仓订单管理</span>

  
</div>

<div class="page-content">



    <div class="fixed-header table-header" style="padding: 0 50px;">

        <div style='border-left:1px solid #f2f2f2;width: 400px;text-align: left;'>商品</div>

        <div class="others">买家</div>

        <div class="others">支付/配送</div>

        <div class="others">价格</div>

        <div class="others">操作</div>

        <div class="others">状态</div>

    </div>

    <form action="./index.php" method="get" class="form-horizontal table-search" role="form"  id="search">

        <input type="hidden" name="c" value="site" />

        <input type="hidden" name="a" value="entry" />

        <input type="hidden" name="m" value="ewei_shopv2" />

        <input type="hidden" name="do" value="web" />

        <input type="hidden" name="r" value="order.superior.index" />

        <input type="hidden" name="status" value="<?php  echo $status;?>" />

        <div class="page-toolbar">

            <div class="input-group">

                <span class="input-group-select">

                    <select name='status'  class='form-control'   style="width:100px;padding:0 5px;"  id="status">

                        <option value=''>订单状态</option>
                         <option value='5' <?php  if($_GPC['status']==5) { ?>selected<?php  } ?>>全部</option>

                        <option value='-1' <?php  if($_GPC['status']==-1) { ?>selected<?php  } ?>>已取消</option>

                        <option value='6' <?php  if($_GPC['status']==6) { ?>selected<?php  } ?>>未支付</option>

                        <option value='1' <?php  if($_GPC['status']==1) { ?>selected<?php  } ?>>已付款</option>

                        <option value='3' <?php  if($_GPC['status']==3) { ?>selected<?php  } ?>>已完成</option>
                        
                    </select>

                </span>
                
                <span class="input-group-select">

                    <select name='searchtime'  class='form-control'   style="width:100px;padding:0 5px;"  id="searchtime">

                        <option value=''>不按时间</option>

                        <option value='create' <?php  if($_GPC['searchtime']=='create') { ?>selected<?php  } ?>>下单时间</option>

                        <option value='pay' <?php  if($_GPC['searchtime']=='pay') { ?>selected<?php  } ?>>付款时间</option>

                        <option value='send' <?php  if($_GPC['searchtime']=='send') { ?>selected<?php  } ?>>发货时间</option>

                        <option value='finish' <?php  if($_GPC['searchtime']=='finish') { ?>selected<?php  } ?>>完成时间</option>

                    </select>

                </span>

                <span class="input-group-btn">

                    <?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d H:i', $endtime)),true);?>

                </span>

                <span class="input-group-select">

                    <select name='searchfield'  class='form-control'   style="width:110px;padding:0 5px;"  >

                        <option value='ordersn' <?php  if($_GPC['searchfield']=='ordersn') { ?>selected<?php  } ?>>订单号</option>

                        <option value='address' <?php  if($_GPC['searchfield']=='address') { ?>selected<?php  } ?>>收件人信息</option>

                        <option value='location' <?php  if($_GPC['searchfield']=='location') { ?>selected<?php  } ?>>地址信息</option>
                        <option value='goodstitle' <?php  if($_GPC['searchfield']=='goodstitle') { ?>selected<?php  } ?>>商品名称</option>
                        <option value='remark' <?php  if($_GPC['searchfield']=='remark') { ?>selected<?php  } ?>>备注信息</option>
                    </select>

                </span>

                <input type="text" class="form-control input-sm"  name="keyword" id="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词"/>

                <span class="input-group-btn">

                        <button type="button" data-export="0" class="btn btn-primary btn-submit"> 搜索</button>

                </span>

            </div>



        </div>



    </form>





    <?php  if(count($list)>0) { ?>

    <div class="row">

        <div class="col-md-12">

            <div  class="">

                <div class="table-header" style='background:#f8f8f8;height: 35px;line-height: 35px;padding: 0 20px'>

                    <div style='border-left:1px solid #f2f2f2;width: 400px;text-align: left;'>商品</div>

                    <div class="others">买家</div>

                    <div class="others">支付/配送</div>

                    <div class="others">价格</div>

                    <div class="others">操作</div>

                    <div class="others">状态</div>

                </div>

            <?php  if(is_array($list)) { foreach($list as $item) { ?>

            <div class="table-row"><div style='height:20px;padding:0;border-top:none;'>&nbsp;</div></div>

                <div class="tables">

                    <div class='table-row table-top' style="padding:0  20px;background: #f7f7f7">

                        <div style="text-align: left;color: #8f8e8e;">

                           

                            订单编号:  <?php  echo $item['ordersn'];?>

                           
                            <label class='label label-danger'>
                            
                            <?php  if($item['jdcustomerExpect']==10) { ?>退货申请<?php  } ?>
                            <?php  if($item['jdcustomerExpect']==20) { ?>换货申请<?php  } ?>
                            <?php  if($item['jdcustomerExpect']==30) { ?>维修申请<?php  } ?>
                            </label>
                            
                           
                        </div>

                       

                    </div>

                    <div class='table-row' style="margin:0  20px">

                        <div class="goods-des" style='width:400px;text-align: left'>

                            <?php  if(is_array($item['goods'])) { foreach($item['goods'] as $k => $g) { ?>

                            <div style="display: -webkit-box;display: -webkit-flex;display: -ms-flexbox;display: flex;margin: 10px 0">

                                <img src="<?php  echo $g['imagePath'];?>" style='width:70px;height:70px;border:1px solid #efefef; padding:1px;'onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'">

                                <div style="-webkit-box-flex: 1;-webkit-flex: 1;-ms-flex: 1;flex: 1;margin-left: 10px;text-align: left;display: flex;align-items: center">

                                    <div >

                                       <div class="title">

                                         

                                           <?php  echo $g['name'];?><br/>

                                           

                                       </div>

                                             



                                    </div>

                                    <span style="float: right;text-align: right;display: inline-block;width:130px;">

                                        ￥<?php  echo number_format($g['price'],2)?><br/>

                                    x<?php  echo $g['total'];?>

                                    </span>

                                </div>

                            </div>

                            <?php  } } ?>



                        </div>



                        <div class="list-inner saler"   style='text-align: center;' >

                            <div>

                                <?php  echo $item['nickname'];?>

                                <br/>

                                <?php  echo $item['arealname'];?><br/><?php  echo $item['amobile'];?>

                            </div>

                        </div>

                        <div class="list-inner paystyle"  style='text-align:center;' >



                            <!-- 已支付 -->

                            <?php  if($item['status']==0) { ?>

                                  <label class='label label-default'>未支付</label>
                             <?php  } else { ?>
                                <?php  if($item['paytypevalue']==1) { ?>

                                   <span> <i class="icow icow-yue text-warning" style="font-size: 17px;"></i><span>余额支付</span></span>


                                <?php  } else if($item['paytypevalue']==6) { ?>

                                <span> <i class="icow icow-kuajingzhifuiconfukuan text-danger" style="font-size: 17px"></i>RVC付款</span>

                                <?php  } else if($item['paytypevalue']==11) { ?>

                                   <span> <i class="icow icow-kuajingzhifuiconfukuan text-danger" style="font-size: 17px"></i>后台付款</span>

                                <?php  } else if($item['paytypevalue']==21) { ?>

                                   <span> <i class="icow icow-weixinzhifu text-success" style="font-size: 17px"></i>微信支付</span>

                                <?php  } else if($item['paytypevalue']==22) { ?>

                                    <span><i class="icow icow-zhifubaozhifu text-primary" style="font-size: 17px"></i>支付宝支付</span>

                                <?php  } ?>

                            


                            <?php  } ?>


                        </div>
                         <a  class="list-inner" data-toggle='popover' data-html='true' data-placement='right' data-trigger="hover"   data-content="">
                        
 <div style='text-align:center'>

                                ￥<?php  echo number_format($item['price'],2)?>

                                <?php  if($item['dispatchprice']>0) { ?>

                                <br/>(含运费:￥<?php  echo number_format( $item['dispatchprice'],2)?>)

                                <?php  } ?>

                            </div>
              </a>
                         

                        <div  class="list-inner" style='text-align:center'>

                            <a class='op text-primary'  href="<?php  echo webUrl('order/superior/detail', array('id' => $item['id']))?>" >查看详情</a>

                            <?php  if($item['jdcustomerExpect']!=0) { ?>
                            <!--  
                            <a class='op  text-primary'  href="<?php  echo webUrl('order/superior/refund', array('id' => $item['id']))?>" >维权详情</a>
                           -->
                            <?php  } ?>
                          
                            <a class='op  text-primary'  data-toggle="ajaxModal" href="<?php  echo webUrl('order/superior/express', array('id' => $item['id']))?>">京东订单进程</a>

                        </div>

                        <div  class='ops list-inner' style='line-height:20px;text-align:center' >

                            <span class='text-<?php  echo $item['statuscss'];?>'>
                            <?php  if($item["status"]==-1) { ?>
                                                                                 已取消
                            <?php  } else if($item["status"]==1) { ?>
                            已支付
                            <?php  } else if($item["status"]==0) { ?>
                            待支付
                             <?php  } else if($item["status"]==3) { ?>
                             已完成        
                            <?php  } ?>
                            
                            </span>
                            
                          <?php  if($item["status"]==0) { ?>
                         
                           <a class="btn btn-primary btn-xs" data-toggle='ajaxPost' href="<?php  echo webUrl('order/superior/cancel', array('id' => $item['id']))?>" data-confirm="确认取消订单吗？">取消订单</a>
                           <?php  } ?>
                            <?php  if($item["status"]==-1) { ?>
                         
                           <a class="btn btn-primary btn-xs" data-toggle='ajaxPost' href="<?php  echo webUrl('order/superior/del', array('id' => $item['id']))?>" data-confirm="确认删除订单吗？">删除</a>
                           <?php  } ?>
                        </div>



                    </div>

            <?php  if(!empty($item['remark'])) { ?>

            <div class="table-row"><div  style='background:#fdeeee;color:red;flex: 1;;'>买家备注: <?php  echo $item['remark'];?></div></div>

            <?php  } ?>



            </div>

            <?php  } } ?>

                <div style="padding: 20px;text-align: right" >

                        <?php  echo $pager;?>

                </div>

            </div>

        </div>

    </div>

    <?php  } else { ?>

    <div class="panel panel-default">

        <div class="panel-body empty-data">暂时没有任何订单!</div>

    </div>

    <?php  } ?>

</div>



<script>

    //没有选中时间段不能导出

    $(function () {

        $('.btn-submit').click(function () {

            var e = $(this).data('export');

            if(e==1 ){

                if($('#keyword').val() !='' ){

                    $('#export').val(1);

                    $('#search').submit();

                }else if($('#searchtime').val()!=''){

                    $('#export').val(1);

                    $('#search').submit();

                }else{

                    tip.msgbox.err('请选择按时间导出!');

                    return;

                }

            }else{

                $('#export').val(0);

                $('#search').submit();

            }

        })

    })

</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
