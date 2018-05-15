<?php
//-------------------------------------------------------
//明日書店網管中心
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
                    APP_ROOT.'center/admin_center/inc/code',
                    APP_ROOT.'lib/php/db/code',
                    APP_ROOT.'inc/code'
                    );
        func_load($funcs,true);

        //清除並停用BUFFER
        @ob_end_clean();

    //---------------------------------------------------
    //有無登入
    //---------------------------------------------------

        if(!login_check(array('a'))){
            $url=str_repeat("../",2).'mod/m_login/loginF.php';
            header("Location: {$url}");
            die();
        }

    //---------------------------------------------------
    //SESSION
    //---------------------------------------------------

        //初始化，承接變數
        $_sess_a=$_SESSION['a'];
        foreach($_sess_a as $field_name=>$field_value){
            if(!is_array($field_value))$$field_name=trim($field_value);
        }
        //echo "<Pre>";
        //print_r($_SESSION['a']);
        //echo "</Pre>";

    //---------------------------------------------------
    //接收參數
    //---------------------------------------------------

        //分頁
        $psize=(isset($_GET['psize']))?(int)$_GET['psize']:15;
        $pinx =(isset($_GET['pinx']))?(int)$_GET['pinx']:1;
        $psize=($psize===0)?15:$psize;
        $pinx =($pinx===0)?1:$pinx;

        $filter      ='';   //查詢條件式
        $query_fields='';   //查詢欄位,顯示用

        if(isset($_SESSION['a']['query']['m_forum_use_analysis_photo']['filter'])){
            $filter=trim($_SESSION['a']['query']['m_forum_use_analysis_photo']['filter']);
        }
        if(isset($_SESSION['a']['query']['m_forum_use_analysis_photo']['query_fields'])){
            $query_fields=$_SESSION['a']['query']['m_forum_use_analysis_photo']['query_fields'];
        }
        //echo "<Pre>";
        //print_r($filter);
        //echo "</Pre>";

    //---------------------------------------------------
    //設定參數
    //---------------------------------------------------

        //網頁標題
        $title="明日星球,明日書店網管中心";

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

        //-----------------------------------------------
        //主SQL
        //-----------------------------------------------

    //---------------------------------------------------
    //分頁處理
    //---------------------------------------------------

    //---------------------------------------------------
    //資料,設定
    //---------------------------------------------------

        page_hrs($title);
        die();
?>


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

        //local
        global $numrow;
        global $psize;
        global $pnos;
        global $pinx;
        global $sinx;
        global $einx;

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

        $query_year=(isset($_SESSION['a']['query']['m_forum_use_analysis_photo']['query_year']))?(int)$_SESSION['a']['query']['m_forum_use_analysis_photo']['query_year']:(int)date("Y");
        $total=0;
        $application_cno=0;
        $arry_build_time_info=[];
        $sql="
            #************************************
            #此段SQL語法為撈取星球聊書系統使用趨勢圖
            #經Brian指示製作此一功能
            #若slow log上榜為正常現象
            #************************************
                SELECT
                    DATE_FORMAT(`mssr_forum`.`mssr_forum_article`.`keyin_cdate`, '%Y-%m') AS `keyin_cdate`,
                    COUNT(`mssr_forum`.`mssr_forum_article`.`keyin_cdate`) AS `cno`
                FROM `mssr_forum`.`mssr_forum_article`
                WHERE 1=1
                GROUP BY DATE_FORMAT(`mssr_forum`.`mssr_forum_article`.`keyin_cdate`, '%Y-%m')

            UNION ALL

                SELECT
                    DATE_FORMAT(`mssr_forum`.`mssr_forum_reply`.`keyin_cdate`, '%Y-%m') AS `keyin_cdate`,
                    COUNT(`mssr_forum`.`mssr_forum_reply`.`keyin_cdate`) AS `cno`
                FROM `mssr_forum`.`mssr_forum_reply`
                WHERE 1=1
                GROUP BY DATE_FORMAT(`mssr_forum`.`mssr_forum_reply`.`keyin_cdate`, '%Y-%m')
        ";
        $db_results=db_result($conn_type='pdo',$conn_mssr,$sql,$arry_limit=array(),$arry_conn_mssr);
        foreach($db_results as $db_result){
            $rs_keyin_cdate=trim($db_result['keyin_cdate']);
            $rs_cno        =(int)trim($db_result['cno']);
            if(!array_key_exists($rs_keyin_cdate,$arry_build_time_info))$arry_build_time_info[$rs_keyin_cdate]=0;
            $arry_build_time_info[$rs_keyin_cdate]=$arry_build_time_info[$rs_keyin_cdate]+$rs_cno;
            $total         =$total+$rs_cno;
        }
        foreach($arry_build_time_info as $rs_build_time => $rs_cno){
            if((int)$query_year!==(int)date("Y",strtotime($rs_build_time))){
                unset($arry_build_time_info[$rs_build_time]);
            }
        }
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
    <?php echo robots($allow=true);?>

    <!-- 通用 -->
    <link rel="stylesheet" type="text/css" href="../../../../inc/code.css" media="all" />
    <script type="text/javascript" src="../../../../lib/php/image/verify/verify_image.js"></script>
    <script type="text/javascript" src="../../../../inc/code.js"></script>
    <script type="text/javascript" src="../../../../lib/js/flash/code.js"></script>
    <script type="text/javascript" src="../../../../lib/jquery/basic/code.js"></script>
    <script type="text/javascript" src="../../../../lib/jquery/plugin/code.js"></script>
    <script type="text/javascript" src="../../../../lib/jquery/plugin/func/flot/excanvas.min.js"></script>
    <script type="text/javascript" src="../../../../lib/jquery/plugin/func/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="../../../../lib/jquery/plugin/func/flot/jquery.flot.pie.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <!-- 專屬 -->
    <link rel="stylesheet" type="text/css" href="../../css/def.css" media="all" />
    <link rel="stylesheet" type="text/css" href="../../inc/code.css" media="all" />
    <script type="text/javascript" src="../../inc/code.js"></script>
