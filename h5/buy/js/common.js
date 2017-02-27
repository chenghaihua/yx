/**
 * Created by Administrator on 2017/1/22.
 */
var hostUrl="http://h5.uhelper.cn/Cmateriel/";
var uid=window.navigator.userAgent.split("uhelper")[1];
var str3="";
/*插入数据*/
function getList(pg,gh,gg){

    $.get(hostUrl+"sale_list",{pg:pg,gh:gh,gg:gg},function(data){
        if(data.status){
            str3="";
            var dataMes=data.data;
            var active2="";
            var it=0;
            for(var i=0,iLen=dataMes.length;i<iLen;i++){

                if(dataMes[i].selected){
                    active2="active";
                    it=1;
                }else{
                    active2="";
                    it=0;
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
                    '<span>'+dataMes[i].price+'元/吨</span>' +
                    '<a href="javascript:void(0);" data-itrue="'+it+'" class='+active2+'>加入采购清单</a>' +
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

    $.post(hostUrl+"get_user",{"uid":uid},function(data){
        if(data.status){
            obj.each(function(index){
                var sale_id=$(this)[0].dataset.sale_id;
                var sale_sl=$(this).find(".sale_num").html();

                $(this).find("a").on("click",function(){
                    var _self=$(this);
                   var t=$(this)[0].dataset.itrue;

                    if(!Number(t)){
                        addList(sale_id,sale_sl,_self);
                    }
                });
            });
        }
    },'json');

}

var num4=Number($(".total_num").val());
if(window.AndroidWebView){
    window.AndroidWebView.showInfoFromJs(num4);    // android
}

//提交数据
function addList(sale_id,sale_sl,_self){

    $.post(hostUrl+"intent_add",{"sale_id":sale_id,"sale_sl":sale_sl},function(data){
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
