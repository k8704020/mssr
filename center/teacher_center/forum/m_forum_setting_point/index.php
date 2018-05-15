<?php
//-------------------------------------------------------
//教師中心
//-------------------------------------------------------

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

					APP_ROOT.'lib/php/net/code',
					APP_ROOT.'lib/php/db/code'
					);
		func_load($funcs,true);

		//清除並停用BUFFER
		@ob_end_clean();

	//---------------------------------------------------
	//有無維護
	//---------------------------------------------------

		if($config_arrys['is_offline']['center']['teacher_center']){
			$url=str_repeat("../",5).'index.php';
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

	//---------------------------------------------------
	//管理者判斷
	//---------------------------------------------------

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
			$auth_sys_check_lv=auth_sys_check($sess_login_info['responsibilities'],'m_forum_setting_class');
		}

	//---------------------------------------------------
	//接收參數
	//---------------------------------------------------

		//SESSION
		$filter      ='';   //查詢條件式
		$query_fields='';   //查詢欄位,顯示用

		$sess_user_id   =(int)$sess_login_info['uid'];
		$sess_permission=trim($sess_login_info['permission']);
		$sess_class_code=trim($sess_login_info['arrys_class_code'][0]['class_code']);

	//---------------------------------------------------
	//設定參數
	//---------------------------------------------------

		$sess_user_id=(int)$sess_user_id;
		$sess_permission=mysql_prep(trim($sess_permission));
		$sess_class_code=mysql_prep(trim($sess_class_code));

	//---------------------------------------------------
	//檢驗參數
	//---------------------------------------------------

	//---------------------------------------------------
	//串接SQL
	//---------------------------------------------------

		//-----------------------------------------------
		//資料庫
		//-----------------------------------------------

			//建立連線 user
			$conn_user=conn($db_type='mysql',$arry_conn_user);

			//建立連線 mssr
			$conn_mssr=conn($db_type='mysql',$arry_conn_mssr);

		//---------------------------------------------------
		//學生陣列
		//---------------------------------------------------

			$users=arrys_users($conn_user,$sess_class_code,$date=date("Y-m-d"),$arry_conn_user);

			//無學生時處理
			if (empty($users)) {
				$message = "
					<br><br>
					<h2 align='center'><font color='red'>目前沒有任何學生在這個班級中。</font></h2>
					<div align='center'><input type ='button' onclick='history.back()' value='回到上一頁'></input></div>
				";

				die($message);
			}

		//-----------------------------------------------
		//主SQL
		//-----------------------------------------------

			$query_sql="
				SELECT `user`.`member`.`uid` AS `user_id`
				FROM `user`.`member`
				WHERE 1=1
					AND `user`.`member`.`uid` IN ({$users})
				ORDER BY `user_id` ASC
			";

			$arrys_result=db_result($conn_type='pdo',$conn_mssr,$query_sql,array(),$arry_conn_mssr);

	//---------------------------------------------------
	//分頁處理
	//---------------------------------------------------

		$numrow=0;  //資料總筆數
		$psize =10;  //單頁筆數,預設10筆
		$pnos  =0;  //分頁筆數
		$pinx  =1;  //目前分頁索引,預設1
		$sinx  =0;  //值域起始值
		$einx  =0;  //值域終止值

		if(isset($_GET['psize'])){
			$psize=(int)$_GET['psize'];
			if($psize===0){
				$psize=10;
			}
		}
		if(isset($_GET['pinx'])){
			$pinx=(int)$_GET['pinx'];
			if($pinx===0){
				$pinx=1;
			}
		}

		if(!empty($arrys_result)){
			$numrow=db_result($conn_type='pdo',$conn_mssr,$query_sql,array(),$arry_conn_mssr);
			$numrow=count($numrow);
		}else{
			$numrow=db_result($conn_type='pdo',$conn_user,$query_sql,array(),$arry_conn_user);
			$numrow=count($numrow);
		}

		$pnos  =ceil($numrow/$psize);
		$pinx  =($pinx>$pnos)?$pnos:$pinx;

		$sinx  =(($pinx-1)*$psize)+1;
		$einx  =(($pinx)*$psize);
		$einx  =($einx>$numrow)?$numrow:$einx;

	//---------------------------------------------------
	//資料,設定
	//---------------------------------------------------

		//網頁標題
		$title="明日星球,教師中心";

		//-----------------------------------------------
		//教師中心路徑選單
		//-----------------------------------------------

			$auth_sys_name_arry=auth_sys_name_arry();
			$FOLDER=explode('/',dirname($_SERVER['PHP_SELF']));
			$sys_ename=$FOLDER[count($FOLDER)-2];
			$mod_ename=$FOLDER[count($FOLDER)-1];
			$sys_cname='';  //系統名稱
			$mod_cname='';  //模組名稱

			foreach($auth_sys_name_arry as $key=>$val){
				if($key==$sys_ename){
					$sys_cname=$val;
				}elseif($key==$mod_ename){
					$mod_cname=$val;
				}
			}

			if((trim($sys_cname)=='')||(trim($mod_cname)=='')){
				$err ='teacher_center_path err!';

				if(1==2){//除錯用
					echo "<pre>";
					print_r($err);
					echo "</pre>";
					die();
				}
			}

			//連結路徑
			$sys_url ="";
			$sys_page=str_repeat("../",2)."index.php";
			$sys_arg =array(
				'sys_ename'  =>addslashes($sys_ename)
			);
			$sys_arg=http_build_query($sys_arg);
			$sys_url=$sys_page."?".$sys_arg;
