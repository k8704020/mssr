<?php
//-------------------------------------------------------
//inc
//-------------------------------------------------------
//root          根單元
//
//-------------------------------------------------------
//root          根單元
//-------------------------------------------------------
//
//-------------------------------------------------------

    //---------------------------------------------------
    //設定與引用
    //---------------------------------------------------

        //外掛函式檔

            ////root          根單元
            //require_once(preg_replace('/\s+/','','get_login_info    /code.php'));

            $_funcs=array(
                trim('update_user_branch                    '),
                trim('update_user_branch_revenue_bonus_log  '),
                trim('update_user_task_inventory            ')
            );

            foreach($_funcs as $inx=>$_func){
                if(!function_exists("{$_func}")){
                    require_once("{$_func}/code.php");
                }
            }
?>