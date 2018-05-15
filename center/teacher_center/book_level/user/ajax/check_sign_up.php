<?php
//-------------------------------------------------------
//教師中心
//-------------------------------------------------------
// echo "hello";
    //---------------------------------------------------
    //設定與引用
    //---------------------------------------------------

        //SESSION
        @session_start();

        //啟用BUFFER
        @ob_start();

        //外掛設定檔
        require_once(str_repeat("../",5).'config/config.php');

        //外掛函式檔
        $funcs=array(
                    APP_ROOT.'inc/code',
                    APP_ROOT.'center/teacher_center/inc/code',

                    APP_ROOT.'lib/php/vaildate/code',
                    APP_ROOT.'lib/php/db/code',
                    APP_ROOT.'lib/php/net/code',
                    APP_ROOT.'lib/php/array/code'
                    );
        func_load($funcs,true);

        //清除並停用BUFFER
        @ob_end_clean();

    //---------------------------------------------------
    //有無維護
    //---------------------------------------------------


    //---------------------------------------------------
    //有無登入
    //---------------------------------------------------

    //---------------------------------------------------
    //重複登入
    //---------------------------------------------------



    //---------------------------------------------------
    //SESSION
    //---------------------------------------------------



    //---------------------------------------------------
    //權限,與判斷
    //---------------------------------------------------


    // //---------------------------------------------------
    // //管理者判斷
    // //---------------------------------------------------


    //---------------------------------------------------
    //系統權限判斷
    //---------------------------------------------------

    //---------------------------------------------------
    //接收參數
    //---------------------------------------------------
    //user_id    使用者主索引(被閱讀人)
    //book_sid   書籍識別碼
    //flag       閱讀結果指標
    //ajax_cno   閱讀數指標


    //---------------------------------------------------
    //設定參數
    //---------------------------------------------------
    //user_id    使用者主索引(被閱讀人)
    //book_sid   書籍識別碼
    //flag       閱讀結果指標
    //ajax_cno   閱讀數指標

        //POST
        // $user_id =trim($_POST[trim('user_id ')]);
        // $book_sid=trim($_POST[trim('book_sid')]);
        // $flag    =trim($_POST[trim('flag    ')]);
        // $ajax_cno=trim($_POST[trim('ajax_cno')]);



    //---------------------------------------------------
    //檢驗參數
    //---------------------------------------------------


    //---------------------------------------------------
    //資料庫
    //---------------------------------------------------

        //-----------------------------------------------
        //通用
        //-----------------------------------------------

            //建立連線 mssr
            $conn_mssr=conn($db_type='mysql',$arry_conn_mssr);

        //-----------------------------------------------
        //預設值
 
            $new_account=trim($_REQUEST['new_account']);
           

            


//9789862167038

        //-----------------------------------------------
        //處理
        //-----------------------------------------------

        //搜尋使用者資料

       

            $sql="
                        SELECT * 
                        FROM `mssr_idc_member` 
                        WHERE `account`='{$new_account}'
                        

                    ";


 
            $result=db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

            $array_output=[];

            if(!empty($result)){

                    $array_output['msg'] ="此帳號已存在";
       
                 
            }else{

                    $array_output['msg'] ="";
            }

           



    //---------------------------------------------------
    //重導頁面
    //---------------------------------------------------

        echo json_encode($array_output,true);
?>