//-------------------------------------------------------
//函式: scroll_to_top()
//用途: 頁面至頂
//-------------------------------------------------------

    function scroll_to_top(){
    //---------------------------------------------------
    //函式: scroll_to_top()
    //用途: 頁面至頂
    //---------------------------------------------------
    //
    //---------------------------------------------------
    
        $('html, body').scrollTop(0);
    }

    //客製化
    $(".scroll_to_top").click(function(){
        scroll_to_top();
    });