<?php
//-------------------------------------------------------
//函式: array_shift_n()
//用途: 從前頭刪除N個元素
//日期: 2011年12月1日
//作者: jeff@max-life
//-------------------------------------------------------

    function array_shift_n(&$array,$N){
    //---------------------------------------------------
    //從前頭刪除N個元素
    //---------------------------------------------------
    //$arr  陣列
    //$N    個數
    //
    //本函式回傳被移除的元素,型態為陣列
    //---------------------------------------------------

        $R=array();

        if($N>=count($array)){
            $N=count($array);
        }

        for($i=1;$i<=$N;$i++){
            $R[]=array_shift($array);
        }

        return $R;
    }
?>