<?php defined('IN_IA') or exit('Access Denied');?><div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        商品详情
    </div>
    <div class="region-goods-right col-sm-10">
        <?php if( mce('goods' ,$item) ) { ?>
        <?php  echo tpl_ueditor('content',$item['content'],array('height'=>'300','dest_dir'=>'merch/'.$_W['merchid']))?>
        <?php  } else { ?>
        <textarea id='detail' style='display:none'><?php  echo $item['content'];?></textarea>
        <a href='javascript:preview_html("#detail")' class="btn btn-default">查看内容</a>
        <?php  } ?>
    </div>
</div>


<div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        <div class="col-sm-10">
            <div>
                <h4 style="line-height: 50px;font-weight: bold;">购买后可见</h4>
                <div class="col-sm-2 pull-right">
                    <?php if( mce('goods' ,$item) ) { ?>
                    <input type="checkbox" class="js-switch small" name="buyshow" value="1" <?php  if($item['buyshow']==1) { ?>checked<?php  } ?> />
                    <?php  } else { ?>
                    <?php  if($item['buyshow']==1) { ?>
                    <span class='text-success'>开启</span>
                    <?php  } else { ?>
                    <span class='text-default'>关闭</span>
                    <?php  } ?>
                    <?php  } ?>
                </div>
            </div>
            <span style="font-weight: normal;font-size: 12px;display: block;">开启后购买过的商品才会显示</span>
        </div>
    </div>
    <div class="region-goods-right col-sm-10 bcontent" <?php  if(empty($item['buyshow'])) { ?>style="display: none;"<?php  } ?>>
        <!--<div class=" bcontent">-->
            <?php if( mce('goods' ,$item) ) { ?>
            <?php  echo tpl_ueditor('buycontent',$item['buycontent'],array('height'=>'300','dest_dir'=>'merch/'.$_W['merchid']))?>
            <?php  } else { ?>
            <textarea id='buycontent' style='display:none;'><?php  echo $item['buycontent'];?></textarea>
            <a href='javascript:preview_html("#buycontent")' class="btn btn-default">查看内容</a>
            <?php  } ?>
        <!--</div>-->
    </div>
</div>


<!--<div class="form-group" >-->
    <!--&lt;!&ndash;<label class="col-lg">购买后可见</label>&ndash;&gt;-->

    <!--<div class="col-sm-10 col-xs-12">-->

    <!--</div>-->
    <!--<div class="col-lg pull-right" style="padding-right:50px;text-align: right" >-->


    <!--</div>-->



<!--</div>-->
<!--青岛易联互动网络科技有限公司-->