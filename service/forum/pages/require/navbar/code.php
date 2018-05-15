<?php
//-------------------------------------------------------
//函式: navbar()
//用途: 導覽列
//日期: 2015年4月25日
//作者: mssr_team@cl_ncu
//-------------------------------------------------------

    //---------------------------------------------------
    //測試
    //---------------------------------------------------

        //$navbar=navbar($rd=0);
        //echo '<pre>';
        //print_r($navbar);
        //echo '</pre>';

    function navbar($rd=0){
    //---------------------------------------------------
    //函式: navbar()
    //用途: 導覽列
    //---------------------------------------------------
    //$rd   層級指標,預設0,表示在目前目錄下
    //---------------------------------------------------

        //-----------------------------------------------
        //參數檢驗
        //-----------------------------------------------

            if(!isset($rd)||(int)$rd===0){
                $rd='';
            }else{
                $rd=str_repeat('../',$rd);
            }

        //-----------------------------------------------
        //設定
        //-----------------------------------------------

            global $PAGE_SELF;
            global $_SESSION;
            global $file_server_enable;
            global $arry_ftp1_info;
            global $arry_conn_user;
            global $arry_conn_mssr;

            if(isset($_SESSION['uid'])&&isset($_SESSION['mssr_forum'][0])&&!empty($_SESSION['mssr_forum'][0])){
                $sess_user_id  =(int)$_SESSION['uid'];
                $sess_user_name=trim($_SESSION['mssr_forum'][0]['name']);
                $sess_user_sex =(int)$_SESSION['mssr_forum'][0]['sex'];

                $sess_user_img ='';
                if($sess_user_sex===1)$sess_user_img='../img/default/user_boy.png';
                if($sess_user_sex===2)$sess_user_img='../img/default/user_girl.png';

                if(isset($file_server_enable)&&($file_server_enable)){
                    ////FTP 路徑
                    //$ftp_root="public_html/mssr/info/user";
                    //$ftp_path="{$ftp_root}/{$sess_user_id}/forum/user_sticker";
                    //
                    ////連接 | 登入 FTP
                    //$ftp_conn  =ftp_connect($arry_ftp1_info['host'],$arry_ftp1_info['port']);
                    //$ftp_login =ftp_login($ftp_conn,$arry_ftp1_info['account'],$arry_ftp1_info['password']);
                    //
                    ////設定被動模式
                    //ftp_pasv($ftp_conn,TRUE);
                    //
                    ////獲取檔案目錄
                    //$arry_ftp_file=ftp_nlist($ftp_conn,$ftp_path);
                    //
                    //if(!empty($arry_ftp_file)){
                    //    $sess_user_img="http://".$arry_ftp1_info['host']."/mssr/info/user/{$sess_user_id}/forum/user_sticker/1.jpg";
                    //}
                    //
                    ////關閉連線
                    //ftp_close($ftp_conn);
                    if(@getimagesize("http://".$arry_ftp1_info['host']."/mssr/info/user/{$sess_user_id}/forum/user_sticker/1.jpg")){
                        $sess_user_img="http://".$arry_ftp1_info['host']."/mssr/info/user/{$sess_user_id}/forum/user_sticker/1.jpg";
                    }
                }else{
                    if(file_exists(str_repeat("../",3)."info/user/{$sess_user_id}/forum/user_sticker/1.jpg")){
                        $sess_user_img=str_repeat("../",3)."info/user/{$sess_user_id}/forum/user_sticker/1.jpg";
                    }
                }
            }

        //-----------------------------------------------
        //邀請 SQL
        //-----------------------------------------------

            $request_results=array();
            if(isset($_SESSION['uid'])&&isset($_SESSION['mssr_forum'][0])&&!empty($_SESSION['mssr_forum'][0])){
                $request_results=get_request_info($sess_user_id,'','',$arry_conn_user,$arry_conn_mssr);
                $request_cno    =count($request_results);
                if($request_cno>99)$request_cno='99+';
                //echo "<Pre>";
                //print_r($request_results);
                //echo "</Pre>";
                //die();
            }

		//-----------------------------------------------
		//積分、發文點數，頭銜 SQL
		//-----------------------------------------------

			$get_rank_and_point = get_rank_and_point($sess_user_id,'','',$arry_conn_user,$arry_conn_mssr);
			$rank = $get_rank_and_point['total_rank'];
			$point = $get_rank_and_point['total_point'];
			$appellation = get_appellation($rank,'','',$arry_conn_user,$arry_conn_mssr);
			$appellation_name = $appellation['appellation_name'];
			$appellation_mark = $appellation["appellation_mark"];
			$appellation_mark = "<span style='font-weight: normal;'>" . $appellation_mark . "</span>";

//分組觀察用程式碼
$use_new_system = true;

$sess_school_code = $_SESSION['mssr_forum'][0]['school_code'];

$isolated_school_array = array(
	'hop','dat','tqa','zbq','tap','mid','tbn','stp','cle','osl',
	'uwn','ifx','lqd','dzu','lum','dxu','bts','gwh','vsa','wte',
	'xql','gdc','ctc','glh','gcp','don','lrb','sua','pmc','smps',
	'lhes','cpe','chk','chc','bjd','cte','cwl','okr','shps','ybs'
);

if (isset($sess_school_code) && in_array($sess_school_code, $isolated_school_array)) {
	$use_new_system = false;
}

			//-------------------------------------------
            //負數發文點數處理
            //-------------------------------------------

            	if ($point < 0) {
            		$negative_point = -$point;
            	}

            //-------------------------------------------
            //html  內容
            //-------------------------------------------

                $html ='';
                $html.="
                    <!-- 導覽列,容器,start -->
                    <div class='navbar navbar-default navbar-fixed-top mx-navbar'>

                        <div class='container'>

                            <!-- 導覽列,標題列 -->
                            <div class='navbar-header mx-navbar-header'>
                                <!-- 導覽列,LOGO -->
                                <span class='navbar-brand mx-navbar-brand'>
                                    <a href='{$rd}view/index.php' class='navbar-link'>
                                        <img class='hidden-xs mx-navbar-logo' src='{$rd}img/logo.png' alt='logo,圖示'
                                        width='105px'>
                                        <img class='visible-xs mx-navbar-logo' src='{$rd}img/logo.png' alt='logo,圖示'>
                                    </a>
                                </span>
                ";
                if(isset($_SESSION['uid'])&&isset($_SESSION['mssr_forum'][0])&&!empty($_SESSION['mssr_forum'][0])){
                    $html.="
                                    <!-- 導覽列,縮合觸發 -->
                                    <button class='btn btn-deafult navbar-toggle' data-toggle='collapse' data-target='#navbar-collapse-1'>
                                        <span class='icon-bar'></span>
                                        <span class='icon-bar'></span>
                                        <span class='icon-bar'></span>
                                    </button>
                    ";
                }
if ($use_new_system) {
	$report_url = "https://docs.google.com/forms/d/1vR9fzIBvA_XwT4Brrdf39jQhC4YDK6IsI_tvBNmwykc";
} else {
	$report_url = "https://docs.google.com/forms/d/18CUl6KzeLtP5TB-rDJzmjaDlZ9JF2eCIQe5K7O192yY";
}
                $html.="
<a class='navbar-brand mx-navbar-brand' href='{$report_url}' target='_blank'>
	回報
</a>
                            </div>
                ";
                if(isset($_SESSION['uid'])&&isset($_SESSION['mssr_forum'][0])&&!empty($_SESSION['mssr_forum'][0])){
                    $html.="
                                <!-- 導覽列,縮合列 -->
                                <div class='collapse navbar-collapse' id='navbar-collapse-1'>

                                    <!-- 資料搜尋,start -->
                                        <ul class='nav navbar-nav navbar-left hidden-xs' style='border:0px solid red;'>
                                            <form class='navbar-form navbar-left' onsubmit='return false;'>
                                                <!-- <input type='text' class='form-control search_value input' value='' placeholder='請輸入搜尋條件'
                                                style='width:120px;'> -->
                                                <!-- <button type='button' class='btn btn-default' onclick='search();void(0);'>搜尋</button> -->
                                                <select class='hidden form-control search_type input-sm'
                                                style='width:115px;'>
                                                    <option value='1' selected>查詢人員</option>
                                                    <option value='2'>查詢書籍</option>
                                                    <option value='3'>查詢小組</option>
                                                    <option value='4'>查詢文章編號</option>
                                                </select>
                                                <div class='input-group' style='width:250px;'>
                                                    <div class='input-group-btn' >
                                                        <div class='btn-group'>
                                                            <button class='btn btn-default btn dropdown-toggle' type='button' data-toggle='dropdown'>
                                                                <span data-bind='label' id='set_search_type_view'>查詢人員</span> <span class='caret'></span>
                                                            </button>
                                                            <ul class='dropdown-menu' role='menu'>
                                                                <li class='set_search_type' type_val='1'><a href='javascript:void(0);'>查詢人員    </a></li>
                                                                <li class='set_search_type' type_val='2'><a href='javascript:void(0);'>查詢書籍    </a></li>
                                                                <li class='set_search_type' type_val='3'><a href='javascript:void(0);'>查詢小組    </a></li>
                                                                <li class='set_search_type' type_val='4'><a href='javascript:void(0);'>查詢文章編號</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <input type='text' class='form-control search_value input' value='' placeholder='請輸入搜尋條件' style='width: 125px;'/>
                                                    <span class='input-group-btn'>
                                                        <button class='btn btn-default btn' type='button' onclick='search();void(0);'>
                                                            <i class='glyphicon glyphicon-search'></i>
                                                        </button>
                                                    </span>
                                                </div>

                                            </form>
                                        </ul>
                                    <!-- 資料搜尋,end -->

                                    <ul class='nav navbar-nav navbar-right mx-navbar-nav'>";
if ($use_new_system) {
                        $html.="
										<li class='dropdown'>
											<a id='drop1' class='dropdown-toggle' data-toggle='modal' data-target='#show_rank' aria-haspopup='true' aria-expanded='false'>
												<em class='glyphicon glyphicon-signal'></em>
													積分 {$rank}
											</a>
											<div class='modal fade' id='show_rank' tabindex='-1' role='dialog' aria-labelledby='show_rank_label' aria-hidden='true' data-backdrop='false'>
												<div class='modal-dialog'>
													<div class='modal-content'>
														<div class='modal-header'>
															<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>
																&times;
															</button>
															<div class='modal-title' id='show_rank_label'>
																<div class='text-center' style='font-size: 24px;'>
																	什麼是積分？
																</div>
															</div>
														</div>
														<div class='modal-body' style='font-size: 18px;'>
															<b style='font-size: 18px;'>積分</b>是你在聊書裡積極發表文章以及與書友互動所獲得的分數。
															<br><br>
															<b style='font-size: 18px;'>積分</b>越高，就能得到更好的頭銜喔！
															<br><br>
															<font style='font-size: 18px;' color='red'>發表文章時請用心撰寫文章內容，不恰當的文章內容將會遭受懲罰。</font>
														</div>
														<div class='modal-footer'>
															<div class='text-center'>
																<button type='button' class='btn btn-primary' data-dismiss='modal'>
																	<div style='font-size: 16px;'>確定<div>
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>";
					if ($point >= 0) {
						$html.="
										<li class='dropdown'>
											<a id='drop1' data-toggle='modal' data-target='#show_point_rule' aria-haspopup='true' aria-expanded='false'>
												<em class='glyphicon glyphicon-pencil'></em>
												發文點數 {$point}
											</a>
											<div class='modal fade' id='show_point_rule' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop='false'>
												<div class='modal-dialog'>
													<div class='modal-content'>
														<div class='modal-header'>
															<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
															<div class='text-center' style='font-size: 24px;'>
																什麼是發文點數？
															</div>
														</div>
														<div class='modal-body' style='font-size: 18px;'>
															<b style='font-size: 18px;'>發文點數</b>是用於發表文章的點數，在每一次的發表文章時將會消耗 30 點<b style='font-size: 18px;'>發文點數</b>，<b style='font-size: 18px;'>發文點數</b>不足30點時將不能發表文章。
															<br><br>
															只要每天多回覆別人的文章，就可以獲得<b style='font-size: 18px;'>發文點數</b>喔！
															<br><br>
                                                            回覆一篇文章可獲得10點<b style='font-size: 18px;'>發文點數</b>（書友的12點）。
                                                            <br><br>
															<font style='font-size: 18px;' color='red'>回覆文章時請用心回覆，不恰當的回覆內容將會遭受懲罰。</font>
														</div>
														<div class='modal-footer'>
															<div class='text-center'>
																<button type='button' class='btn btn-primary' data-dismiss='modal'>
																	<div style='font-size: 16px;'>確定<div>
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>
						";
					} else {
						$html.="
										<li class='dropdown'>
											<a id='drop1' data-toggle='modal' data-target='#show_point_rule' aria-haspopup='true' aria-expanded='false'>
												<font color='red'>
													<em class='glyphicon glyphicon-pencil'></em>
													發文點數限制中
												</font>
											</a>
											<div class='modal fade' id='show_point_rule' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' data-backdrop='false'>
												<div class='modal-dialog'>
													<div class='modal-content'>
														<div class='modal-header'>
															<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
															<div class='text-center' style='font-size: 24px;'>
																為什麼我的發文點數被限制了？
															</div>
														</div>
														<div class='modal-body' style='font-size: 18px;'>
															由於你有不當的回文被多數人檢舉，因此遭受到懲罰扣除 50 點<b style='font-size: 18px;'>發文點數</b>，但是你原本持有的<b style='font-size: 18px;'>發文點數</b>不足 50 點，所以<b style='font-size: 18px;'>發文點數</b>遭受到限制，
															導致你之後的回文將無法獲得應有的<b style='font-size: 18px;'>發文點數</b>獎勵。
															<br><br>
															<font style='font-size: 18px;' color='red'>
																想要解除限制狀態，就必須更用心地去回覆別人的文章！
																<br>距離解除限制狀態至少還需要獲得 {$negative_point} 點<b style='font-size: 18px;'>發文點數</b>。
															</font>
															<br><br>
															※你可以在個人頁面中的紀錄裡查看所有被懲罰的事項。
														</div>
														<div class='modal-footer'>
															<div class='text-center'>
																<button type='button' class='btn btn-primary' data-dismiss='modal'>
																	<div style='font-size: 16px;'>確定<div>
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>
						";
					}
}
						$html.="									
                                        <li class=''>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=1'>
                                                <img class='user_img' src='{$sess_user_img}' width='20' height='20' alt='Media' border='0'>
                                                {$sess_user_name}";
if ($use_new_system) {
                        $html.="
                                                 {$appellation_mark}{$appellation_name}{$appellation_mark}";
}
                        $html.="
                                            </a>
                                        </li>
                                        <!-- <li class='dropdown' onclick='close_drop_msg();void(0);'>
                                            <a id='drop1' href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                <em class='glyphicon glyphicon-cog hide'></em>
                                                <img class='user_img' src='{$sess_user_img}' width='20' height='20' alt='Media' border='0'>
                                                {$sess_user_name}
                                                <ul class='dropdown-menu hidden-xs' role='menu' aria-labelledby='drop1' style='width:200px;'>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=2' style='color:#4e4e4e;'><em class='glyphicon glyphicon-book'></em> 我的書櫃</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=3' style='color:#4e4e4e;'><em class='glyphicon glyphicon-pencil'></em> 我的討論</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=5' style='color:#4e4e4e;'><em class='glyphicon glyphicon-user'></em> 我的書友</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=4' style='color:#4e4e4e;'><em class='glyphicon glyphicon-star'></em> 我的小組</a>
                                                    </li>
                                                </ul>
                                            </a>
                                        </li> -->
                                        <li class='hidden'>
                                            <a href='{$rd}view/index.php'><em class='glyphicon glyphicon glyphicon-th-large'></em> 動態牆</a>
                                        </li>
                                        <li class='hidden-sm hidden-md hidden-lg'>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=2'><em class='glyphicon glyphicon-book'></em> 我的書櫃</a>
                                        </li>
                                        <li class='hidden-sm hidden-md hidden-lg'>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=3'><em class='glyphicon glyphicon-pencil'></em> 我的討論</a>
                                        </li>
                                        <li class='hidden-sm hidden-md hidden-lg'>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=5'><em class='glyphicon glyphicon-user'></em> 我的書友</a>
                                        </li>
                                        <li class='hidden-sm hidden-md hidden-lg'>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=4'><em class='glyphicon glyphicon-star'></em> 我的小組</a>
                                        </li>
                                        <!-- <li class='dropdown hidden-xs' onclick='close_drop_msg();void(0);'>
                                            <a id='drop1' href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                <em class='glyphicon glyphicon-list-alt'></em> 我的資訊
                                                <ul class='dropdown-menu' role='menu' aria-labelledby='drop1' style='width:200px;'>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=2' style='color:#4e4e4e;'><em class='glyphicon glyphicon-book'></em> 我的書櫃</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=3' style='color:#4e4e4e;'><em class='glyphicon glyphicon-pencil'></em> 我的討論</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=5' style='color:#4e4e4e;'><em class='glyphicon glyphicon-user'></em> 我的書友</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/user.php?user_id={$sess_user_id}&tab=4' style='color:#4e4e4e;'><em class='glyphicon glyphicon-star'></em> 我的小組</a>
                                                    </li>
                                                </ul>
                                            </a>
                                        </li> -->
                                        <li class='visible-xs'>
                                            <a href='{$rd}view/user.php?user_id={$sess_user_id}&tab=6'>
                                                <em class='glyphicon glyphicon-comment'></em>
                                                <span class='badge request_cno' style='color:#ffffff;background-color:#ff0000;'>{$request_cno}</span>
                                            </a>
                                        </li>
                                        <li class='dropdown hidden-xs'>
                                            <a id='drop_obj' href='javascript:void(0);' class='drop_obj dropdown-toggle' aria-haspopup='true' aria-expanded='false'
                                            drop_flag='up' onclick='drop_msg(this);void(0);'>
                                            <!-- <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'></a> -->
                                                <em class='drop_obj glyphicon glyphicon-comment'></em>

                                                <div id='msg_cno' class='drop_obj'
                                                    style='position:relative;top:-2px;text-align:center;border-radius:99px;width:23px;height:23px;display:inline-block;color:#ffffff;background-color:#ff0000;'>
                                                    <p style='position:relative;top:2px;' class='drop_obj'><b class='drop_obj request_cno'>{$request_cno}</b></p>
                                                </div>

                                                <ul class='drop_msg dropdown-menu' role='menu' aria-labelledby='' style='width:325px;height:382px;overflow-y:auto;'>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='forum.php?method=add_group' style='color:#4e4e4e;'>

                                                        </a>
                                                    </li>
						";
                                                    if(!empty($request_results)&&1===2){
                                                        foreach($request_results as $time=>$request_result):
                                                            foreach($request_result as $request_type=>$arry_request):
                                                                extract($arry_request, EXTR_PREFIX_ALL, "rs");
                                                                if(!in_array(trim($request_type),array('request_friend','article_get_like','article_get_reply','request_friend_success'))){
                                                                    $rs_request_from_sex =(int)$rs_request_from_sex;
                                                                    $rs_request_to_sex   =(int)$rs_request_to_sex;
                                                                    $rs_request_from_name=trim($rs_request_from_name);
                                                                    $rs_request_to_name  =trim($rs_request_to_name);
                                                                    $rs_request_from     =(int)$rs_request_from;
                                                                    $rs_request_to       =(int)$rs_request_to;
                                                                    $rs_request_id       =(int)$rs_request_id;
                                                                    $rs_request_state    =(int)$rs_request_state;
                                                                    $rs_request_read     =(int)$rs_request_read;
                                                                    $rs_keyin_cdate      =trim($rs_keyin_cdate);
                                                                    $rs_rev_id           =(int)$rs_rev_id;

                                                                    $rs_request_from_img ='../img/default/user_boy.png';
                                                                    $rs_request_to_img   ='../img/default/user_boy.png';

                                                                    if($rs_request_from_sex===2)$rs_request_from_img ='../img/default/user_girl.png';
                                                                    if($rs_request_to_sex===2)$rs_request_to_img ='../img/default/user_girl.png';
                                                                }

                                                        if(trim($request_type)==='article_get_reply'):
                                                            $rs_request_from_name=trim($rs_request_from_name);
                                                            $rs_request_to_name  =trim($rs_request_to_name);
                                                            $rs_request_from     =(int)$rs_request_from;
                                                            $rs_request_to       =(int)$rs_request_to;
                                                            $rs_article_id       =(int)$rs_article_id;
                                                            $rs_article_title    =trim($rs_article_title);
                                                            $rs_request_from_img ='../img/default/user_boy.png';
                                                            $rs_request_to_img   ='../img/default/user_boy.png';

                                                            if((int)$rs_request_from_sex===2)$rs_request_from_img ='../img/default/user_girl.png';
                                                            if((int)$rs_request_to_sex===2)$rs_request_to_img ='../img/default/user_girl.png';

                                                            $rs_group_id=(int)$rs_group_id;

                                                            if($rs_group_id===0)$get_from=1;
                                                            if($rs_group_id!==0)$get_from=2;

                                                            $href_1="user.php?user_id={$rs_request_from}&tab=1";
                                                            $href_2="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_3="reply.php?get_from={$get_from}&article_id={$rs_article_id}";

                                                            $rs_content="
                                                                已回覆你的文章：
                                                                <a href='javascript:void(0);' onclick=\"navbar_href('{$href_3}');\">{$rs_article_title}</a>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='javascript:void(0);' onclick=\"navbar_href('{$href_1}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='article_get_like'):
                                                            $rs_request_from_name=trim($rs_request_from_name);
                                                            $rs_request_to_name  =trim($rs_request_to_name);
                                                            $rs_request_from     =(int)$rs_request_from;
                                                            $rs_request_to       =(int)$rs_request_to;
                                                            $rs_article_id       =(int)$rs_article_id;
                                                            $rs_article_title    =trim($rs_article_title);
                                                            $rs_request_from_img ='../img/default/user_boy.png';
                                                            $rs_request_to_img   ='../img/default/user_boy.png';

                                                            if((int)$rs_request_from_sex===2)$rs_request_from_img ='../img/default/user_girl.png';
                                                            if((int)$rs_request_to_sex===2)$rs_request_to_img ='../img/default/user_girl.png';

                                                            $rs_group_id=(int)$rs_group_id;

                                                            if($rs_group_id===0)$get_from=1;
                                                            if($rs_group_id!==0)$get_from=2;

                                                            $href_1="user.php?user_id={$rs_request_from}&tab=1";
                                                            $href_2="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_3="reply.php?get_from={$get_from}&article_id={$rs_article_id}";

                                                            $rs_content="
                                                                已對你的文章：
                                                                <a href='javascript:void(0);' onclick=\"navbar_href('{$href_3}');\">{$rs_article_title}</a>
                                                                按讚
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='javascript:void(0);' onclick=\"navbar_href('{$href_1}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='ok_request_rec_us_book_rev'):
                                                            $rs_book_sid=trim($arry_request['book_sid']);
                                                            if($rs_book_sid!==''){
                                                                $arry_book_infos=get_book_info('',$rs_book_sid,$array_filter=array('book_name'),$arry_conn_mssr);
                                                                if(empty($arry_book_infos))continue;
                                                                $rs_book_name=trim($arry_book_infos[0]['book_name']);
                                                                if(mb_strlen($rs_book_name)>12){
                                                                    $rs_book_name=mb_substr($rs_book_name,0,12)."..";
                                                                }
                                                            }else{continue;}
                                                            $href_1="user.php?user_id={$rs_request_from}&tab=1";
                                                            $href_2="user.php?user_id={$rs_request_to}&tab=1";
                                                            $rs_content      ="
                                                                已回應 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_from_name}</a> 的請求，
                                                                推薦一本書籍給 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_from_name}</a>。
                                                            ";
                                                            $rs_content.="
                                                                <h5 style='position:relative;top:-3px;'>
                                                                    書名：<a href='article.php?get_from=1&book_sid={$rs_book_sid}'>【{$rs_book_name}】</a>
                                                                </h5>
                                                            ";
                                                            $rs_content.="
                                                                <h5>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-6px;left:55px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_edit_ok_request_rec_us_book(this);void(0);'>確定</button>
                                                            ";
                                                            $rs_content.="
                                                                </h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:90px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_request_to_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_rec_us_book_rev'):
                                                            $href_1="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_2="user.php?user_id={$rs_request_from}&tab=1";
                                                            $rs_content      ="
                                                                向 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 提出邀請，
                                                                希望 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 能推薦一本書籍給{$rs_request_from_name}
                                                            ";
                                                            $rs_content.="
                                                                <h5><button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                onclick='location.href=
                                                            ";
                                                            $rs_content.='"user.php?user_id=';
                                                            $rs_content.="{$sess_user_id}";
                                                            $rs_content.='&tab=6"';
                                                            $rs_content.="
                                                                ;'
                                                                >前往回應</button></h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_article_rev'):
                                                            $rs_group_id     =(int)$rs_group_id;
                                                            $rs_article_id   =(int)$rs_article_id;
                                                            $rs_article_title=trim($rs_article_title);
                                                            $href_1="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_2="user.php?user_id={$rs_request_from}&tab=1";
                                                            $href_3="reply.php?get_from=1&article_id={$rs_article_id}";
                                                            $href_4="reply.php?get_from=2&article_id={$rs_article_id}";
                                                            $rs_content      ="
                                                                向 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 提出邀請，
                                                                希望 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 能一起參與討論文章：
                                                            ";
                                                            if($rs_group_id===0){
                                                                $rs_content.="<a target='_blank' href='#' onclick=\"navbar_href('{$href_3}');\">{$rs_article_title}</a>";
                                                            }
                                                            if($rs_group_id!==0){
                                                                $rs_content.="<a target='_blank' href='#' onclick=\"navbar_href('{$href_4}');\">{$rs_article_title}</a>";
                                                            }
                                                            $rs_content.="
                                                                <h5>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_article_rev(this);void(0);'>確定</button>
                                                            ";
                                                            $rs_content.="
                                                                </h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_create_group_rev'):
                                                            $rs_group_id  =(int)$rs_group_id;
                                                            $rs_group_name=trim($rs_group_name);
                                                            $href_1="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_2="article.php?get_from=2&group_id={$rs_group_id}";
                                                            $href_3="user.php?user_id={$rs_request_from}&tab=1";
                                                            $rs_content ="
                                                                向 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 提出邀請，
                                                                希望 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 能一同聯署建立小組：
                                                                <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_group_name}</a>
                                                            ";
                                                            $rs_content.="
                                                                <h5>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_create_group(this,1);void(0);'>我要聯署
                                                                    </button>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_create_group(this,2);void(0);'>我不要聯署
                                                                    </button>
                                                            ";
                                                            $rs_content.="
                                                                </h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_3}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_join_to_group_rev'):
                                                            $rs_group_id  =(int)$rs_group_id;
                                                            $rs_group_name=trim($rs_group_name);
                                                            $href_1="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_2="article.php?get_from=2&group_id={$rs_group_id}";
                                                            $href_3="user.php?user_id={$rs_request_from}&tab=1";
                                                            $rs_content ="
                                                                向 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 提出申請，
                                                                希望能加入你的小組：
                                                                <a target='_blank' href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_group_name}</a>
                                                            ";
                                                            $rs_content.="
                                                                <h5>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_join_to_group(this,1);void(0);'>允許
                                                                    </button>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_join_to_group(this,2);void(0);'>拒絕
                                                                    </button>
                                                            ";
                                                            $rs_content.="
                                                                </h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_3}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_join_us_group_rev'):
                                                            $rs_group_id  =(int)$rs_group_id;
                                                            $rs_group_name=trim($rs_group_name);
                                                            $href_1="user.php?user_id={$rs_request_to}&tab=1";
                                                            $href_2="article.php?get_from=2&group_id={$rs_group_id}";
                                                            $href_3="user.php?user_id={$rs_request_from}&tab=1";
                                                            $rs_content ="
                                                                向 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 提出邀請，
                                                                希望 <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_request_to_name}</a> 能加入他的小組：
                                                                <a target='_blank' href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_group_name}</a>
                                                            ";
                                                            $rs_content.="
                                                                <h5>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_join_us_group(this,1);void(0);'>接受
                                                                    </button>
                                                                    <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                    request_id='{$rs_request_id}' onclick='quick_request_join_us_group(this,2);void(0);'>拒絕
                                                                    </button>
                                                            ";
                                                            $rs_content.="
                                                                </h5>
                                                            ";
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <img src='{$rs_request_from_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            <a href='#' onclick=\"navbar_href('{$href_3}');\">{$rs_request_from_name}</a>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                        if(trim($request_type)==='request_friend'):
                                                            $rs_user_name       =trim($rs_user_name);
                                                            $rs_friend_name     =trim($rs_friend_name);
                                                            $rs_create_by       =(int)$rs_create_by;
                                                            $rs_user_id         =(int)$rs_user_id;
                                                            $rs_friend_id       =(int)$rs_friend_id;
                                                            $rs_friend_content  =trim($rs_content);
                                                            $rs_friend_state    =(int)$rs_friend_state;
                                                            $rs_keyin_mdate     =trim($rs_keyin_mdate);

                                                            if($rs_friend_state===1){
                                                                $rs_friend_state_html='成功';
                                                            }elseif($rs_friend_state===2){
                                                                $rs_friend_state_html='失敗';
                                                            }

                                                            $rs_user_img    ='../img/default/user_boy.png';
                                                            $rs_friend_img  ='../img/default/user_boy.png';

                                                            if($rs_user_sex===2)$rs_user_img ='../img/default/user_girl.png';
                                                            if($rs_friend_sex===2)$rs_friend_img ='../img/default/user_girl.png';

                                                            if($rs_user_id!==$sess_user_id){
                                                                if(@getimagesize("http://".$arry_ftp1_info['host']."/mssr/info/user/{$rs_user_id}/forum/user_sticker/1.jpg")){
                                                                    $rs_friend_img="http://".$arry_ftp1_info['host']."/mssr/info/user/{$rs_user_id}/forum/user_sticker/1.jpg";
                                                                }
                                                            }
                                                            if($rs_friend_id!==$sess_user_id){
                                                                if(@getimagesize("http://".$arry_ftp1_info['host']."/mssr/info/user/{$rs_friend_id}/forum/user_sticker/1.jpg")){
                                                                    $rs_friend_img="http://".$arry_ftp1_info['host']."/mssr/info/user/{$rs_friend_id}/forum/user_sticker/1.jpg";
                                                                }
                                                            }

                                                            $href_1="user.php?user_id={$rs_user_id}&tab=1";
                                                            $href_2="user.php?user_id={$rs_friend_id}&tab=1";

                                                            if($rs_friend_state===3){
                                                                $rs_content ="
                                                                    <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_user_name}</a>
                                                                    已經提出要與
                                                                    <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_friend_name}</a>
                                                                    成為書友，
                                                                    請問你是否要跟他成為書友?
                                                                ";
                                                                $rs_content.="
                                                                    <h5>
                                                                        <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                        create_by='{$rs_create_by}'
                                                                        user_id='{$rs_user_id}'
                                                                        friend_id='{$rs_friend_id}'
                                                                        onclick='quick_request_friend(this,1);void(0);'>接受
                                                                        </button>
                                                                        <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                        create_by='{$rs_create_by}'
                                                                        user_id='{$rs_user_id}'
                                                                        friend_id='{$rs_friend_id}'
                                                                        onclick='quick_request_friend(this,2);void(0);'>拒絕
                                                                        </button>
                                                                ";
                                                                if(trim($rs_friend_content)!==''){
                                                                    $rs_content.="
                                                                        <button type='button' class='btn btn-default btn-xs' style='position:relative;top:-3px;'
                                                                        onclick='location.href=
                                                                    ";
                                                                    $rs_content.='"user.php?user_id=';
                                                                    $rs_content.="{$sess_user_id}";
                                                                    $rs_content.='&tab=6"';
                                                                    $rs_content.="
                                                                        ;'
                                                                        >觀看留言</button>
                                                                    ";
                                                                }
                                                                $rs_content.="
                                                                    </h5>
                                                                ";
                                                            }else{
                                                                $rs_content ="
                                                                    <a href='#' onclick=\"navbar_href('{$href_1}');\">{$rs_user_name}</a>
                                                                    提出與
                                                                    <a href='#' onclick=\"navbar_href('{$href_2}');\">{$rs_friend_name}</a>
                                                                    的
                                                                    交友申請結果為 : {$rs_friend_state_html}
                                                                ";
                                                            }
                                                            $html.="
                                                                <li role='presentation' style='width:100%;height:70px;background-color:#fdfdff;border-bottom:1px solid #ebebeb;'>
                                                                    <div style='width:100%;word-break:break-all;padding:0px 5px 0px 5px;'>
                                                                        <!-- <img src='{$rs_user_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/> -->
                                                                        <img src='{$rs_friend_img}' width='55px' height='55px' border='0' alt='0'
                                                                        style='float:left;position:relative;left:-3px;top:7px;'
                                                                        onmouseover=''/>
                                                                        <a role='menuitem' tabindex='-1' href='javascript:void(0);'
                                                                        style='color:#4e4e4e;text-decoration:none;'
                                                                        onmouseover=''>
                                                                            {$rs_content}
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            ";
                                                        endif;

                                                    endforeach;endforeach;}
                    $html.="
                                                </ul>
                                            </a>
                                        </li>
                                        <li class='dropdown' onclick='close_drop_msg();void(0);'>
                                            <a id='drop1' href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                <em class='glyphicon glyphicon-list'></em>
                                                前往
                                                <ul class='dropdown-menu' role='menu' aria-labelledby='drop1' style='width:200px;'>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='{$rd}view/index.php' style='color:#4e4e4e;'>前往動態牆</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='/mssr/service/code.php?mode=read_the_registration' style='color:#4e4e4e;'>前往悅讀登記</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='/mssr/service/mssr_menu.php' style='color:#4e4e4e;'>前往明日閱讀</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='/ac/index.php' style='color:#4e4e4e;'>前往星球首頁</a>
                                                    </li>
                                                </ul>
                                            </a>
                                        </li>
                                        <li class='dropdown' onclick='close_drop_msg();void(0);'>
                                            <a id='drop1' href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                <em class='glyphicon glyphicon-cog'></em>
                                                設定
                                                <ul class='dropdown-menu' role='menu' aria-labelledby='drop1' style='width:200px;'>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='forum.php?method=add_group' style='color:#4e4e4e;'>建立聊書小組</a>
                                                    </li>
                                                    <li role='presentation' style='width:100%;'>
                                                        <a role='menuitem' tabindex='-1' href='../controller/logout.php' style='color:#4e4e4e;'>登出</a>
                                                    </li>
                                                </ul>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                    ";
                }
                $html.="
                        </div>
                    </div>
                    <!-- 導覽列,容器,end -->
                ";

        //-----------------------------------------------
        //處理
        //-----------------------------------------------

            if(1==2){ //除錯用
                echo '<pre>';
                print_r($html);
                echo '</pre>';
            }

        //-----------------------------------------------
        //回傳
        //-----------------------------------------------

            return $html;
    }
?>