?>
<!DOCTYPE HTML>
<Html>
<Head>
	<Title><?php echo $title;?></Title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo Charset;?>">
	<meta http-equiv="Content-Language" content="<?php echo Content_Language;?>">
	<?php echo meta_keywords($key='mssr');?>
	<?php echo meta_description($key='mssr');?>
	<?php echo bing_analysis($allow=false);?>
	<?php echo robots($allow=false);?>

	<!-- 通用 -->
	<link rel="stylesheet" type="text/css" href="../../../../inc/code.css" media="all" />
	<script type="text/javascript" src="../../../../inc/code.js"></script>

	<script type="text/javascript" src="../../../../lib/jquery/basic/code.js"></script>
	<script type="text/javascript" src="../../../../lib/jquery/plugin/code.js"></script>

	<script type="text/javascript" src="../../../../lib/js/vaildate/code.js"></script>
	<script type="text/javascript" src="../../../../lib/js/public/code.js"></script>
	<script type="text/javascript" src="../../../../lib/js/string/code.js"></script>
	<script type="text/javascript" src="../../../../lib/js/table/code.js"></script>

	<!-- 專屬 -->
	<link rel="stylesheet" type="text/css" href="../../inc/code.css" media="all" />
	<script type="text/javascript" src="../../inc/code.js"></script>

	<link rel="stylesheet" type="text/css" href="../../css/def.css" media="all" />

	<style>
		/* 容器微調 */
		#container, #content, #teacher_datalist_tbl{
			width:760px;
		}
	</style>
</Head>

<Body>

<!-- 容器區塊 開始 -->
<div id="container">

	<!-- 內容區塊 開始 -->
	<div id="content">
		<table id="teacher_datalist_tbl" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" valign="middle" width="400px">
					<!-- 教師中心路徑選單 開始 -->
					<div id="teacher_center_path">
						<table id="teacher_center_path_cont" border="0" width="100%">
							<tr>
								<td align="left" valign="middle" class="menu_dot">
									<span class="menu_cont">
										<img width="12" height="12" src="../../../../img/icon/blue.jpg" border="0">
										<a href="<?php echo htmlspecialchars($sys_url);?>">
											<?php echo htmlspecialchars($sys_cname);?>
										</a>
										<span class="fc_gray0" style="margin:0 3px;">&gt;&gt;</span>
										<a href="javascript:void(0);"><?php echo htmlspecialchars($mod_cname);?></a>
									</span>
								</td>
							</tr>
						</table>
					</div>
					<!-- 教師中心路徑選單 結束 -->
				</td>
				<td align="right" valign="middle">
					<!-- 查詢表單列 開始 -->

					<!-- 查詢表單列 結束 -->
				</td>
			</tr>
			<tr>
				<td width="760px" colspan="2">
					<!-- 資料列表 開始 -->
					<?php
						if($numrow!==0){
							$arrys_result=db_result($conn_type='pdo',$conn_mssr,$query_sql,array($sinx-1,$psize),$arry_conn_mssr);
							page_hrs($title);
						}else{
							die();
						}
					?>
					<!-- 資料列表 結束 -->
				</td>
			</tr>
		</table>
	</div>
	<!-- 內容區塊 結束 -->

</div>
<!-- 容器區塊 結束 -->

<!-- 快速切換區塊 開始 -->
<?php //echo fast_area($rd=2);?>
<!-- 快速切換區塊 結束 -->

<script type="text/javascript">
//-------------------------------------------------------
//頁面初始
//-------------------------------------------------------

	$(function(){

		//快速切換設置
		//fast_area_config('#fast_area',0,0);

	});

	function choose_identity(){
	//開啟身分選擇區塊
		$.blockUI({
			message:$('#choose_identity'),
			css:{
				width:'260px'
			},
			overlayCSS:{
				backgroundColor:'#000',
				opacity:0.6,
				cursor:'default'
			}
		});
	}

	function choose_class_code(){
	//開啟班級選擇區塊
		$.blockUI({
			message:$('#choose_class_code'),
			css:{
				width:'260px'
			},
			overlayCSS:{
				backgroundColor:'#000',
				opacity:0.6,
				cursor:'default'
			}
		});
	}

