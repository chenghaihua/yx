<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo base64_decode($this->input->get('gh'))?></title>
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
<section class="choice">
    <span>筛选规格<em></em></span>
    <ul>
        <li>全部</li>
        <?php foreach($gg_arr as $gg){?>
        <li data-gg="<?php echo base64_encode($gg)?>"><?php echo $gg;?></li>
        <?php }?>
    </ul>
</section>
<section class="list-con list-con2" id="wrapper">
    <div id="scroller">
        <div id="pullDown" class=""><div class="pullDownLabel"></div></div>
        <div class="pulldown-tips">下拉刷新</div>
        <ul id="list"></ul>
        <div id="pullUp" class="">
            <div class="pullUpLabel">加载更多</div>
        </div>
    </div>
</section>
<input type="hidden" value="<?php echo $sale_num ?>" class="total_num">
<?php inc_js('jquery-3.0.0.min')?>
<?php inc_js('iscroll-probe')?>
<script>

    /*插入数据*/
    var gh2="<?php echo $this->input->get('gh')?>";

    var hostUrl='<?php echo base_url("Cmateriel")?>';
    var uid=window.navigator.userAgent.split("uhelper")[1];
    var str3="";
    var sl=0;
    var dataLength;
    var its=true;

    function getList(pg,gh,gg){

        $.get(hostUrl+"/sale_list",{pg:pg,gh:gh,gg:gg},function(data){
            if(data.status){
                str3="";
                var dataMes=data.data;
                var active2="";
                var it=0;
                var valueTit="";

                dataLength=dataMes.length;
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
                        }else{
                            valueTit='加入采购清单';
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
                        '<i>'+dataMes[i].bz+'</i>'+
                        '<a href="javascript:void(0);" data-itrue="'+it+'" class='+active2+'>'+valueTit+'</a>' +
                        '</div>' +
                        '</li>';
                }
                //console.log(str3);


                $(".list-con ul").append(str3);

                if(its){
                    load (gg);
                    its=false;
                }
                clickEvent($("#list li"),sl);
                sl+=dataMes.length;

                clickEvent($(".list-con li"));
            }else{
                alert(data.msg);
            }

        },"json");
    }

    //加入购物清单
    function clickEvent(obj,num){

        obj.each(function(index){
            if(index>=num){
                var sale_id=$(this)[0].dataset.sale_id;
                var sale_sl=$(this).find(".sale_num").html();

                $(this).on("click",'a',function(){
                    var _self=$(this);
                    var t=$(this)[0].dataset.itrue;

                    $(this).html("已加入采购清单");
                    if(!Number(t)){
                        addList(sale_id,sale_sl,_self);
                    }
                });
            }
        });
    }

    var num4=Number($(".total_num").val());
    if(window.AndroidWebView){
        window.AndroidWebView.showInfoFromJs(num4);    // android
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

    getList(0,gh2,"");

    /*
    * 筛选规格
    * */
    function Choice(options){
        this.$wrap=options.wrap;
        this.init();
    }
    Choice.prototype={
        constructor:Choice,
        init:function(){
            this.btnEvent();
            this.listEvent();
            this.hideBox();
            this.scrollMove();
        },
        btnEvent:function(){
            var _self=this;
            this.$wrap.on("click","span",function(event){
                _self.slideBox(event);
            });
        },
        listEvent:function(){
            var _self=this;
            this.$wrap.find("li").each(function(index){
                $(this).on("click",function(event){  //规格筛选
                    
                    var _this=$(this);
                    _this.addClass("active").siblings().removeClass("active");
                    _self.slideBox(event);

                    var val=$(this)[0].dataset.gg; //提交请求
                    if(val=="全部"){
                        val="";
                    }
                    $("#list").html("");  //先清空列表
                    sl=0;
                    its=true;
                    myScroll.refresh();
                    getList(0,gh2,val);
                });
            })
        },
        slideBox:function(event){
            event.stopPropagation();
            this.$wrap.find("ul").slideToggle(100);
        },
        hideBox:function(){
            var _self=this;
            $(document).on("click",function(){
                _self.$wrap.find("ul").slideUp(100);
            });
        },
        scrollMove:function(){
            this.$wrap.find("ul").on("touchmove",function(){
                document.addEventListener('touchmove', function (e) {return false;}, false);
            });
        }
    };

    /*
     * 筛选规格调用
     * */
    new Choice({
        "wrap":$(".choice")
    });
    var myScroll;
    function load (val3) {
        var pullDown = $("#pullDown"),
            pullUp = $("#pullUp"),
            pullDownLabel = $(".pullDownLabel"),
            pullUpLabel = $(".pullUpLabel"),
            container = $('#list'),
            num5=0,
            loadingStep = 0;//加载状态0默认，1显示加载状态，2执行加载数据，只有当为0时才能再次加载，这是防止过快拉动刷新

        pullDown.hide();
        pullUp.hide();

        myScroll = new IScroll("#wrapper", {
            scrollbars: true,
            mouseWheel: false,
            interactiveScrollbars: true,
            shrinkScrollbars: 'scale',
            fadeScrollbars: true,
            scrollY:true,
            probeType: 2,
            bindToWrapper:true,
            preventDefault:false
        });
        myScroll.on("scroll",function(){

            if(loadingStep == 0 && !pullDown.attr("class").match('refresh|loading') && !pullUp.attr("class").match('refresh')){
                if(this.y > 40){//下拉刷新操作

                    $(".pulldown-tips").hide();
                    pullDown.addClass("refresh").show();
                    pullDownLabel.text("松手刷新数据");
                    loadingStep = 1;
                    myScroll.refresh();
                }else if(this.y < (this.maxScrollY - 14)){//上拉加载更多

                    pullUp.addClass("refresh").show();
                    pullUpLabel.text("正在载入");
                    loadingStep = 1;
                    pullUpAction();
                }
            }
        });
        myScroll.on("scrollEnd",function(){
            if(loadingStep == 1){
                if( pullDown.attr("class").match("refresh") ){//下拉刷新操作
                    pullDown.removeClass("refresh").addClass("loading");
                    pullDownLabel.text("正在刷新");
                    loadingStep = 2;
                    pullDownAction();
                }
            }
        });

        function pullDownAction(){
            pullDown.attr('class','').hide();
            myScroll.refresh();
            loadingStep = 0;
            $(".pulldown-tips").show();
        }
        function pullUpAction(){

            setTimeout(function(){
                num5++;
               getList(num5,gh2,val3);
                pullUp.attr('class','').hide();
                myScroll.refresh();
                loadingStep = 0;
                if(dataLength==0){
                    alert("没有更多数据了");
                }
            },600);
        }

        document.addEventListener('touchmove', function (e) {return false;}, false);

    }

</script>
</body>
</html>