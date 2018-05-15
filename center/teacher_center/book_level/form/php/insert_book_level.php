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
        require_once(str_repeat("../",4).'config/config.php');

        //外掛函式檔
        $funcs=array(
                    APP_ROOT.'inc/code',
                    APP_ROOT.'center/teacher_center/inc/code',

                    APP_ROOT.'lib/php/vaildate/code',
                    APP_ROOT.'lib/php/string/code',
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

        if($config_arrys['is_offline']['center']['teacher_center']){
            $url=str_repeat("../",3).'index.php';
            header("Location: {$url}");
            die();
        }

    //---------------------------------------------------
    //有無登入
    //---------------------------------------------------

        $arrys_login_info=get_login_info($db_type='mysql',$arry_conn_user,$APP_ROOT);
        if(empty($arrys_login_info)){
            die();
        }

    //---------------------------------------------------
    //重複登入
    //---------------------------------------------------

        if(in_array('read_the_registration_code',$config_arrys['user_area'])){
        //清空閱讀登記條碼版登入資訊

            $_SESSION['config']['user_tbl']=array();
            $_SESSION['config']['user_type']='';
            $_SESSION['config']['user_lv']=0;
            if(in_array('read_the_registration_code',$_SESSION['config']['user_area'])){
                foreach($_SESSION['config']['user_area'] as $inx=>$area){
                    if(trim($area)==='read_the_registration_code'){
                        unset($_SESSION['config']['user_area'][$inx]);
                    }
                }
            }
        }

    //---------------------------------------------------
    //SESSION
    //---------------------------------------------------

        $sess_login_info=(isset($_SESSION['tc']['t|dt']))?$_SESSION['tc']['t|dt']:array();

    //---------------------------------------------------
    //權限,與判斷
    //---------------------------------------------------

        if(!empty($sess_login_info)){
            if(!auth_check($db_type='mysql',$arry_conn_user,$sess_login_info['permission'],$auth_type='mssr_tc')){
                $msg="您沒有權限進入，請洽詢明日星球團隊人員!";
                $jscript_back="
                    <script>
                        alert('{$msg}');
                        history.back(-1);
                    </script>
                ";
                die($jscript_back);
            }
        }else{
            //權限指標
            $auth_flag=false;
            foreach($arrys_login_info as $inx=>$arry_login_info){
                if(auth_check($db_type='mysql',$arry_conn_user,$arry_login_info['permission'],$auth_type='mssr_tc'))$auth_flag=true;
            }
            if(!$auth_flag){
                $msg="您沒有權限進入，請洽詢明日星球團隊人員!";
                $jscript_back="
                    <script>
                        alert('{$msg}');
                        history.back(-1);
                    </script>
                ";
                die($jscript_back);
            }
        }

    // //---------------------------------------------------
    // //管理者判斷
    // //---------------------------------------------------

        if(!empty($sess_login_info)){
            $is_admin=is_admin(trim($sess_login_info['permission']));
            if($is_admin){
                $sess_login_info['responsibilities']=99;
            }
        }

    //---------------------------------------------------
    //系統權限判斷
    //---------------------------------------------------
    //1     校長
    //3     主任
    //5     帶班老師
    //12    行政老師
    //14    主任帶一個班
    //16    主任帶多個班
    //22    老師帶多個班
    //99    管理者

        if(!empty($sess_login_info)){
            $auth_sys_check_lv=auth_sys_check($sess_login_info['responsibilities'],'m_user_rec');
        }

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
        
        // $flag    =trim($_POST[trim('flag    ')]);
        // $ajax_cno=trim($_POST[trim('ajax_cno')]);

        //SESSION
        $sess_user_id    =(int)$sess_login_info['uid'];
        $sess_permission =trim($sess_login_info['permission']);
        $sess_school_code=trim($sess_login_info['school_code']);
        $sess_class_code =trim($sess_login_info['arrys_class_code'][0]['class_code']);
        $sess_grade      =(int)$sess_login_info['arrys_class_code'][0]['grade'];
        $sess_classroom  =(int)$sess_login_info['arrys_class_code'][0]['classroom'];

        //分頁
        // $psize=(isset($_POST['psize']))?(int)$_POST['psize']:10;
        // $pinx =(isset($_POST['pinx']))?(int)$_POST['pinx']:1;
        // $psize=($psize===0)?10:$psize;
        // $pinx =($pinx===0)?1:$pinx;

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
        //-----------------------------------------------

            $sess_user_id=(int)$sess_user_id;
            $create_by   =(int)$sess_user_id;
            $edit_by     =(int)$sess_user_id;

            $book_sid =$_REQUEST['book_sid'];
            $book_isbn_10 =$_REQUEST['book_isbn_10'];
            $book_isbn_13 =$_REQUEST['book_isbn_13'];
            $book_name =$_REQUEST['book_name'];
            $book_library_code =$_REQUEST['book_library_code'];


            $language =$_REQUEST['language'];
            $bopomofo=$_REQUEST['bopomofo'];
            $major_topic_val=$_REQUEST['major_topic'];
            $major_topic=implode(",",$major_topic_val);

            $sub_topic_val= $_REQUEST['sub_topic'];
            $sub_topic=implode(",",$sub_topic_val);
    


            $minor_topic_val= $_REQUEST['minor_topic'];
            $minor_topic=implode(",",$minor_topic_val);


            


            $tag1= $_REQUEST['tag1'];
            $tag2= $_REQUEST['tag2'];
            $tag3= $_REQUEST['tag3'];
            $tag4= $_REQUEST['tag4'];
            $tag5= $_REQUEST['tag5'];
            $pages= $_REQUEST['pages'];
            $words= $_REQUEST['words'];
            $hard= $_REQUEST['hard'];
            $level= $_REQUEST['level'];
            $keyin_cdate        ="NOW()";
            $keyin_mdate        ="NOW()";
            $keyin_ip           =get_ip();

        //-----------------------------------------------

        //-----------------------------------------------
            $sql="
                        

                        INSERT INTO `mssr_reading_log_spreadsheet`(
	                        `edit_by`, 
	                        `user_id`, 
	                        `book_sid`, 
	                        `book_isbn_10`, 
	                        `book_isbn_13`, 
	                        `book_library_code`, 
	                        `book_language`, 
	                        `bopomofo`, 
	                        `major_topic`, 
	                        `sub_topic`, 
	                        `minor_topic`, 
	                        `tag1`, 
	                        `tag2`, 
	                        `tag3`, 
	                        `tag4`, 
	                        `tag5`, 
	                        `pages`, 
	                        `words`, 
	                        `level`, 
	                        `lexile_level`, 
	                        `keyin_cdate`, 
	                        `keyin_mdate`, 
	                        `keyin_ip`
                        ) 
                        VALUES 
                        (
	                        {$edit_by},
	                        {$create_by},
	                        '{$book_sid}',
	                        '{$book_isbn_10}',
	                        '{$book_isbn_13}',
	                        '{$book_library_code}',
	                        {$language},
	                        '{$bopomofo}',
	                        '".$major_topic."',
	                        '".$sub_topic."',
	                        '".$minor_topic."',
	                        '{$tag1}',
	                        '{$tag2}',
	                        '{$tag3}',
	                        '{$tag4}',
	                        '{$tag5}',
	                        '{$pages}',
	                        '{$words}',
	                        '{$hard}',
	                        '{$level}',
	                        {$keyin_cdate},
	                        {$keyin_mdate},
	                        '{$keyin_ip}'
                    	)

        ";

       

echo $sql;

        $result=db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
         header("Location:../finish.php ");



?>