</script>
</Body>
</Html>


<?php function page_hrs($title="") {?>
<?php
//-------------------------------------------------------
//page_hrs 區塊 -- 開始
//-------------------------------------------------------

	//---------------------------------------------------
	//外部變數
	//---------------------------------------------------

		//config.php
		global $PAGE_SELF;
		global $FOLDER_SELF;
		global $nl;
		global $tab;
		global $fso_enc;
		global $page_enc;
		global $arry_conn_user;
		global $arry_conn_mssr;
		global $APP_ROOT;

		//local
		global $numrow;
		global $psize;
		global $pnos;
		global $pinx;
		global $sinx;
		global $einx;

		global $arrys_result;
		global $sess_login_info;
		global $sess_class_code;
		global $conn_user;
		global $conn_mssr;

	//---------------------------------------------------
	//內部變數
	//---------------------------------------------------

		$fld_nos=0;  //欄位個數
		$btn_nos=0;  //功能按鈕個數

	//---------------------------------------------------
	//額外處理
	//---------------------------------------------------

		//權限資訊
		$user_upload=1;

		if(false===@json_decode($arrys_result[0]['setting'],true)){
			$arry_setting=array();
		}else{
			$arry_setting=@json_decode($arrys_result[0]['setting'],true);
		}

		if((!empty($arry_setting))&&(isset($arry_setting['user_upload']))){
			$user_upload=(int)($arry_setting['user_upload']);
		}
?>
<!-- 條件資訊  開始 -->
<!-- <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_style2" style="position:relative;top:20px;"/>
	<tr align="center" valign="middle" class="bg_gray1 fc_white0">
		<td height="30px" colspan="4">
			聊書發文點數設定
		</td>
	</tr>
	<tr align="center" valign="middle">
		<td width="250px" height="50px">
			<span style="position:relative;left:10px;" class="fc_gray0">班級老師：</span>
			<span style="position:relative;left:5px;" class="fc_gray0">
				<?php echo htmlspecialchars($sess_login_info['name']);?>
			</span>
		</td>
	</tr>
</table> -->
<!-- 條件資訊  結束 -->

<br>

<div style="padding-left: 15%;">
	<font color="red" size="3">
		※只有發文點數為負數的學生才能重置發文點數，重置後的發文點數為30點。<br>
		&emsp;聊書發表文章一次會消耗發文點數30點。
	</font>
</div>

<!-- 設定條件資訊  開始 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="position:relative;top:30px;">
	<tr>
		<!-- 在此設定寬高 -->
		<td width="100%" height="250px" align="center" valign="top">

			<table id="mod_data_tbl" border="0" width="100%" cellpadding="5" cellspacing="0" style="margin-top:0px; border:1px solid #c0c0c0" class="table_style1">
				<tr align="center" valign="middle" class="bg_gray1 fc_white0">
					<td width="30%">學生</td>
					<td>持有的發文點數</td>
					<td>重置發文點數</td>
				</tr>

				<?php foreach($arrys_result as $inx=>$arry_result) :?>
				<?php
				//---------------------------------------------------
				//接收欄位
				//---------------------------------------------------

					extract($arry_result, EXTR_PREFIX_ALL, "rs");

				//---------------------------------------------------
				//處理欄位
				//---------------------------------------------------

					//user_id           使用者主索引
					$rs_user_id=(int)$rs_user_id;

				//---------------------------------------------------
				//特殊處理
				//---------------------------------------------------

					//-----------------------------------------------
					//查找, 使用者名稱
					//-----------------------------------------------

						$rs_user_name='';
						$query_sql="
							SELECT
								`name`
							FROM `member`
							WHERE 1=1
								AND `uid`={$rs_user_id}
						";
						//送出
						$err ='DB QUERY FAIL';
						$sth=$conn_user->query($query_sql);
						foreach($sth as $value){
							$rs_user_name=trim($value['name']);
						}

					//-----------------------------------------------
					//查找, 使用者發文點數
					//-----------------------------------------------

						$total_point = 100;
						$query_sql = "
							SELECT `mssr_forum`.`mssr_forum_point`.`total_point`
							FROM `mssr_forum`.`mssr_forum_point`
							WHERE 1=1
								AND `mssr_forum`.`mssr_forum_point`.`user_id` = {$rs_user_id}
						";
						//送出
						$err ='DB QUERY FAIL';
						$sth=$conn_mssr->prepare($query_sql);
						$sth->execute();

						foreach($sth as $value){
							$total_point = trim($value['total_point']);
						}
				?>
				<tr>
					<td height="30px" align="center" valign="middle">
						<?php echo htmlspecialchars($rs_user_name);?>
					</td>
					<td height="30px" align="center" valign="middle">
						<?php echo $total_point;?>
					</td>
					<td height="30px" align="center" valign="middle">
						<?php if ($total_point > 0): ?>
							不需重置發文點數
						<?php else: ?>
							<button onclick="edit_user_point(<?php echo $rs_user_id; ?>)">重置</button>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach ;?>
			</table>

			<table border="0" width="100%">
				<tr valign="middle">
					<td align="left">
						<!-- 分頁列 -->
						<span id="page" style="position:relative;top:10px;"></span>
					</td>
					<td align="right">
						<span style="position:relative;top:10px;right:25px;"></span>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<!-- 設定條件資訊  結束 -->

<script type="text/javascript">
//-------------------------------------------------------
//頁面初始
//-------------------------------------------------------

	var psize=<?php echo $psize;?>;
	var pinx =<?php echo $pinx;?>;

	window.onload=function(){

	}

	window.onload=function(){
		//套表格列奇偶色
		table_hover(tbl_id='mod_data_tbl',c_odd='#fff',c_even='#fff',c_on='#e6faff');

		//分頁列
		var cid         ="page";                        //容器id
		var numrow      =<?php echo (int)$numrow;?>;    //資料總筆數
		var psize       =<?php echo (int)$psize ;?>;    //單頁筆數,預設10筆
		var pnos        =<?php echo (int)$pnos  ;?>;    //分頁筆數
		var pinx        =<?php echo (int)$pinx  ;?>;    //目前分頁索引,預設1
		var sinx        =<?php echo (int)$sinx  ;?>;    //值域起始值
		var einx        =<?php echo (int)$einx  ;?>;    //值域終止值
		var list_size   =5;                             //分頁列顯示筆數,5
		var url_args    ={};                            //連結資訊
		url_args={
			'pinx_name' :'pinx',
			'psize_name':'psize',
			'page_name' :'index.php',
			'page_args' :{}
		}
		var opage=pages(cid,numrow,psize,pnos,pinx,sinx,einx,list_size,url_args);
	}

	function edit_user_point(student_id) {
		if (!confirm('你確定要重置這位學生的發文點數嗎?')) {
			return false;
		}

		//修改上傳條件
		var url = "";
		var page = str_repeat("../",0)+"edit/edit_user_point.php";
		var arg = {
			"psize": psize,
			"pinx" : pinx,
			"student_id" : student_id
		};
		var _arg = [];
		for (var key in arg) {
			_arg.push(key + "=" + encodeURI(arg[key]));
		}
		arg = _arg.join("&");

		if (arg.length!=0) {
			url += page + "?" + arg;
		} else {
			url += page;
		}

		go(url,"self");
	}

</script>

<?php
//-------------------------------------------------------
//page_hrs 區塊 -- 結束
//-------------------------------------------------------
	$conn_user=NULL;
	$conn_mssr=NULL;
?>
<?php };?>

