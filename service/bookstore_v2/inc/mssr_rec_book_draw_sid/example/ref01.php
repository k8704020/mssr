<?php
//-------------------------------------------------------
//範例
//-------------------------------------------------------

    //設定頁面語系
    header("Content-Type: text/html; charset=UTF-8");

    //設定文字內部編碼
    mb_internal_encoding("UTF-8");

    //設定台灣時區
    date_default_timezone_set('Asia/Taipei');

    //---------------------------------------------------
    //函式: book_read_opinion_sid()
    //用途: 推薦畫圖.識別碼
    //---------------------------------------------------
    //$create_by    建立者
    //$encode       頁面編碼
    //
    //---------------------------------------------------
    //字首: mrbd + create_by(建立者) + YYYYMMDDhhiiss + 亂數組成，共25碼，
    //      mrbd + 1 + 20130101000000 + 0000001
    //---------------------------------------------------

        //外掛設定檔
        require_once(str_repeat("../",1)."code.php");
        echo mssr_rec_book_draw_sid(1,mb_internal_encoding());
?>
