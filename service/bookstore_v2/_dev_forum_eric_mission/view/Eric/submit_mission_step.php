<?php
    //---------------------------------------------------
        //SESSION
        @session_start();

        //啟用BUFFER
        @ob_start();

        //外掛設定檔
        require_once(str_repeat("../",4).'config/config.php');

        //外掛頁面檔
        require_once(str_repeat("../",2).'pages/code.php');

        //外掛函式檔
        $funcs=array(
            APP_ROOT.'inc/code',
			APP_ROOT.'lib/php/db/code',
            APP_ROOT.'service/_dev_forum_eric_mission/inc/code'
        );
        func_load($funcs,true);

        //清除並停用BUFFER
        @ob_end_clean();

    //---------------------------------------------------
    //資料庫
    //---------------------------------------------------

        //-----------------------------------------------
        //連線物件
        //-----------------------------------------------

            //建立連線 mssr
            $conn_mssr=conn($db_type='mysql',$arry_conn_mssr);

            //建立連線 user
            $conn_user=conn($db_type='mysql',$arry_conn_user);

        //-----------------------------------------------
		$arrys_sess_login_info=get_login_info($db_type='mysql',$arry_conn_user,$APP_ROOT);
		if(empty($arrys_sess_login_info)){
			$msg="您沒有權限進入，請洽詢明日星球團隊人員!";
			$jscript_back="
				<script>
					alert('{$msg}');
					location.href='/ac/index.php';
				</script>
			";
			die($jscript_back);
		}

		if(isset($arrys_sess_login_info[0]['uid'])){

			$step_number = $_POST["step_number"];
			$uid = (int)$arrys_sess_login_info[0]['uid'];
			$task_id = $_POST["task_id"];
			$deliver_uid = $_POST["deliver_uid"];

			$step_number_now = (int)$step_number+1;//現在正在進行的步驟
			//------------------------------------------------------
			$sql="
				SELECT *
				FROM `mssr_forum`.`dev_mission_step_log` as a
				inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
				WHERE a.`deliver_uid`={$deliver_uid} and a.`accept_uid`={$uid} and b.`step_number`={$step_number_now} and b.`group_task_id`={$task_id}
			";
			$get_master_atask_id =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
			foreach($get_master_atask_id as $key => $get_master_atask_id){
				$master_atask_id[$key] = $get_master_atask_id['master_atask_id'];//抓取主的任務接收編號
				$step_score[$key]	   = $get_master_atask_id['available_score'];//抓取主的任務的可獲得分數
			}
			//------------------------------------------------------
			//第一步驟> 抓取文章編號與書本編號
			//------------------------------------------------------
			if($step_number==0){
				//------------------------------------------------------
				$sql="
					SELECT *
					FROM `mssr_forum`.`mssr_forum_article` as a
					inner join `mssr_forum`.`mssr_forum_article_book_rev` as b on a.`article_id`=b.`article_id`
					inner join `mssr_forum`.`mssr_forum_article_eagle_rev` as c on a.`article_id`=c.`article_id`
					inner join `mssr_forum`.`dev_article_group_mission_rev` as d on a.`article_id`=d.`article_id`
					WHERE `user_id`={$uid} and `article_from`=3 and `article_state` =1 and `group_task_id`={$task_id}
				";
				$get_article =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$article_id = $get_article[0]['article_id'];
				$book_sid   = $get_article[0]['book_sid'];

				if(isset($get_article[0]['article_id'])){//如果有實際抓到文章編號，則新增資料進第一步的log、更新第一步已完成、更新任務步驟時間
					//------------------------------------------------------
					//將資料Insert進step1的資料表
					//------------------------------------------------------
					$sql="
						INSERT INTO `mssr_forum`.`dev_master_step1`(`master_atask_id`,`book_sid`,`article_id`,`score`)
						VALUES ({$master_atask_id[0]},'$book_sid',{$article_id},30)
					";
					$insert_mission_step1 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					//------------------------------------------------------
					//更新log資料表
					//------------------------------------------------------
					$sql="
						UPDATE `mssr_forum`.`dev_mission_step_log` as a
						inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
						SET `step_state`=1
						WHERE a.`step_number`=1 and a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
					";
					$update_mission_step1 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

					$sql="
						UPDATE `mssr_forum`.`dev_mission_step_log` as a
						inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
						SET `start_step_time`= NOW(),`end_step_time`=DATE_ADD(NOW(),INTERVAL 3 DAY)
						WHERE a.`step_number`=2 and a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
					";
					$update_mission_step_time =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				}
			//------------------------------------------------------
			//第二步驟> 結算文章的按讚數與回覆數
			//------------------------------------------------------
			}else if($step_number==1){
				//------------------------------------------------------
				$sql="
					SELECT a.`article_id`,e.`article_title`
					FROM `mssr_forum`.`mssr_forum_article` as a
					inner join `mssr_forum`.`mssr_forum_article_book_rev` as b on a.`article_id`=b.`article_id`
					inner join `mssr_forum`.`mssr_forum_article_eagle_rev` as c on a.`article_id`=c.`article_id`
					inner join `mssr_forum`.`dev_article_group_mission_rev` as d on a.`article_id`=d.`article_id`
					inner join `mssr_forum`.`mssr_forum_article_detail` as e on a.`article_id`=e.`article_id`
					WHERE `user_id`={$uid} and `article_from`=3 and `article_state` =1 and `group_task_id`={$task_id}
				";
				$get_article =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$article_id    = $get_article[0]['article_id'];//抓取文章編號
				//------------------------------------------------------
				$article_like_cno = 0;//文章的按讚數
				$sql="
					SELECT sum(a.`article_like_cno`) as cno
					FROM `mssr_forum`.`mssr_forum_article` as a
					inner join `mssr_forum`.`dev_article_group_mission_rev` as b on a.`article_id`=b.`article_id`
					WHERE a.`article_id`={$article_id}
				";
				$get_article_like_cno =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$article_like_cno = $get_article_like_cno[0]['cno'];//抓取活動文章的按讚數
				//------------------------------------------------------
				$article_reply_like_cno = 0;//回覆文章的按讚數
				$sql="
					SELECT sum(a.`reply_like_cno`) as cno
					FROM `mssr_forum`.`mssr_forum_reply` as a
					inner join `mssr_forum`.`dev_reply_group_mission_rev` as b on a.`reply_id`=b.`reply_id`
					WHERE a.`article_id`={$article_id} and `reply_state`=1
				";
				$get_article_reply_like_cno =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$article_reply_like_cno = $get_article_reply_like_cno[0]['cno'];//抓取回覆文章的按讚數
				//------------------------------------------------------
				$article_reply_cno = 0;//回覆文章的次數
				$sql="
					SELECT count(a.`reply_id`) as cno
					FROM `mssr_forum`.`mssr_forum_reply` as a
					inner join `mssr_forum`.`dev_reply_group_mission_rev` as b on a.`reply_id`=b.`reply_id`
					WHERE a.`article_id`={$article_id} and a.`user_id`!={$uid} and a.`reply_state`=1
				";
				$get_article_reply_cno =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				if(isset($get_article_reply_cno[0]['cno'])){
					$article_reply_cno = $get_article_reply_cno[0]['cno'];//抓取活動文章的回覆數
				}
				//------------------------------------------------------
				$like_score  = 0;//暫存按讚的分數
				$article_reply_score = 0;//暫存文章回覆的分數
				$like_cno = $article_like_cno+$article_reply_like_cno;

				$like_score = $like_cno*$step_score[0];
				$article_reply_score= $article_reply_cno*$step_score[1];
				//------------------------------------------------------
				//將資料Insert進step2的資料表
				//------------------------------------------------------
				$sql="
					INSERT INTO `mssr_forum`.`dev_master_step2_like`(`master_atask_id`,`like_number`,`score`)
					VALUES ({$master_atask_id[0]},{$like_cno},{$like_score})
				";
				$insert_mission_step2_1 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

				$sql="
					INSERT INTO `mssr_forum`.`dev_master_step2_reply`(`master_atask_id`,`reply_number`,`score`)
					VALUES ({$master_atask_id[1]},{$article_reply_cno},{$article_reply_score})
				";
				$insert_mission_step2_2 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				//------------------------------------------------------
				//更新log資料表
				//------------------------------------------------------
				$sql="
					UPDATE `mssr_forum`.`dev_mission_step_log` as a
					inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
					SET `step_state`=1
					WHERE a.`step_number`=2 and a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
				";
				$update_mission_state2 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

				$sql="
					UPDATE `mssr_forum`.`dev_mission_step_log` as a
					inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
					SET `start_step_time`= NOW()
					WHERE a.`step_number`=3 and a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
				";
				$update_mission_step_time =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

			//------------------------------------------------------
			//第三步驟> 提交好友的名單
			//------------------------------------------------------
			}else if($step_number==2){
				$friend_confirm = array();
				$friend_confirm = $_POST["friend_confirm"];
				//echo($friend_confirm);
 				for($i=0; $i<count($friend_confirm); $i++){
					$sql="
						SELECT *
						FROM `mssr_forum`.`dev_mission_step_log` as a
						inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
						WHERE a.`accept_uid`={$friend_confirm[$i]} and b.`group_task_id`={$task_id}
					";
					$select_friend =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
 					/*echo "<pre>";
					print_r($sql);
					echo "<pre>";  */
					if(!isset($select_friend[0]['master_ctask_id'])){
						$sql="
							INSERT  INTO `mssr_forum`.`dev_master_step3` (`master_atask_id`,`next_uid`,`score`)
							VALUES ({$master_atask_id[0]},{$friend_confirm[$i]},{$step_score[0]})
						";
						$insert_mission_step3 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

						$sql="
							INSERT  INTO `mssr_forum`.`dev_complete_mission_log` (`group_task_id`,`deliver_uid`,`accept_uid`,`total_master_score`,`mission_state`)
							VALUES ({$task_id},{$uid},{$friend_confirm[$i]},0,0)
						";
						$insert_mission_log =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					}
				}
				//------------------------------------------------------
				//結算自己的成績
				//------------------------------------------------------
				$sql="
					SELECT *
					FROM `mssr_forum`.`dev_mission_step_log` as a
					inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
					WHERE a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
				";
				$get_all_master_id = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				foreach($get_all_master_id as $key =>$get_all_master_id){
					$all_master_id[$key] = $get_all_master_id['master_atask_id'];
				}

				$total_score = 0;//存取所有的分數
				$sql="
					SELECT `score`
					FROM `mssr_forum`.`dev_master_step1`
					WHERE `master_atask_id`={$all_master_id[0]}
				";
				$get_step1_score = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$total_score += $get_step1_score[0]['score'];
				$sql="
					SELECT `score`
					FROM `mssr_forum`.`dev_master_step2_like`
					WHERE `master_atask_id`={$all_master_id[1]}
				";
				$get_step2_1_score = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$total_score += $get_step2_1_score[0]['score'];
				$sql="
					SELECT `score`
					FROM `mssr_forum`.`dev_master_step2_reply`
					WHERE `master_atask_id`={$all_master_id[2]}
				";
				$get_step2_2_score = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$total_score += $get_step2_2_score[0]['score'];
				$sql="
					SELECT sum(`score`) as total_score
					FROM `mssr_forum`.`dev_master_step3`
					WHERE `master_atask_id`={$all_master_id[3]}
				";
				$get_step3_score = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$total_score += $get_step3_score[0]['total_score'];
				//------------------------------------------------------
				//結算朋友的成績
				//------------------------------------------------------
				$friend_results =get_forum_friend($uid,$friend_id=0,$arry_conn_mssr);//取得好友名單
				$friend_list = array();
				$j = 0;
				for($i=0;$i<count($friend_results);$i++){
					if($friend_results[$i]['friend_state']==1){
						if($friend_results[$i]['friend_id']==$uid){
							$friend_list[$j] = $friend_results[$i]['user_id'];
							$j++;
						}else if($friend_results[$i]['friend_id']!=$uid){
							$friend_list[$j] = $friend_results[$i]['friend_id'];
							$j++;
						}
					}
				}

				$friend_enjoy = array();
				$all_slave_id = array();
				$slave_step2_1_score = array();
				$slave_step2_2_score = array();
				$slave_step2_3_score = array();
				$i =0;
				for($y=0; $y<count($friend_list); $y++){
					$sql="
						SELECT `slave_atask_id`
						FROM `mssr_forum`.`dev_mission_slave_log`
						WHERE `view_uid`={$friend_list[$y]} and `accept_uid`={$uid} and `group_task_id`={$task_id}
					";
					$get_all_slave_id = db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					if(isset($get_all_slave_id[0]['slave_atask_id'])){
						$all_slave_id[$i] = $get_all_slave_id[0]['slave_atask_id'];
						$friend_enjoy[$i] = $friend_list[$y];
						$i++;
					}
				}
				//------------------------------------------------------
				for($x=0; $x<$i; $x++){//將有參與的朋友，更新他們的貢獻分數
					$sql="
						SELECT `score`
						FROM `mssr_forum`.`dev_slave_step2_like`
						WHERE `slave_atask_id`={$all_slave_id[$x]}
					";
					$get_slave_like_number =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					$slave_step2_1_score[$x] = $get_slave_like_number[0]['score'];


					$sql="
						SELECT `score`
						FROM `mssr_forum`.`dev_slave_step2_reply`
						WHERE `slave_atask_id`={$all_slave_id[$x]}
					";
					$get_slave_reply_number =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					$slave_step2_2_score[$x] = $get_slave_reply_number[0]['score'];

					$sql="
						SELECT `score`
						FROM `mssr_forum`.`dev_slave_step2_reply_liked`
						WHERE `slave_atask_id`={$all_slave_id[$x]}
					";
					$get_slave_reply_like_number =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					$slave_step2_3_score[$x] = $get_slave_reply_like_number[0]['score'];

				//------------------------------------------------------
					$sql="
						SELECT *
						FROM  `mssr_forum`.`dev_member_score`
						WHERE `u_id`={$friend_enjoy[$x]}
					";
					$get_friend_score =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
					$friend_score[$x] = $get_friend_score[0]['score'];

					$all_score[$x] = $friend_score[$x] + $slave_step2_1_score[$x] + $slave_step2_2_score[$x] +$slave_step2_3_score[$x];

					$sql="
						UPDATE `mssr_forum`.`dev_member_score`
						SET `score`={$all_score[$x]}
						WHERE `u_id`={$friend_enjoy[$x]}
					";
					$update_friend_score =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				}
				//------------------------------------------------------
				//更新自己的log資料表
				//------------------------------------------------------
				$sql="
					UPDATE `mssr_forum`.`dev_mission_step_log` as a
					inner join `mssr_forum`.`dev_group_mission_master` as b on a.`master_task_id` = b.`master_task_id`
					SET a.`step_state`=1, a.`end_step_time`=NOW()
					WHERE a.`step_number`=3 and a.`accept_uid`={$uid} and a.`deliver_uid`={$deliver_uid} and b.`group_task_id`={$task_id}
				";
				$update_step_state3 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

				$sql="
					UPDATE `mssr_forum`.`dev_complete_mission_log`
					SET `mission_state`=1,`total_master_score`={$total_score},`finish_time`=NOW()
					WHERE `accept_uid`={$uid} and `deliver_uid`={$deliver_uid} and `group_task_id`={$task_id}
				";
				$update_mission_state3 =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
			//------------------------------------------------------
				$sql="
					SELECT *
					FROM  `mssr_forum`.`dev_member_score`
					WHERE `u_id`={$uid}
				";
				$get_member_score =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);
				$get_score = $get_member_score[0]['score'];

				$score = $get_score + $total_score;
				$sql="
					UPDATE `mssr_forum`.`dev_member_score`
					SET `score`={$score}
					WHERE `u_id`={$uid}
				";
				$update_member_score =db_result($conn_type='pdo',$conn_mssr,$sql,array(),$arry_conn_mssr);

			}
		}


?>