<?php function page_nrs($title="") {?>
<?php
//-------------------------------------------------------
//page_nrs 區塊 -- 開始
//-------------------------------------------------------

	//---------------------------------------------------
	//外部變數
	//---------------------------------------------------

		//config.php
		global $PAGE_SELF;
		global $FOLDER_SELF;
		global $nl;
		global $tab;
		global $fso_enc;
		global $page_enc;

		//local
		global $numrow;
		global $psize;
		global $pnos;
		global $pinx;
		global $sinx;
		global $einx;

		global $config_arrys;

	//---------------------------------------------------
	//內部變數
	//---------------------------------------------------

		$fld_nos=0;  //欄位個數
		$btn_nos=0;  //功能按鈕個數

	//---------------------------------------------------
	//額外處理
	//---------------------------------------------------
?>

<script type="text/javascript">
//-------------------------------------------------------
//頁面初始
//-------------------------------------------------------

	var psize=<?php echo $psize;?>;
	var pinx =<?php echo $pinx;?>;

	window.onload=function(){

	}

</script>

<?php
//-------------------------------------------------------
//page_nrs 區塊 -- 結束
//-------------------------------------------------------
	$conn_user=NULL;
	$conn_mssr=NULL;
?>
<?php };?>

<script type="text/javascript" src="../../../../inc/external/code.js"></script>
<script type="text/javascript">
//-------------------------------------------------------
//呼叫使用者頁面紀錄
//-------------------------------------------------------
	user_page_log(rd=4);
</script>