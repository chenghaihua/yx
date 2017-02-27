/**
 * Created by Administrator on 2017/1/21.
 */
(function(){
    var hostUrl="http://h5.uhelper.cn/Cmateriel/";

    //去除ios下弹窗自带网址
    var browser={
        versions:function(){
            var u = navigator.userAgent, app = navigator.appVersion;
            return {
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1, //是否iPad
                webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
            };
        }(),
        language:(navigator.browserLanguage || navigator.language).toLowerCase()
    }

    if(browser.versions.ios){
        window.alert = function(name){
            var iframe = document.createElement("IFRAME");
            iframe.style.display="none";
            iframe.setAttribute("src", 'data:text/plain,');
            document.documentElement.appendChild(iframe);
            window.frames[0].window.alert(name);
            iframe.parentNode.removeChild(iframe);
        };

        window.confirm = function (message) {
            var iframe = document.createElement("IFRAME");
            iframe.style.display = "none";
            iframe.setAttribute("src", 'data:text/plain,');
            document.documentElement.appendChild(iframe);
            var alertFrame = window.frames[0];
            var result = alertFrame.window.confirm(message);
            iframe.parentNode.removeChild(iframe);
            return result;
        };
    }



    //数字转换成货币格式
    Number.prototype.formatMoney = function (places, symbol, thousand, decimal) {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand = thousand || ",";
        decimal = decimal || ".";
        var number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number -                i).toFixed(places).slice(2) : "");
    };

    //数字转化为中文大写
    function DX(n) {
        if (!/^(0|[1-9]\d*)(\.\d+)?$/.test(n))
            return "数据非法";
        var unit = "千百拾亿千百拾万千百拾元角分", str = "";
        n += "00";
        var p = n.indexOf('.');
        if (p >= 0)
            n = n.substring(0, p) + n.substr(p+1, 2);
        unit = unit.substr(unit.length - n.length);
        for (var i=0; i < n.length; i++)
            str += '零壹贰叁肆伍陆柒捌玖'.charAt(n.charAt(i)) + unit.charAt(i);
        return str.replace(/零(千|百|拾|角)/g, "零").replace(/(零)+/g, "零").replace(/零(万|亿|元)/g, "$1").replace(/(亿)万|壹(拾)/g, "$1$2").replace(/^元零?|零分/g, "").replace(/元$/g, "元整");
    }


    function Add(option){
        this.$listWrap=option.listWrap;
        this.$total=option.total;
        this.init();
    }
    Add.prototype={
        constructor:Add,
        init:function(){
            this.forEach();
            this.loadingValue();
        },
        loadingValue:function(){
            var _self=this;
            _self.addTotalNum();

        },
        forEach:function(){
            var _self=this;
            this.$listWrap.find("li").each(function(index){
                _self.reductionEvent($(this).find(".btn em").eq(0));
                _self.addEvent($(this).find(".btn em").eq(1));
                _self.inputChangeEvent($(this).find(".inputNum"));
                _self.everyTotalPrice($(this).find(".total_price"),$(this).find("input").val(),$(this).find(".onePrice"));
            });
        },
        everyTotalPrice:function(obj,num,price){
            obj.find("em").html(Number(num*price.html()).formatMoney(1,''));
        },
        addEvent:function(obj){  //加
            var _self=this;
            obj.on("touchstart",function(){
                var input=$(this).siblings().find(".inputNum"),
                    maxVal=parseInt(input.attr("max"));
                Num=Number(input.val());
                Num++;

                if(Num>maxVal){
                    Num=maxVal;
                    alert("最大值只能是"+maxVal+"吨");
                }

                $(this).siblings().find(".inputNum").val(Num);
                _self.everyTotalPrice($(this).closest("p").siblings(".total_price"),Num,$(this).closest("li").find(".onePrice"));
                _self.addTotalNum();
            });
        },
        reductionEvent:function(obj){    //减
            var _self=this;
            obj.on("touchstart",function(){
                var Num=Number($(this).siblings().find(".inputNum").val());
                var that=$(this);
                Num--;
                if(Num<1){
                    var s=confirm("是否删除此钢材，确定后此钢材不在清单中显示");
                    if(s){
                       var sale_id=that.closest("li").find(".sale_id").val();

                        $.post(hostUrl+"intent_add",{'sale_id':sale_id,'sale_sl':0},function(data){

                            if(data){
                                that.closest("li").remove();
                            }

                        },'json');
                    }else{
                        return false;
                    }
                }
                $(this).siblings().find(".inputNum").val(Num);
                _self.everyTotalPrice($(this).closest("p").siblings(".total_price"),Num,$(this).closest("li").find(".onePrice"));
                _self.addTotalNum();
            });
        },
        inputChangeEvent:function(obj){   //输入数字
            var _self=this;
            obj.on("input",function(){

                if(Number($(this).val())>parseInt($(this).attr("max"))){
                    $(this).val(parseInt($(this).attr("max")));
                    alert("最大值只能是"+parseInt($(this).attr("max")));
                }
                _self.everyTotalPrice($(this).closest("p").siblings(".total_price"),$(this).val(),$(this).closest("li").find(".onePrice"));
               _self.addTotalNum();
            });
            obj.on("blur",function(){
                if(Number($(this).val())<=0){
                    $(this).val("1");
                    alert("只能输入正整数");
                }
                if(Number($(this).val())<1){
                    var s=confirm("是否删除此钢材，确定后此钢材不在清单中显示");
                    if(s){
                        $(this).closest("li").remove();
                    }else{
                        return false;
                    }
                }
                _self.everyTotalPrice($(this).closest("p").siblings(".total_price"),$(this).val(),$(this).closest("li").find(".onePrice"));
                _self.addTotalNum();
            });
        },
        addTotalNum:function(){  //统计
            var _self=this;
            this.totalNum=0;
            this.totalPrice=0;

            this.$listWrap.find(".inputNum").each(function(index){
                _self.totalNum+=Number($(this).val());
                //_self.totalPrice+=Number($(this).closest('li').find(".total_price em").html());
                 _self.totalPrice+=Number($(this).val())*Number($(this).closest("li").find(".onePrice").html());
                console.log(_self.totalPrice);
            });

            this.$total.find("i").html(this.totalNum);
            this.$total.find("span").eq(1).html(this.totalPrice.formatMoney(2,'¥'));
            this.$total.find(".capital").html(DX(Math.abs(this.totalPrice)));
        }
    };

    new Add({
        "listWrap":$("#list"),
        "total":$(".total")
    });


    /*验证*/
    function Reg(options){
        this.$btn=options.btn;
        this.$input=options.input;
        this.init();
    }
    Reg.prototype={
        constructor:Reg,
        init:function(){
            this.submitEvent();
        },
        submitEvent:function(){
            var _self=this;
            this.$btn.on("click",function(){

                var input=_self.$input;

                if(!input.eq(0).val()){
                    alert("联系人不能为空");
                    return false;
                }
                if(!/^1\d{10}$/.test(input.eq(1).val())){
                    input.eq(1).val("");
                    alert("手机号输入有误");
                    return false;
                }
                if(!input.eq(2).val()){
                    alert("公司名字不能为空");
                    return false;
                }
                if(!input.eq(3).val()){
                    alert("收货地址不能为空");
                    return false;
                }

                if(!$("#list").find("li").length){
                    alert("采购清单不能为空");
                    return false;
                }
                var s=confirm("是否提交采购清单");
                if(s) {
                    return true;
                }else{
                    return false;
                }
            });
        }
    };

    new Reg({
        "btn":$(".submitBtn input[type=submit]"),
        "input":$(".qd-bot input")
    })

})();