<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>集中采购</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <?php inc_js('adaptation');?>
    <?php inc_css('style')?>
</head>
<body>
<section class="bg">
    <header class="header">
        <h3>钢厂直接团购，优惠的价格，稳定的质量，可依赖的品质</h3>
        <p ontouchstart="window.location.href='<?php echo base_url("Cmateriel/statement")?>'">免责声明</p>
    </header>
    <section class="buy_num">
        <span>采购数量</span>
        <strong><?php echo $sum_sl?>t</strong>
        <p>评估时间：<?php echo date('Y.m.d');?></p>
    </section>
    <div class="people_num">
        参与工厂数：<?php echo sprintf("%03d", $sum_num);?>
    </div>

    <section class="list">
        <div class="list-hd">
            <h4>采购需求</h4>
            <p>请填写你的采购需求，如果还有更多需求，可以新增。</p>
        </div>
        <form action="<?php echo base_url('Cmateriel/addPost');?>" method="post">
            <div class="table-show">
                <table class="table1" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th>钢种</th>
                        <th>规格</th>
                        <th>数量</th>
                        <th>钢厂</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="total">
                    <p>合计：<i>300</i>吨</p>
                </div>
            </div>
            <ul class="checked" id="checked">
                <li>
                    <span><em>*</em>采购钢种：</span>
                    <select name="" id="" >
                        <option value="">请输入钢的品种</option>
                        <?php foreach(explode(',', $res->gh) as $h){?>
                            <option value="<?php echo $h?>"><?php echo $h?></option>
                        <?php }?>
                    </select>
                </li>
                <li>
                    <span><em>*</em>采购规格：</span>
                    <input type="number" name="" placeholder="请输入采购规格,只能是纯数字" />
                </li>
                <li>
                    <span><em>*</em>采购数量：</span>
                    <input type="number" name="" placeholder="请输入钢的数量" />
                </li>
                <li>
                    <span>采购钢厂：</span>
                    <select name="" id="" >
                        <option value="">请选择以下钢厂</option>
                        <?php foreach(explode(',', $res->gc) as $c){?>
                            <option value="<?php echo $c?>"><?php echo $c?></option>
                        <?php }?>
                    </select>
                </li>
                <li>
                    <span><em style="color:#fff;">*</em>备注：</span>
                    <input type="text" maxlength="100" name="" placeholder="请用简短文字输入你的描述" />
                </li>
            </ul>
            <a href="javascript:void(0);" class="trueBtn">确定</a>  
            <a href="javascript:void(0);" class="addBtn">继续添加</a>
            <div class="list-hd">
                <h4>核对信息</h4>
                <p>请核对如下联系人的信息，如有不符请重新编辑。</p>
            </div>
            <ul class="checked checked2">
                <li>
                    <span>联系人：</span>
                    <input type="text" maxlength="10" name="link_man" value="" placeholder="" />
                </li>
                <li>
                    <span>联系电话：</span>
                    <input type="number" maxlength="11" name="link_phone" value="" placeholder="" />
                </li>
                <li>
                    <span>公司名称：</span>
                    <input type="text" maxlength="30" name="link_company" value="" placeholder="" />
                </li>
                <li>
                    <input type="hidden" name="materiel_setid" value="<?php echo $res->id?>"/>
                    <input type="hidden" name="companyid" value=""/>
                    <input type="hidden" name="purchaseid" value=""/>
                    <input type="submit" class="submit" value="提交">
                </li>
            </ul>
        </form>
    </section>
</section>

<!--弹窗-->
<div class="pop_wrap">
    <section class="pop-ups">
        <h4>温馨提示</h4>
        <p class="pop_txt">有采购相同的钢种和规格，是否继续采购</p>
        <div class="pop_btn" id="pop_btn">
            <a href="javascript:void(0);" class="pop_true" style="width:50%;">确定</a><a href="javascript:void(0);" style="width:50%;border-left:1px solid #d7d4d4" class="">取消</a>
        </div>
    </section>
</div>

<?php inc_js('jquery-3.0.0.min')?>
<?php inc_js('wl')?>
</body>
</html>