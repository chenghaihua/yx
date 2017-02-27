/**
 * Created by Administrator on 2016/9/20.
 */

var YX={
    //手机适配
    setPxPerRem:function(){
        var cssEl=document.createElement("style");
        document.documentElement.firstElementChild.appendChild(cssEl);
        var dpr=1;
        var pxPerRem=document.documentElement.clientWidth*dpr/10;
        if(pxPerRem>60){
            pxPerRem=60;
        }
        cssEl.innerHTML='html{font-size:'+pxPerRem+'px!important;}';
        return this;
    },
    //300ms延迟
    delayClick:function(){

        if(document.querySelector("a")){
            var a=document.querySelector("a");
            for(var i=0;i< a.length;i++){
                a[i].addEventListener("touchstart",function(e){
                    e.preventDefault();
                });
                a[i].addEventListener("touchmove",function(){
                    this.isMove=true;
                });
                a[i].addEventListener("touchend",function(){
                    if(!this.isMove){
                        window.location.href=thi.href;
                    }
                    this.isMove=false;
                });
            }
        }
        return this;
    }

};
YX.setPxPerRem().delayClick();


