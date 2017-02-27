<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>商品列表</title>
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
<section class="header">
    <div>
        <?php foreach($gh_arr as $gh){?>
            <a href="<?php echo base_url('Cmateriel/sale_gh?gh='.base64_encode($gh));?>"><?php echo $gh?></a>
        <?php }?>
    </div>
    <div>
        <span>钢种</span><span>规格</span><span>钢厂</span><span>长度</span><span>数量</span>
    </div>
</section>
<section class="list-con">
    <ul></ul>
</section>
<input type="hidden" value="<?php echo $sale_num ?>" class="total_num">
<?php inc_js('jquery-3.0.0.min')?>
<script>

    var hostUrl='<?php echo base_url("Cmateriel")?>';
    var uid=window.navigator.userAgent.split("uhelper")[1];
    var str3="";
    /*插入数据*/

    function getList(pg,gh,gg){

        $.get(hostUrl+"/sale_list",{pg:pg,gh:gh,gg:gg},function(data){

            if(data.status){
                str3="";
                var dataMes=data.data;
                var active2="";
                var it=0;
                var valueTit="";
                for(var i=0,iLen=dataMes.length;i<iLen;i++){

                    if(dataMes[i].selected){
                        valueTit='已加入采购清单';
                        active2="active";
                        it=1;

                    }else{
                        if(dataMes[i].on_sale==1){
                            valueTit='加入采购清单';
                            active2="";
                            it=0;
                        }else if(dataMes[i].on_sale==2){
                            valueTit='售罄';
                            active2="active";
                            it=1;
                        }
                    }



                    str3+='<li data-sale_id="'+dataMes[i].id+'">' +
                        '<div>' +
                        '<span>'+dataMes[i].gh+'</span>' +
                        '<span>'+dataMes[i].gg+'</span>' +
                        '<span>'+dataMes[i].gc+'</span>' +
                        '<span>'+dataMes[i].cd+'米</span>' +
                        '<span class="sale_num">'+dataMes[i].sl+'吨</span>' +
                        '</div>' +
                        '<div>' +
                        '<span class="sale_style">'+dataMes[i].price+'元/吨</span>' +
                        '<a href="javascript:void(0);" data-itrue="'+it+'" class='+active2+'>'+valueTit+'</a>' +
                        '</div>' +
                        '</li>';


                }
                //console.log(str3);


                $(".list-con ul").html(str3);

                clickEvent($(".list-con li"));
            }else{
                alert(data.msg);
            }

        },"json");
    }

    //是否有uid
    function clickEvent(obj){

        $.post(hostUrl+"/get_user",{"uid":uid},function(data){
            if(data.status){
                obj.each(function(index){
                    var sale_id=$(this)[0].dataset.sale_id;
                    var sale_sl=$(this).find(".sale_num").html();

                    $(this).find("a").on("click",function(){
                        var _self=$(this);
                        var t=$(this)[0].dataset.itrue;
                        $(this).html("已加入采购清单");
                        if(!Number(t)){
                            addList(sale_id,sale_sl,_self);
                        }
                    });
                });
            }
        },'json');

    }

    var num4=Number($(".total_num").val());

    // android初始化
    if(window.AndroidWebView){
        window.AndroidWebView.showInfoFromJs(num4);
    }

    //提交数据
    function addList(sale_id,sale_sl,_self){
        $.post(hostUrl+"/intent_add",{"sale_id":sale_id,"sale_sl":sale_sl},function(data){
            if(data){
                num4++;

                if(window.AndroidWebView){
                    window.AndroidWebView.showInfoFromJs(num4);    // android
                }else{
                    window.location.href="uhelperSaleNum://saleNum?"+num4;    //ios
                }
                _self.addClass("active");
                _self[0].dataset.itrue=1;
            }
        },"json");
    }


    getList(0,"","");

    $(".list-con").css("margin-top",$(".header").innerHeight());
</script>
</body>
</html>