</Head>

<Body>

<!-- 資料列表 開始 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <!-- 在此設定寬高 -->
        <td width="80%" height="300px" align="center" valign="top">
            <!-- 內容 -->
            <table align="center" border="1" width="100%" class="table_style9" style="position:relative;margin-top:30px;">
                <tr align="center" class="bg_gray1" height="75px">
                    <td>
                        累計共發表了<span style="color:red;font-size:18pt;"> <?php echo $total;?> </span>篇文章
                    </td>
                </tr>
                <?php if(!empty($arry_build_time_info)):?>
                    <tr align="center" height="45px">
                        <td><div id="container" style="width:100%;float:left;margin-top:5px;"></div></td>
                    </tr>
                <?php else:?>
                    <tr align="center" height="45px">
                        <td>查無資料</td>
                    </tr>
                <?php endif;?>
            </table>
            <!-- 內容 -->
        </td>
    </tr>
</table>
<!-- 資料列表 結束 -->

<script type="text/javascript">
//-------------------------------------------------------
//頁面初始
//-------------------------------------------------------

    //---------------------------------------------------
    //參數
    //---------------------------------------------------

        var psize=<?php echo $psize;?>;
        var pinx =<?php echo $pinx;?>;

    //---------------------------------------------------
    //物件
    //---------------------------------------------------

        var arry_build_time=[];
        var arry_cno=[];
        <?php foreach($arry_build_time_info as $rs_build_time => $rs_cno):?>
            arry_build_time.push('<?php echo $rs_build_time;?>');
            arry_cno.push(<?php echo $rs_cno;?>);
        <?php endforeach;?>

    //---------------------------------------------------
    //ONLOAD
    //---------------------------------------------------

    ﻿$(function(){
console.log(arry_cno);
console.log(arry_build_time);
        $('#container').highcharts({
            chart: {type: 'line'},
            title: {text: ''},
            subtitle: {text: ''},
            xAxis: {
                categories: arry_build_time
            },
            yAxis: {
                title: {
                    text: '文章篇數'
                }
            },
            tooltip: {
                enabled: false,
                formatter: function() {
                    //return '<b>'+ this.series.name +'</b><br/>'+this.x +': '+ this.y +'°C';
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: '時間',
                data: arry_cno
            }]
        });
    });
</script>

</Body>
</Html>

<?php
//-------------------------------------------------------
//page_hrs 區塊 -- 結束
//-------------------------------------------------------
    $conn_user=NULL;
    $conn_mssr=NULL;
?>
<?php };?>