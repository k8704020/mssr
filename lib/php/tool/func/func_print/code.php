<?php
//-------------------------------------------------------
//函式: func_print()
//用途: 列印出自訂函數
//日期: 2011年10月29日
//作者: jeff@max-life
//-------------------------------------------------------

    function func_print(){
    //---------------------------------------------------
    //函式: func_print()
    //用途: 列印出自訂函數
    //---------------------------------------------------

        //取得所有函式名稱陣列
        $arr0=get_defined_functions();
        $arr1=$arr0['internal']; //系統內建
        $arr2=$arr0['user'];     //自訂

        //列出自訂常數
        echo '<pre>';
        print_r($arr2);
        echo '</pre>';

        die();
    }
?>