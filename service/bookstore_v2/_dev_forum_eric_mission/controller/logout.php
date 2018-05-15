<?php
//-------------------------------------------------------
//明日聊書
//-------------------------------------------------------

    //---------------------------------------------------
    //設定與引用
    //---------------------------------------------------

        //SESSION
        @session_start();

        //啟用BUFFER
        @ob_start();

        //外掛設定檔
        require_once(str_repeat("../",3).'config/config.php');

        //外掛頁面檔
        require_once(str_repeat("../",1).'pages/code.php');

        //外掛函式檔
        $funcs=array(
            APP_ROOT.'inc/code',
            APP_ROOT.'service/_dev_forum_eric_mission/inc/code'
        );
        func_load($funcs,true);

        //清除並停用BUFFER
        @ob_end_clean();

    //---------------------------------------------------
    //處理
    //---------------------------------------------------

        //終止SESSION
        session_end(TZone);

        //重導頁面
        $url=str_repeat("../",0).'/ac/index.php';
        header("Location: {$url}");
        die();
?>