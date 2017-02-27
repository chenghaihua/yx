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
    <div class="clear">
        <?php foreach($gh_arr as $gh){?>
            <a href="<?php echo base_url('Cmateriel/sale_gh?gh='.base64_encode($gh));?>"><?php echo $gh?></a>
        <?php }?>
    </div>
    <div>
        <span>钢种</span><span>规格</span><span>钢厂</span><span>长度</span><span>数量</span>
    </div>
</section>
<section class="list-con" id="wrapper">
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

    var hostUrl='<?php echo base_url("Cmateriel")?>';
    var uid=window.navigator.userAgent.split("uhelper")[1];
    var str3="";
    var sl=0;
    var dataLength;
    var its=true;
    var isT=true;

    /*插入数据*/

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
                isT=true;
                if(its){

                    load ();
                    its=false;
                }

                clickEvent($("#list li"),sl);
                sl+=dataMes.length;
            }else{
                alert(data.msg);
            }

        },"json");
    }



    function clickEvent(obj,num){

        obj.each(function(index){
            if(index>=num){
                var sale_id=$(this)[0].dataset.sale_id;
                var sale_sl=$(this).find(".sale_num").html();


                $(this).on("click",'a',function(){
                    var _self=$(this);
                    var t=$(this)[0].dataset.itrue;

                    alert(sale_sl);

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


    getList(0,"","");

    $(".list-con").css("top",$(".header").innerHeight());


    function load () {
        var myScroll,
            pullDown = $("#pullDown"),
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
                                myScroll.refresh();
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
            /*num=0;   下拉刷新数据
            $("#list").html("");
            its=true;
            myScroll.refresh();
            getList(num5,"","");*/
            pullDown.attr('class','').hide();
            myScroll.refresh();
            loadingStep = 0;
            $(".pulldown-tips").show();
        }
        function pullUpAction(){

            setTimeout(function(){
                if(!isT){
                    return;
                }
                num5++;
                getList(num5,"","");
                pullUp.attr('class','').hide();
                myScroll.refresh();
                loadingStep = 0;
                if(dataLength==0){
                    alert("没有更多数据了");
                }
                isT=false;
            },600);
        }

        document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    }
</script>
</body>
</html>