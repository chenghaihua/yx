<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>采购活动</title>
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
    <?php inc_css('activitys')?>
</head>
<body style="background:#d82e00;">
    <img src="<?php echo base_url('assets/pic/activity_pic.jpg')?>" width="100%" alt="">
    <section>
        <div class="activity_hd">

            <a href="javascript:void(0);">
                集中采购
                <?php if($purchase>0){?>
                    <span>火热进行中</span>
                <?php }else{?>
                    <span>活动未开启</span>
                <?php }?>
            </a><a href="javascript:void(0);">
                物料展销
                <?php if($sale>0){?>
                    <span>火热进行中</span>
                <?php }else{?>
                    <span>活动未开启</span>
                <?php }?>
            </a>
        </div>
        <div class="activity_con">
            <div class="activity_list" style="display:block;">
                <a href="javascript:void(0);" data-url="<?php echo base_url('Cmateriel/purchase')?>" class="activity_join"><img src="<?php echo base_url('assets/pic/activity_pic3.jpg')?>" alt=""></a>
                <a href="javascript:void(0);" data-url="<?php echo base_url('Cmateriel/purchase')?>" class="activity_join activity_join2">点击进入</a>
            </div>
            <div class="activity_list">

                <a href="javascript:void(0);" data-url="<?php echo base_url('Cmateriel/sale')?>" class="activity_join"><img src="<?php echo base_url('assets/pic/activity_pic2.jpg')?>" alt=""></a>
                <a href="javascript:void(0);" data-url="<?php echo base_url('Cmateriel/sale')?>" class="activity_join activity_join2">点击进入</a>
            </div>
        </div>
    </section>

    <?php inc_js('jquery-3.0.0.min')?>
    <script>
        (function(){
            var hostUrl='<?php echo base_url("Cmateriel")?>';
            var uid=window.navigator.userAgent.split("uhelper")[1];

            /*
             * 切换
             * */
            function changeBox(options){
                this.$btn=options.btn;
                this.$conList=options.conList;
                this.$btn.first().addClass("active");
                this.$conList.first().show();
                this.clickEvent();
            }

            changeBox.prototype={
                constructor:changeBox,
                clickEvent:function(){
                    var _self=this;
                    this.$btn.each(function(index){
                        $(this).on("touchstart",function(){
                            $(this).addClass("active").siblings().removeClass("active");
                            _self.$conList.hide().eq(index).show();
                        });
                    });
                }
            };


            new changeBox({
                "btn":$(".activity_hd a"),
                "conList":$(".activity_con .activity_list")
            });

            
            if(window.AndroidWebView){
                window.AndroidWebView.hideNum('hideNum');   // android
            }

            $(".activity_join").each(function(index){
                $(this).on("touchstart",function(){
                    var _self=$(this);
                    $.post(hostUrl+'/get_user',{"uid":uid},function(data){
                        if(data.status){
                            if(window.AndroidWebView){
                                if(index==2||index==3){
                                    window.AndroidWebView.zx('zx');
                                }   // android
                                window.location.href=_self[0].dataset.url;
                            }else{
                                url=_self[0].dataset.url.split("://")[1];
                                window.location.href="uhelperURL://URL?"+url;    //ios
                            }

                        }else{
                            alert(data.msg);
                        }
                    },"json");
                });
            });


        })();
    </script>

</body>
</html>