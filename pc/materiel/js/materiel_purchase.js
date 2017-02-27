/**
 * Created by Administrator on 2017/2/23.
 */

/*下拉封装*/
function SelectBox(opt){
    if(!opt){
        return;
    }
    this.$btn=opt.btn;
    this.$list=opt.list;
    this.init();
}
SelectBox.prototype={
    constructor:SelectBox,
    init:function(){
        this.inputEvent();
        this.documentEvent();
        this.listEvent();
    },
    inputEvent:function(){
        var _self=this;
        this.$btn.on("click",function(event){
            _self.$list.toggle();
            event.stopPropagation();
        });
    },
    documentEvent:function(){
        var _self=this;
        $(document).on("click",function(){
            _self.$list.hide();
        });
    },
    listEvent:function(){
        var _self=this;
        this.$list.on("click","b",function(){
            $(this).addClass("active").siblings().removeClass("active");
            _self.$btn.val($(this).text());
        });
    }
};


/*下拉调用*/
for(var i=0;i<$(".mat_list3").length;i++){
    new SelectBox({
        btn:$(".mat_list3").eq(i).find("input"),
        list:$(".mat_list3").eq(i).find("div")
    });
}



/*增加需求*/
function AddList(opt){
    if(!opt){
        return;
    }
    this.$table=opt.table;
    this.$form=opt.form;
    this.$isTrue=opt.isTrue;
    this.$addTrBtn=opt.addTrBtn;
    this.$submit=opt.submit;
    this.$baseMessage=opt.baseMessage;

    this.totalPrice=0;

    this.lastTr=this.$table.find("tr").last();
    this.index=this.$table.find("tbody tr").length-2;
    this.init();
}

AddList.prototype={
    constructor:AddList,
    init:function(){
        this.addValidate();
        this.continueAdd();
        this.removeList();
        this.submitEvent();
        this.default();
    },
    addValidate:function(){
        var Form=this.$form;
        var _self=this;

        _self.$isTrue.on("click",function(){

            var aInput=Form.find("input");
            if(!aInput.eq(0).val()){
                layer.msg('采购钢种不能为空',{icon:2,time:1000});
                return;
            }
            if(!/^\d{1,3}$/ig.test(aInput.eq(1).val())){
                layer.msg('采购规格只能为纯数字且只能输入1-3位数',{icon:2,time:1000});
                aInput.eq(1).val("");
                return;
            }
            if(!(/^\d+$/ig.test(aInput.eq(2).val()))){
                layer.msg('采购数量只能是数字且不能超过4位数',{icon:2,time:1000});
                return;
            }
            if(!aInput.eq(3).val()){
                layer.msg('采购钢厂不能为空',{icon:2,time:1000});
                return;
            }
            _self.addTr(aInput,Form.find("textarea"));
        });
    },
    default:function(){
        var _self=this;
        if(this.$table.find("tbody tr").length>1){
            _self.reset();
            _self.priceAll();
        }
    },
    addTr:function(aInput,textarea){
        var tr="";
        var s=textarea.val()==""?"/":textarea.val();

        this.index++;

        tr+='<tr>' +
                '<td>'+aInput.eq(0).val()+'</td>' +
                '<td>Ф'+aInput.eq(1).val()+'</td>' +
                '<td>'+aInput.eq(2).val()+'</td>' +
                '<td>'+aInput.eq(3).val()+'</td>' +
                '<td>' +
                    '<span>'+s+'</span>' +
                '</td>' +
                '<td>' +
                    '<a href="javascript:void(0);">删除</a>'+
                    '<input type="hidden" name="note['+this.index+'][gh]" value="'+aInput.eq(0).val()+'">' +
                    '<input type="hidden" name="note['+this.index+'][gg]" value="Ф'+aInput.eq(1).val()+'">' +
                    '<input type="hidden" name="note['+this.index+'][sl]" value="'+aInput.eq(2).val()+'">' +
                    '<input type="hidden" name="note['+this.index+'][gc]" value="'+aInput.eq(3).val()+'">' +
                    '<input type="hidden" name="note['+this.index+'][bz]" value="'+textarea.val()+'">'+
                '</td>' +
            '</tr>';

        aInput.val("");
        textarea.val("");
        this.lastTr.before(tr);
        this.priceAll();
        this.reset();
    },
    continueAdd:function(){
        var _self=this;
        this.$addTrBtn.on("click",function(){
            _self.showInput();
        });
    },
    removeList:function(){
        var _self=this;
        this.$table.on("click","a",function(){
           $(this).closest("tr").remove();
            _self.priceAll();
            if(_self.$table.find("tbody tr").length<2){
                _self.showInput();
            }
        });
    },
    submitEvent:function(){
        var _self=this;
        var aInput=_self.$baseMessage.find("input");

        _self.$submit.on("click",function(){
            if(_self.$table.find("tbody tr").length<2){
                layer.msg('采购需求未确定',{icon:2,time:1000});
                return false;
            }
            if(!aInput.eq(0).val()){
                layer.msg('联系人不能为空',{icon:2,time:1000});
                return false;
            }
            if(!/^1\d{10}$/.test(aInput.eq(1).val())){
                layer.msg('手机号输入有误',{icon:2,time:1000});
                return false;
            }
            if(!aInput.eq(2).val()){
                layer.msg('公司名字不能为空',{icon:2,time:1000});
                return false;
            }
        });
    },
    priceAll:function(){
        var tr=this.$table.find("tbody tr");
        var iLen=this.$table.find("tbody tr").length-1;
        this.totalPrice=0;

        for(var i=0;i<iLen;i++){
            this.totalPrice+=Number(tr.eq(i).find("td").eq(2).text());
        }
        this.$table.find("strong").text(this.totalPrice);
    },
    showInput:function(){
        this.$isTrue.css("display","block");
        this.$addTrBtn.css("display","none");
        this.$form.show();
    },
    reset:function(){
        this.$form.hide();
        this.$isTrue.css("display","none");
        this.$addTrBtn.css("display","block");
        this.$form.find("b").removeClass("active");
    }
};

/*调用*/
new AddList({
    table:$("#mat_table"),
    form:$("#mat_input"),
    isTrue:$("#isTrue"),
    addTrBtn:$("#addTr"),
    submit:$("#mat_sub"),
    baseMessage:$("#mat_input2")
});