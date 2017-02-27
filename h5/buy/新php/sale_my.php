<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的清单</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script>
        var cssEl=document.createElement("style");
        document.documentElement.firstElementChild.appendChild(cssEl);
        var dpr=1;
        var pxPerRem=document.documentElement.clientWidth*dpr/10;
        if(pxPerRem>60){
            pxPerRem=60;
        }
        cssEl.innerHTML='html{font-size:'+pxPerRem+'px!important;}';
    </script>
    <?php inc_css('buy')?>
</head>
<body style="background:#f0f0f0;">
<form action="<?php echo base_url('Cmateriel/intent_post')?>" method="POST">
    <section class="list-con list-con3">
        <ul id="list">
            <?php foreach($intent as $key=>$in){?>
                <li>
                    <div>
                        <span><?php echo $in['gh']?></span><span><?php echo $in['gg']?></span><span><?php echo $in['gc']?></span><span><?php echo $in['cd']?>米</span><span><em class="onePrice"><?php echo $in['price']?></em>元/吨</span>
                    </div>
                    <div>
                        <span class="total_price sale_style">¥<em>0</em></span><small><?php echo $in['bz']?></small>
                        <p class="btn clear">
                            <em>-</em><strong><input type="number" name="note[<?php echo $key ?>][sl]" value="1" class="inputNum" max="<?php echo $in['max_sl']?>"><i>吨</i></strong><em>+</em>
                        </p>
                    </div>
                    <input type="hidden" name="note[<?php echo $key ?>][gh]" value="<?php echo $in['gh'] ?>">
                    <input type="hidden" name="note[<?php echo $key ?>][gg]" value="<?php echo $in['gg'] ?>">
                    <input type="hidden" name="note[<?php echo $key ?>][gc]" value="<?php echo $in['gc'] ?>">
                    <input type="hidden" name="note[<?php echo $key ?>][cd]" value="<?php echo $in['cd'] ?>">
                    <input type="hidden" name="note[<?php echo $key ?>][price]" value="<?php echo $in['price'] ?>">
                    <input type="hidden" name="note[<?php echo $key ?>][bz]" value="<?php echo $in['bz'] ?>">
                    <input type="hidden" class="sale_id" value="<?php echo $key ?>">
                </li>
            <?php }?>
        </ul>
        <div class="total">
            <p>
                <em>数量：</em><span><i>0</i>吨</span><em>总计：</em><span class="sale_style">¥0</span>
            </p>
            <div class="capital"></div>
        </div>

    </section>
    <section class="qd-bot">
        <header>
            请核对联系方式
        </header>
        <ul>
            <li>
                <span>联系人：</span><input type="text" name="link_man" value="" maxlength="10">
            </li>
            <li>
                <span>联系方式：</span><input type="number" name="link_phone" value="15158891987">
            </li>
            <li>
                <span>公司名称：</span><input type="text" name="link_company" value="杭州包昆特殊钢配送有限公司" maxlength="30">
            </li>
            <li>
                <span>收货地址：</span><input type="text" name="delivery_address" value="杭州市拱墅区共康路77号富康大厦1503室" maxlength="50">
            </li>
        </ul>
    </section>
    <section class="submitBtn">
        <input type="hidden" value="" name="companyid" class="companyId">
        <input type="submit" value="提交清单">
    </section>
</form>


<?php inc_js('jquery-3.0.0.min')?>
<?php inc_js('qd')?>
<script>
    var hostUrl='<?php echo base_url("Cmateriel")?>';
    var uid=window.navigator.userAgent.split("uhelper")[1];

    $.post(hostUrl+"/get_user",{"uid":uid},function(data){
        console.log(data);
        if(data.status){

            $(".companyId").val(data.user.companyid);
            $(".qd-bot input").eq(0).val(data.user.link_man);
            $(".qd-bot input").eq(1).val(data.user.link_phone);
            $(".qd-bot input").eq(2).val(data.user.link_company);
            $(".qd-bot input").eq(3).val(data.user.delivery_address);
        }else{
            alert(data.msg);
        }
    },"json");
</script>
</body>
</html>