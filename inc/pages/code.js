//-------------------------------------------------------
//分頁列
//-------------------------------------------------------

    function pages(cid,numrow,psize,pnos,pinx,sinx,einx,list_size,url_args){
    //---------------------------------------------------
    //分頁列
    //---------------------------------------------------
    //參數
    //---------------------------------------------------
    //cid           容器id
    //numrow        資料總筆數
    //psize         單頁筆數
    //pnos          分頁筆數
    //pinx          目前所在頁
    //sinx          目前所在頁,值域起始值
    //einx          目前所在頁,值域終止值
    //list_size     分頁列顯示筆數
    //url_args      連結資訊
    //---------------------------------------------------
    //回傳值
    //---------------------------------------------------
    //本函式會傳回容器物件,你可以透過 容器物件.tbl 取得
    //分頁列表格 物件.
    //---------------------------------------------------

        //分頁列區段
        var arry_list=[];   //分頁列資料陣列
        var s_sinx   =0;    //分頁列區段,值域起始值
        var s_einx   =0;    //分頁列區段,值域終止值
        var s_sinx   =(get_seinx()).s_sinx;
        var s_einx   =(get_seinx()).s_einx;

        //連結資訊
        var pinx_name =url_args.pinx_name;
        var psize_name=url_args.psize_name;
        var page_name =url_args.page_name;
        var page_args =parse_page_args(url_args.page_args);

        //容器
        var opage=document.getElementById(cid);
        opage.className="page_container";

        //表格
        var otbl =document.createElement("TABLE");
        otbl.className="page_tbl";

        //列
        var otr  =otbl.insertRow(-1);
        otr.className="page_tr";

        //資訊欄位
        var otd_info=otr.insertCell(-1);
        otd_info.className="page_info";
        otd_info.innerHTML="第"+sinx+"筆~第"+einx+"筆"+":"+"共"+numrow+"筆";

        //第一頁
        if(s_sinx!=1){
            var otd_first=otr.insertCell(-1);
            otd_first.className="page_first";
            otd_first.innerHTML="第一頁";
            otd_first.cls="page_first";

            var _pinx =1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_first._pinx =_pinx;
            otd_first._psize=_psize;
            otd_first._url  =_url;

            otd_first.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_first.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_first.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //上一頁
        if(s_sinx>1){
            var otd_prev=otr.insertCell(-1);
            otd_prev.className="page_prev";
            otd_prev.innerHTML="<<";
            otd_prev.cls="page_prev";

            var _pinx =s_sinx-1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_prev._pinx =_pinx;
            otd_prev._psize=_psize;
            otd_prev._url  =_url;

            otd_prev.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_prev.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_prev.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //一般|現在
        for(;s_sinx<=s_einx;s_sinx++){
            if(pinx==s_sinx){
            //現在
                var otd_current=otr.insertCell(-1);
                otd_current.className="page_current";
                otd_current.innerHTML=s_sinx;
                otd_current.cls="page_current";

                var _pinx =s_sinx;
                var _psize=psize;
                var _url  ="";

                if(page_args!=""){
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize+"&"
                    _url+=page_args
                }else{
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize
                }

                otd_current._pinx =_pinx;
                otd_current._psize=_psize;
                otd_current._url  =_url;

                otd_current.onmouseover=function(){
                    this.style.cursor="pointer";
                }
                otd_current.onmouseout=function(){
                    this.className=this.cls;
                    this.style.cursor="";
                }
                otd_current.onclick=function(){
                    var _pinx =this._pinx ;
                    var _psize=this._psize;
                    var _url  =this._url  ;
                    self.location.href=_url;
                }
            }else{
            //一般
                var otd_normal=otr.insertCell(-1);
                otd_normal.className="page_normal";
                otd_normal.innerHTML=s_sinx;
                otd_normal.cls="page_normal";

                var _pinx =s_sinx;
                var _psize=psize;
                var _url  ="";

                if(page_args!=""){
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize+"&"
                    _url+=page_args
                }else{
                    _url+=page_name +"?"
                    _url+=pinx_name +"="+_pinx+"&"
                    _url+=psize_name+"="+_psize
                }

                otd_normal._pinx =_pinx;
                otd_normal._psize=_psize;
                otd_normal._url  =_url;

                otd_normal.onmouseover=function(){
                    this.className="page_hover";
                    this.style.cursor="pointer";
                }
                otd_normal.onmouseout=function(){
                    this.className=this.cls;
                    this.style.cursor="";
                }
                otd_normal.onclick=function(){
                    var _pinx =this._pinx ;
                    var _psize=this._psize;
                    var _url  =this._url  ;
                    self.location.href=_url;
                }
            }
        }
        //下一頁
        if(s_einx<pnos){
            var otd_next=otr.insertCell(-1);
            otd_next.className="page_next";
            otd_next.innerHTML=">>";
            otd_next.cls="page_next";

            var _pinx =s_einx+1;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_next._pinx =_pinx;
            otd_next._psize=_psize;
            otd_next._url  =_url;

            otd_next.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_next.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_next.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }
        //最末頁
        if(s_einx<pnos){
            var otd_last=otr.insertCell(-1);
            otd_last.className="page_last";
            otd_last.innerHTML="最末頁";
            otd_last.cls="page_last";

            var _pinx =pnos;
            var _psize=psize;
            var _url  ="";

            if(page_args!=""){
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize+"&"
                _url+=page_args
            }else{
                _url+=page_name +"?"
                _url+=pinx_name +"="+_pinx+"&"
                _url+=psize_name+"="+_psize
            }

            otd_last._pinx =_pinx;
            otd_last._psize=_psize;
            otd_last._url  =_url;

            otd_last.onmouseover=function(){
                this.className="page_hover";
                this.style.cursor="pointer";
            }
            otd_last.onmouseout=function(){
                this.className=this.cls;
                this.style.cursor="";
            }
            otd_last.onclick=function(){
                var _pinx =this._pinx ;
                var _psize=this._psize;
                var _url  =this._url  ;
                self.location.href=_url;
            }
        }

        opage.appendChild(otbl);
        opage.tbl=otbl;

        return opage;

        function get_seinx(){
        //-----------------------------------------------
        //分頁列區段,值域起始值,值域終止值
        //-----------------------------------------------

            arry_list=array_range(1,pnos);
            arry_list=array_chunk(arry_list,list_size);

            for(var i=0;i<arry_list.length;i++){
                if(in_array(pinx,arry_list[i])){
                    var list=arry_list[i];
                    s_sinx=list[0];
                    s_einx=list[list.length-1];
                    break;
                }
            }

            return {
                's_sinx':s_sinx,
                's_einx':s_einx
            };
        }

        function array_range(s,e,step){
        //-----------------------------------------------
        //值域數值陣列
        //-----------------------------------------------
        //s     起始值
        //e     終止值
        //step  遞增數,預設1,可以指定負整數
        //-----------------------------------------------

            if(!step){
                step=1;
            }else{
                step=parseInt(step);
            }

            var arry=[];
            while(s<=e){
                arry.push(s);
                s=s+step;
            }

            return arry;
        }

        function array_chunk(arry,size){
        //-----------------------------------------------
        //依長度分割陣列
        //-----------------------------------------------
        //arry  陣列
        //size  長度,預設1
        //-----------------------------------------------

            //參數檢驗
            if(!arry){
                return [];
            }
            if(!size){
                size=1;
            }else{
                size=parseInt(size);
            }

            //處理
            var len     =arry.length;
            var pnos    =Math.ceil(len/size);
            var results =[];

            var inx=0;
            for(var i=1;i<=pnos;i++){
                var result=[];
                for(var j=0;j<size;j++){
                    var val=arry[inx];
                    if(val){
                        result[j]=val;
                    }
                    inx++;
                }
                //alert(result);
                results.push(result);
            }

            //alert(results.length);

            //回傳
            return results;
        }

        function in_array(val,array){
        //-----------------------------------------------
        //檢驗元素是否在陣列裡
        //-----------------------------------------------
        //val   值
        //array 陣列
        //-----------------------------------------------

            flag=false;
            for(var i=0;i<array.length;i++){
                if(val==array[i]){
                   flag=true;
                   break;
                }
            }

            //回傳
            return flag;
        }

        function parse_page_args(arry){
        //-----------------------------------------------
        //處理額外參數
        //-----------------------------------------------

            var tmp=[];
            for(var key in arry){
                var val=trim(arry[key]);
                tmp.push(key+'='+encodeURI(val));
            }

            return tmp.join('&');

            function trim(str){
            //去除字串前後空白

                str=str.toString();
                str=str.replace(/^\s+/,'');
                str=str.replace(/\s+$/,'');
                return str;
            }
        }
    }