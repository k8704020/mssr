<?php
//-------------------------------------------------------
//函式: nl2br_deep()
//用途: nl2br,回遞套用全部
//日期: 2012年3月15日
//作者: jeff@max-life
//-------------------------------------------------------

    function nl2br_deep($val){
    //---------------------------------------------------
    //nl2br,回遞套用全部
    //---------------------------------------------------

        if(is_array($val)){
            $val=array_map('nl2br_deep',$val);
            return $val;
        }else{
            return nl2br($val);
        }
    }
?>