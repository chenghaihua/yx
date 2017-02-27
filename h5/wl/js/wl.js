/**
 * Created by Administrator on 2017/1/20.
 */
;(function(){

    var hostUrl="";   //请求地址


    function Add(options){
        this.$trueBtn=$(options.trueBtn);
        this.$addBtn=$(options.addBtn);
        this.$total=$(options.total);
        this.$table=$(options.table);
        this.$inputWrap=$(options.inputWrap);
        this.submit=$(options.submit);
        this.index=this.$table.find("tbody tr").length-1;
        this.totalValue=0;
    }
    Add.prototype={
        constructor:Add,
        init:function(){
            this.addEvent();
        },
        addEvent:function(){
            this.trueEvent();
            this.removeTr();
            this.submitEvent();
            this.addClick();
        },
        trueEvent:function(){
            var _self=this;
            this.$trueBtn.on("click",function(){
                var input=_self.$inputWrap.find("select");
                if(!_self.validate(input)) return false;
                _self.iT=0;
                if(!_self.$table.find("tbody tr").length){
                    _self.$table.show();
                    _self.addTr(input);
                    _self.resetInput();
                    return false;
                }

                _self.$table.find("tbody tr").each(function(index){

                    if(input.eq(0).val()==$(this).find("input").eq(0).val()&&_self.$inputWrap.find('input').eq(0).val()==$(this).find("input").eq(1).val().substring(1)){

                        $(".pop_wrap").show();
                        $(".pop_btn a").eq(0).on("click",function(){
                            if($("#checked select").eq(0).val()){
                                _self.$table.show();
                                _self.addTr(input);
                                _self.resetInput();
                            }
                            $(".pop_wrap").hide();
                        });

                        $(".pop_btn a").eq(1).on("click",function(){
                            _self.iT=0;
                            $(".pop_wrap").hide();
                        });
                        return true;
                    }else{
                        if(index==$(this).length-1){
                            _self.iT=2;
                        }
                    }
                });
                if(_self.iT==2){

                    _self.$table.show();
                    _self.addTr(input);
                    _self.resetInput();
                }
            });
        },
        addClick:function(){
            var _self=this;
            this.$addBtn.on("click",function(){

                var inp=_self.$inputWrap.find("select");
                _self.totalValue=0;
                _self.$inputWrap.show();
                _self.$trueBtn.css("display","block");
                $(this).css("display","none");
            });
        },
        validate:function(input){   //验证

            if(!input.eq(0).val()){
                alert("采购钢种不能为空");
                return false;
            }

            if(!/^\d{1,3}$/ig.test(this.$inputWrap.find('input').eq(0).val())){
                alert("采购规格只能为纯数字且只能输入1-3位数");
                this.$inputWrap.find('input').eq(0).val("");
                return false;
            }
            if(!/^\d{1,4}$/ig.test(this.$inputWrap.find('input').eq(1).val())){
                this.$inputWrap.find('input').eq(1).val("");
                alert("采购数量只能是数字且不能超过4位数");
                return false;
            }
            return true;
        },
        addTr:function(input){
            var _self=this;
            this.index=this.$table.find("tbody tr").length-1;
            this.index++;
            var tr='<tr>'+
                '<td><input type="hidden" name="note['+this.index+'][gh]" value="'+input.eq(0).val()+'">'+input.eq(0).val()+'</td>'+
                '<td><input type="hidden" name="note['+this.index+'][gg]" value="Ф'+this.$inputWrap.find('input').eq(0).val()+'">Ф'+this.$inputWrap.find('input').eq(0).val()+'</td>'+
                '<td><input type="hidden" class="t_num" name="note['+this.index+'][sl]" value="'+this.$inputWrap.find('input').eq(1).val()+'">'+this.$inputWrap.find('input').eq(1).val()+
                '<td><input type="hidden" name="note['+this.index+'][gc]" value="'+input.eq(1).val()+'">'+input.eq(1).val()+'</td>'+
                '<input type="hidden" name="note['+this.index+'][bz]" value="'+this.$inputWrap.find('input').eq(2).val()+'">'+this.$inputWrap.find('input').eq(2).val()+
                '</td>'+
                '<td><span></span></td>'+
                '</tr>';

            if(this.$table.find("tbody tr").length>0){
                this.$table.find(".t_num").each(function(index){
                    _self.totalValue+=Number($(this).val());
                });
            }
            this.$total.html(_self.totalValue+Number(this.$inputWrap.find('input').eq(1).val()));

           // this.$total.find("i").
            this.$table.show().find("tbody").append(tr);
            this.$inputWrap.hide();
            this.$trueBtn.css("display","none");
            this.$addBtn.css("display","block");

        },
        resetInput:function(){
            this.$inputWrap.find("select").val('');
            this.$inputWrap.find("input").val('');
        },

        removeTr:function(){
            var _self=this;
            this.$table.on("click","span",function(){
                _self.$total.html(_self.$total.html()- $(this).closest("tr").find(".t_num").val());
                $(this).closest("tr").remove();
                if(_self.$table.find("tbody tr").length==0){
                    _self.$table.hide();
                    _self.$inputWrap.show();
                    _self.$trueBtn.css("display","block");
                    _self.$addBtn.css("display","none");
                }
            });
        },
        submitEvent:function(){
            var _self=this;
            _self.submit.on("click",function(){
                var select=_self.$inputWrap.find("select");
                var input=$(".checked2").find("input");
                if(_self.$table.find("tbody tr").length<1){
                    alert('采购需求未确定');
                    return false;
                }
                if(!input.eq(0).val()){
                    alert("联系人不能为空");
                    return false;
                }
                if(!/^1\d{10}$/.test(input.eq(1).val())){
                    alert("手机号输入有误");
                    return false;
                }
                if(!input.eq(2).val()){
                    alert("公司名字不能为空");
                    return false;
                }
            });
        }
    };

    new Add({
        trueBtn:".trueBtn",
        addBtn:'.addBtn',
        table:".table-show",
        inputWrap:"#checked",
        total:".total i",
        submit:'.submit'
    }).init();


    /*获取uid*/
    var uid=window.navigator.userAgent.split("uhelper")[1];

    $.post("http://h5.uhelper.cn/Cmateriel/get_user",{"uid":uid},function(data){
        if(data.status){
            $(".checked2 input").eq(0).val(data.user.link_man);
            $(".checked2 input").eq(1).val(data.user.link_phone);
            $(".checked2 input").eq(2).val(data.user.link_company);
            $(".checked2 input[name=companyid]").val(data.user.companyid);
            var materiel_setid=$(".checked2 input[name=materiel_setid]").val();

            last_purchase(data.user.companyid,materiel_setid);
        }else{
            alert(data.msg);

        }

    },"json");


    function last_purchase(companyid,materiel_setid){
        $.post("http://h5.uhelper.cn/Cmateriel/last_purchase",{companyid:companyid,materiel_setid:materiel_setid},function(data2){

            if(data2.status){
                var str="";
                var arr=data2.data.purchase;
                var totalValue2=0;

                $("#checked").hide();
                $(".trueBtn").css("display","none");
                $(".addBtn").css("display","block");

               $(".checked2 input[name=purchaseid]").val(data2.data.purchaseid);

                 for(var i in arr){
                     str += '<tr>' +
                         '<td><input type="hidden" name="note[' + i + '][gh]" value="' + arr[i]['gh'] + '">' + arr[i]['gh'] + '</td>' +
                         '<td><input type="hidden" name="note[' + i + '][gg]" value="' + arr[i]['gg'] + '">' + arr[i]['gg'] + '</td>' +
                         '<td><input type="hidden" class="t_num" name="note[' + i + '][sl]" value="' + arr[i]['sl'] + '">' + arr[i]['sl'] +
                         '<td><input type="hidden" name="note[' + i + '][gc]" value="' + arr[i]['gc'] + '">' + arr[i]['gc'] + '</td>' +
                         '<input type="hidden" name="note[' + i + '][bz]" value="' + arr[i]['bz'] + '">' + arr[i]['bz'] +
                         '</td>' + '<td><span></span></td>' +
                         '</tr>';
                 }

                $(".table1").parent().show().end().find("tbody").append(str);

                if($(".table1").find("tbody tr").length>0){
                    $(".table1").find(".t_num").each(function(index){
                        totalValue2+=Number($(this).val());
                    });
                }
                $(".total i").html(totalValue2);
            }
        },"json");
    }
})();