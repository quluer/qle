<?php defined('IN_IA') or exit('Access Denied');?><div class='fui-content params-block'>
    <div class="inner">

        <div class="param-item param-type">
            <div class="fui-title">商品类型</div>
            <div class="fui-cell-group fui-cell-click">
                <div class="fui-cell submit-params" data-value="1">
                    <div class="fui-cell-text">实体商品</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <div class="fui-cell submit-params" data-value="2">
                    <div class="fui-cell-text">虚拟商品</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <div class="fui-cell submit-params" data-value="3">
                    <div class="fui-cell-text">虚拟商品(卡密)</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
            </div>
        </div>

        <div class="param-item param-cate">
            <div class="fui-title">商品分类(多选)</div>
            <div class="cate-list" data-catlevel="<?php  echo $catlevel;?>">
                <div class="item" data-level="1">
                    <?php  if(is_array($allcategory['parent'])) { foreach($allcategory['parent'] as $category_item) { ?>
                        <nav data-id="<?php  echo $category_item['id'];?>"><?php  echo $category_item['name'];?></nav>
                    <?php  } } ?>
                </div>
                <?php  if($catlevel>=2) { ?>
                <div class="item" data-level="2">
                        <div class="empty">请选择上级</div>
                    </div>
                <?php  } ?>
                <?php  if($catlevel>=3) { ?>
                    <div class="item" data-level="3">
                        <div class="empty">请选择上级</div>
                    </div>
                <?php  } ?>
            </div>
            <div class="btn btn-success btn-sm block btn-choose-cate">选择此分类</div>
            <div class="fui-title">已选择分类</div>
            <div class="small-block">
                <?php  if(is_array($category)) { foreach($category as $category_item) { ?>
                    <?php  if(is_array($cates) &&  in_array($category_item['id'],$cates)) { ?>
                        <span class="item" data-cateid="<?php  echo $category_item['id'];?>"><?php  echo $category_item['name'];?> <i class="icon icon-close"></i></span>
                    <?php  } ?>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-totalcnf">
            <div class="fui-title">库存设置</div>
            <div class="fui-cell-group fui-cell-click">
                <div class="fui-cell submit-params" data-value="0">
                    <div class="fui-cell-text">拍下减库存</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <div class="fui-cell submit-params" data-value="1">
                    <div class="fui-cell-text">付款减库存</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <div class="fui-cell submit-params" data-value="2">
                    <div class="fui-cell-text">永不减库存</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
            </div>
        </div>

        <div class="param-item param-prop">
            <div class="fui-title">商品属性</div>
            <div class="fui-cell-group">
                <div class="fui-cell ">
                    <div class="fui-cell-label ">推荐商品</div>
                    <div class="fui-cell-info">
                        <input type="checkbox" class="fui-switch fui-switch-small fui-switch-success pull-right param-child" id="isrecommand" data-text="推荐" <?php  if(!empty($item['isrecommand'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                </div>
                <div class="fui-cell ">
                    <div class="fui-cell-label ">新品上市</div>
                    <div class="fui-cell-info">
                        <input type="checkbox" class="fui-switch fui-switch-small fui-switch-success pull-right param-child" id="isnew" data-text="新品" <?php  if(!empty($item['isnew'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                </div>
                <div class="fui-cell ">
                    <div class="fui-cell-label ">热卖商品</div>
                    <div class="fui-cell-info">
                        <input type="checkbox" class="fui-switch fui-switch-small fui-switch-success pull-right param-child" id="ishot" data-text="热卖" <?php  if(!empty($item['ishot'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                </div>
                <div class="fui-cell ">
                    <div class="fui-cell-label ">包邮商品</div>
                    <div class="fui-cell-info">
                        <input type="checkbox" class="fui-switch fui-switch-small fui-switch-success pull-right param-child" id="issendfree" data-text="包邮" <?php  if(!empty($item['issendfree'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                </div>
            </div>
        </div>

        <div class="param-item param-viewlevel">
            <div class="fui-title">可浏览等级(多选)</div>
            <div class="fui-list-group">
                <?php  if(is_array($levels)) { foreach($levels as $level_item) { ?>
                    <div class="fui-list bindclick">
                        <div class="fui-list-media">
                            <input type="checkbox" class="param-child fui-radio fui-radio-success" name="viewlevel" value="<?php  echo $level_item['id'];?>" data-text="<?php  echo $level_item['levelname'];?>" <?php  if(!empty($level_item['checked_view'])) { ?>checked="checked"<?php  } ?> />
                        </div>
                        <div class="fui-list-inner">
                            <div class="subtitle"><?php  echo $level_item['levelname'];?></div>
                        </div>
                    </div>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-viewgroup">
            <div class="fui-title">可浏览分组(多选)</div>
            <div class="fui-list-group">
                <?php  if(is_array($groups)) { foreach($groups as $group_item) { ?>
                    <div class="fui-list bindclick">
                        <div class="fui-list-media">
                            <input type="checkbox" class="param-child fui-radio fui-radio-success" name="viewgroup" value="<?php  echo $group_item['id'];?>" data-text="<?php  echo $group_item['groupname'];?>" <?php  if(!empty($group_item['checked_view'])) { ?>checked="checked"<?php  } ?> />
                        </div>
                        <div class="fui-list-inner">
                            <div class="subtitle"><?php  echo $group_item['groupname'];?></div>
                        </div>
                    </div>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-buylevel">
            <div class="fui-title">可购买等级(多选)</div>
            <div class="fui-list-group">
                <?php  if(is_array($levels)) { foreach($levels as $level_item) { ?>
                <div class="fui-list bindclick">
                    <div class="fui-list-media">
                        <input type="checkbox" class="param-child fui-radio fui-radio-success" name="viewlevel" value="<?php  echo $level_item['id'];?>" data-text="<?php  echo $level_item['levelname'];?>" <?php  if(!empty($level_item['checked_buy'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                    <div class="fui-list-inner">
                        <div class="subtitle"><?php  echo $level_item['levelname'];?></div>
                    </div>
                </div>
                <?php  } } ?>
            </div>
        </div>
        <div class="param-item param-buygroup">
            <div class="fui-title">可购买分组(多选)</div>
            <div class="fui-list-group">
                <?php  if(is_array($groups)) { foreach($groups as $group_item) { ?>
                <div class="fui-list bindclick">
                    <div class="fui-list-media">
                        <input type="checkbox" class="param-child fui-radio fui-radio-success" name="viewgroup" value="<?php  echo $group_item['id'];?>" data-text="<?php  echo $group_item['groupname'];?>" <?php  if(!empty($group_item['checked_buy'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                    <div class="fui-list-inner">
                        <div class="subtitle"><?php  echo $group_item['groupname'];?></div>
                    </div>
                </div>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-diypage">
            <div class="fui-title">自定义模板</div>
            <div class="fui-cell-group fui-cell-click">
                <div class="fui-cell submit-params" data-id="0">
                    <div class="fui-cell-text">默认模板</div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <?php  if(is_array($diypage)) { foreach($diypage as $diypage_item) { ?>
                    <div class="fui-cell submit-params" data-id="<?php  echo $diypage_item['id'];?>">
                        <div class="fui-cell-text"><?php  echo $diypage_item['name'];?></div>
                        <div class="fui-cell-remark">选择</div>
                    </div>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-diyform">
            <div class="fui-title">自定义表单</div>
            <div class="fui-cell-group fui-cell-click">
                <div class="fui-cell submit-params" data-id="0">
                    <div class="fui-cell-text">不设置</div>
                    <div class="fui-cell-remark">确定</div>
                </div>
            </div>
            <div class="fui-cell-group fui-cell-click">
                <?php  if(is_array($diyform)) { foreach($diyform as $diyform_item) { ?>
                <div class="fui-cell submit-params" data-id="<?php  echo $diyform_item['id'];?>">
                    <div class="fui-cell-text"><?php  echo $diyform_item['title'];?></div>
                    <div class="fui-cell-remark">选择</div>
                </div>
                <?php  } } ?>
            </div>
        </div>

        <div class="param-item param-dispatch">
            <div class="fui-title">运费设置</div>
            <div class="fui-list-group">
                <div class="fui-list bindclick" data-show="dispatchtype_0" data-hide="dispatchtype_1">
                    <div class="fui-list-media">
                        <input type="radio" class="fui-radio fui-radio-success" name="dispatchtype" value="0" id="dispatchtype_0" <?php  if(empty($item['dispatchtype'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                    <label class="fui-list-inner">
                        <div class="subtitle">运费模板</div>
                    </label>
                </div>
                <div class="fui-list bindclick" data-show="dispatchtype_1" data-hide="dispatchtype_0">
                    <div class="fui-list-media">
                        <input type="radio" class="fui-radio fui-radio-success" name="dispatchtype" value="1" <?php  if(!empty($item['dispatchtype'])) { ?>checked="checked"<?php  } ?> />
                    </div>
                    <label class="fui-list-inner">
                        <div class="subtitle">统一邮费</div>
                    </label>
                </div>
            </div>
            <div class="dispatchtype_0" style="display: <?php  if(empty($item['dispatchtype'])) { ?>block<?php  } else { ?>none<?php  } ?>;">
                <div class="fui-title">运费模板</div>
                <div class="fui-list-group">
                    <div class="fui-list bindclick">
                        <div class="fui-list-media">
                            <input type="radio" class="fui-radio fui-radio-success" name="dispatchid" value="0" <?php  if(empty($item['dispatchid'])) { ?>checked="checked"<?php  } ?> />
                        </div>
                        <div class="fui-list-inner">
                            <div class="subtitle">默认模板</div>
                        </div>
                    </div>
                    <?php  if(is_array($dispatch_data)) { foreach($dispatch_data as $dispatch_item) { ?>
                        <div class="fui-list bindclick">
                            <div class="fui-list-media">
                                <input type="radio" class="fui-radio fui-radio-success" name="dispatchid" value="<?php  echo $dispatch_item['id'];?>" <?php  if($item['dispatchid']==$dispatch_item['id']) { ?>checked="checked"<?php  } ?> />
                            </div>
                            <div class="fui-list-inner">
                                <div class="subtitle"><?php  echo $dispatch_item['dispatchname'];?></div>
                            </div>
                        </div>
                    <?php  } } ?>
                </div>
            </div>
            <div class="dispatchtype_1" style="display: <?php  if(!empty($item['dispatchtype'])) { ?>block<?php  } else { ?>none<?php  } ?>;">
                <div class="fui-title">统一邮费</div>
                <div class="fui-cell-group">
                    <div class="fui-cell">
                        <div class="fui-cell-label">邮费价格</div>
                        <div class="fui-cell-info">
                            <input type="number" placeholder="请输入统一运费" class="fui-input" value="<?php  echo $item['dispatchprice'];?>" id="dispatchprice" />
                        </div>
                        <div class="fui-cell-remark noremark">元</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="fui-navbar">
        <div class="nav-item btn btn-success submit-params">确定</div>
        <div class="nav-item btn btn-gray cancel-params">取消</div>
    </div>
</div>