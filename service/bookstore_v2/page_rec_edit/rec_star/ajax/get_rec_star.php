<?
//-------------------------------------------------------
//版本編號 1.0
//讀上架書籍資訊
//ajax
//-------------------------------------------------------

	//---------------------------------------------------
	//輸入 user_id
	//輸出
	//---------------------------------------------------

	//---------------------------------------------------
	//設定與引用
	//---------------------------------------------------

	//SESSION
	@session_start();

	//啟用BUFFER
	@ob_start();

	//外掛設定檔
	require_once(str_repeat("../",5)."/config/config.php");
	require_once(str_repeat("../",5)."/inc/get_book_info/code.php");

	 //外掛函式檔
	$funcs=array(
				APP_ROOT.'inc/code',
				APP_ROOT.'lib/php/db/code',
				);
	func_load($funcs,true);


	//清除並停用BUFFER
	@ob_end_clean();

	//建立連線 user
	$conn_mssr=conn($db_type='mysql',$arry_conn_mssr);




	//-----------------------------------------------
	//通用
	//-----------------------------------------------

	//-------------------------------------------
	//初始化, curl設定
	//-------------------------------------------
		$array =array();
		//評星
		$array['rec_star_rank']="-1";
		$array['rec_star_reason']="xxxxxxx";
		//$array['rec_star_content'] = "";
		//$array['rec_star_score'] = 0;
		$array["error"] = "";
		$array["echo"] = "";
	//---------------------------------------------------
    //設定參數 檢驗參數
    //---------------------------------------------------

        //POST
       	$user_id        =(isset($_POST['user_id']))?(int)$_POST['user_id']:0;
		$user_permission=(isset($_POST['user_permission']))?$_POST['user_permission']:0;
		$book_sid = mysql_prep(trim($_POST['book_sid']));
 		//trim();//去空白

		if($user_permission != $_SESSION["permission"])
		{
			$array["error"] ="你違法進入了喔!!  請重新登入";
			die(json_encode($array,1));
		}

	//-------------------------------------------
	//SQL
	//-------------------------------------------

		//評星
		$sql = "SELECT rec_rank, rec_reason,rec_state
				FROM  `mssr_rec_book_star_log`
				WHERE  `mssr_rec_book_star_log`.`user_id` =  '".$user_id."'
				AND  `mssr_rec_book_star_log`.`book_sid` =  '".$book_sid."'
				ORDER BY  `mssr_rec_book_star_log`.`keyin_cdate` DESC ";

		$retrun = db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(0,1),$arry_conn_mssr);

		foreach($retrun as $key1=>$val1)
		{
			if($val1['rec_state']== "顯示")
			{
				$array['rec_star_rank'] = mysql_prep($val1['rec_rank']);
				$array['rec_star_reason'] = mysql_prep($val1['rec_reason']);
			}
		}
		//print_r($array);
		echo json_encode($array,1);
		?>