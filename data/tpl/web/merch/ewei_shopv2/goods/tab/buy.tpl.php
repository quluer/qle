<?php defined('IN_IA') or exit('Access Denied');?><div class="region-goods-details row">
    <div class="region-goods-left col-sm-2">
        购买权限
    </div>
    <div class="region-goods-right col-sm-10">
        <div class="form-group">
            <label class="col-lg control-label">单次最多购买</label>
            <div class="col-sm-6 col-xs-12">
                <?php if( mce('goods' ,$item) ) { ?>
                <div class="input-group">
                    <input type="text" name="maxbuy" id="maxbuy" class="form-control" value="<?php  echo $item['maxbuy'];?>" />
                    <span class="input-group-addon">件</span>

                </div>
                <span class="help-block">用户单次购买此商品数量限制</span>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['maxbuy'];?> 件</div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">单次最低购买</label>
            <div class="col-sm-6 col-xs-12">
                <?php if( mce('goods' ,$item) ) { ?>
                <div class="input-group">
                    <input type="text" name="minbuy" id="minbuy" class="form-control" value="<?php  echo $item['minbuy'];?>" />
                    <span class="input-group-addon">件</span>

                </div>
                <span class="help-block">用户单次必须最少购买此商品数量限制</span>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['minbuy'];?> 件</div>
                <?php  } ?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg control-label">最多购买</label>

            <div class="col-sm-6 col-xs-12">
                <?php if( mce('goods' ,$item) ) { ?>
                <div class="input-group">
                    <input type="text" name="usermaxbuy" class="form-control" value="<?php  echo $item['usermaxbuy'];?>" />
                    <span class="input-group-addon">件</span>
                </div>
                <span class="help-block">用户购买过的此商品数量限制</span>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $item['usermaxbuy'];?> 件</div>
                <?php  } ?>

            </div>
        </div>
    </div>
</div>




<!--青岛易联互动网络科技有限公司-->