<?php
//-------------------------------------------------------
//範例
//-------------------------------------------------------

    //---------------------------------------------------
    //設定與引用
    //---------------------------------------------------

        //外掛設定檔
        require_once(str_repeat("../",2).'config/config.php');
		require_once(str_repeat("../",2)."/center/teacher_center/inc/book/book/book_global_sid/code.php");

         //外掛函式檔
        $funcs=array(
                    APP_ROOT.'inc/code',
                    APP_ROOT.'center/teacher_center/inc/code',

                    APP_ROOT.'lib/php/vaildate/code',
                    APP_ROOT.'lib/php/db/code',
                    APP_ROOT.'lib/php/net/code',
                    APP_ROOT.'lib/php/array/code',
                    APP_ROOT.'lib/php/vaildate/code',
                    APP_ROOT.'lib/php/string/code',
                    APP_ROOT.'lib/php/fso/code'
                    );
        func_load($funcs,true);

    //---------------------------------------------------
    //預設值
    //---------------------------------------------------

        $file='工讀生快速算字數表格_(劉奕德)_s.csv';

    //---------------------------------------------------
    //資料庫
    //---------------------------------------------------

        //-----------------------------------------------
        //通用
        //-----------------------------------------------

            //建立連線 mssr
            $conn_mssr=conn($db_type='mysql',$arry_conn_mssr);

        //-----------------------------------------------
        //接收參數
        //-----------------------------------------------

            $fcsv_to_arrays=fcsv_to_array($file,$delimiter=",",$enclosure="'",$fso_enc);
          //  echo "<Pre>";
            //print_r(($fcsv_to_arrays));
          //  echo "</Pre>";
          //  die("你死了");

        //-----------------------------------------------
        //SQL
        //-----------------------------------------------

            $create_by=1;
            $edit_by=1;
			$count = 1000;

			$up_count=0;
			$in_count=0;
			$no_count=0;
            foreach($fcsv_to_arrays as $inx=>$fcsv_to_array)
			{

                if(($inx!==0))
				{
					
					$id_type = "" ;
					
                    //------------------------------------------------
					//判斷 屬於哪種類型
					//------------------------------------------------
					//中文字
					if((int)$fcsv_to_array[0]==0)
					{$id_type = "n";}
					//13碼
					else if(mb_strlen($fcsv_to_array[0])==12 || mb_strlen($fcsv_to_array[0])==13 )
					{$id_type = "13";}
					//10碼
					else if(mb_strlen($fcsv_to_array[0])==9 || mb_strlen($fcsv_to_array[0])==10 )
					{$id_type = "10";}
					//其他
					else 
					{
					  $id_type = "u";
					  if(mb_strlen($fcsv_to_array[0])==5 )$fcsv_to_array[0] = "00".$fcsv_to_array[0];
					  if(mb_strlen($fcsv_to_array[0])==6 )$fcsv_to_array[0] = "0".$fcsv_to_array[0];
					  if(mb_strlen($fcsv_to_array[0])==4 )$fcsv_to_array[0] = "000".$fcsv_to_array[0];
					}
					
					//------------------------------------------------
					//應對方式   13碼  10 碼   其他
					//------------------------------------------------
					
					
					if($id_type == "u")
					{
						$find_count = 0 ;
						//mssr_book_library
						$sql = "SELECT book_sid
								FROM `mssr_book_library` 
								WHERE book_library_code = '".$fcsv_to_array[0]."'
								AND school_code = 'gcp'";
						$result = db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(0,1),$arry_conn_mssr);
						if(count($result))
						{
							$sql = "UPDATE `mssr_book_library` 
									SET book_word = '".$fcsv_to_array[1]."'
									WHERE book_sid = '".$result[0]["book_sid"]."'";
							db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(),$arry_conn_mssr);	
							$find_count++;
						}
						
						
						//統計更新 與  新增數
						if($find_count>0)$up_count++;
						if($find_count==0)
						{
							echo 	$fcsv_to_array[0].",".$fcsv_to_array[1]."<BR>";
							$no_count++;
						}
					}
                }
            }
		echo "更新數->".$up_count."<BR>";
		echo "增加數->".$in_count."<BR>";
		echo "無效數->".$no_count."<BR>";
?>