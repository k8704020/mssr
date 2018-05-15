<?
//-------------------------------------------------------
//版本編號 1.0
//登記書籍  讀取書籍頁數填寫資訊
//ajax
//-------------------------------------------------------
	
	//---------------------------------------------------
	//輸入 
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
	require_once(str_repeat("../",4)."/config/config.php");

	//外掛函式檔
	$funcs=array(
				APP_ROOT.'inc/conn/code',
				APP_ROOT.'lib/php/db/code'
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
		$array["error"] = "";
		$array["echo"] ="";	
        //POST

		
	//---------------------------------------------------
    //設定參數 檢驗參數
    //---------------------------------------------------
		
		//POST
       	$user_id        =(isset($_POST['user_id']))?(int)$_POST['user_id']:0;
		$user_permission=(isset($_POST['user_permission']))?$_POST['user_permission']:0;
		$book_sid   =(isset($_POST['book_sid']))?$_POST['book_sid']:0;
			
		if($user_id != $_SESSION["uid"] || $user_permission != $_SESSION["permission"])
		{
			$array["error"] ="你違法進入了喔!!  請重新登入";
			die(json_encode($array,1));
		}

	//-------------------------------------------
	//SQL
	//-------------------------------------------
		$array["book_page"]= "";
		$sql = "SELECT `book_sid` FROM `mssr_user_vote_book_page`
				WHERE create_by = $user_id 
				AND vote_state = '差'";
		$are_you_cracra = db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(),$arry_conn_mssr);
		$array["bad_count"] = count($are_you_cracra);
		
		$sql = "SELECT `book_sid`,`vote_state`,book_page FROM `mssr_user_vote_book_page`
				WHERE create_by = $user_id 
				AND book_sid = '$book_sid'";
		$are_you_cracra = db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(0,1),$arry_conn_mssr);
		$array["has_done"] = count($are_you_cracra);
		if($array["has_done"]>0)
		{
			$array["vote_state"]= $are_you_cracra[0]["vote_state"];
			$array["book_page"]= $are_you_cracra[0]["book_page"];
		}
		
		echo json_encode($array,1);
		
		
		?>