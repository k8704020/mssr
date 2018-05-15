    function qform(id,configs){
    //---------------------------------------------------
    //查詢表單列
    //---------------------------------------------------
    //id        容器id
    //configs   查詢總類設定
    //---------------------------------------------------
    //回傳值
    //---------------------------------------------------
    //本函式會傳回容器物件,你可以透過下列屬性,取得各個組成
    //元件.
    //
    //容器物件._createElement   _createElement()
    //容器物件.qform_form       o_qform_form
    //容器物件.qform_tbl        o_qform_tbl
    //容器物件.qform_tbl_ltd    o_qform_tbl_ltd
    //容器物件.qform_tbl_mtd    o_qform_tbl_mtd
    //容器物件.qform_tbl_rtd    o_qform_tbl_rtd
    //容器物件.qform_type       o_qform_type
    //容器物件.qform_sbtn       o_qform_sbtn
    //容器物件.qform_abtn       o_qform_abtn
    //容器物件.qform_rbtn       o_qform_rbtn
    //---------------------------------------------------

        //容器
        var o_qform =document.getElementById(id);
        var qform_id=o_qform.id;
        o_qform.className="qform_container";

        //表單
        var o_qform_form=document.createElement("FORM");
        o_qform_form.className="qform_form";
        o_qform_form.id=qform_id+"_form";

        //表格
        var o_qform_tbl =document.createElement("TABLE");
        o_qform_tbl.className="qform_tbl";
        o_qform_tbl.id=qform_id+"_tbl";

        //列
        var otr=o_qform_tbl.insertRow(-1);

        //欄位
        var o_qform_tbl_ltd=otr.insertCell(-1);
        o_qform_tbl_ltd.className="qform_tbl_ltd";
        o_qform_tbl_ltd.id=qform_id+"_tbl_ltd";

        var o_qform_tbl_mtd=otr.insertCell(-1);
        o_qform_tbl_mtd.className="qform_tbl_mtd";
        o_qform_tbl_mtd.id=qform_id+"_tbl_mtd";

        var o_qform_tbl_rtd=otr.insertCell(-1);
        o_qform_tbl_rtd.className="qform_tbl_rtd";
        o_qform_tbl_rtd.id=qform_id+"_tbl_rtd";

        //總類,下拉
        var o_qform_type=document.createElement("SELECT");
        o_qform_type.className="qform_type";
        o_qform_type.id=qform_id+"_type";
        for(var type in configs){
            var val=type;
            var txt=configs[type]['text'];
            var o_opt=document.createElement("OPTION");
            o_opt.value=val;
            o_opt.text =txt;
            o_qform_type.options.add(o_opt);
        }
        o_qform_tbl_ltd.appendChild(o_qform_type);

        //按鈕,查詢
        var o_qform_sbtn=document.createElement("INPUT");
        o_qform_sbtn.className="qform_sbtn";
        o_qform_sbtn.id   =qform_id+"_sbtn";
        o_qform_sbtn.type ="button";
        o_qform_sbtn.value="查詢";
        o_qform_tbl_rtd.appendChild(o_qform_sbtn);

        //按鈕,不分
        var o_qform_abtn=document.createElement("INPUT");
        o_qform_abtn.className="qform_abtn";
        o_qform_abtn.id   =qform_id+"_abtn";
        o_qform_abtn.type ="button";
        o_qform_abtn.value="不分";
        o_qform_tbl_rtd.appendChild(o_qform_abtn);

        //按鈕,重設
        var o_qform_rbtn=document.createElement("INPUT");
        o_qform_rbtn.className="qform_abtn";
        o_qform_rbtn.id   =qform_id+"_abtn";
        o_qform_rbtn.type ="button";
        o_qform_rbtn.value="重設";
        o_qform_tbl_rtd.appendChild(o_qform_rbtn);

        //附加表格到表單
        o_qform_form.appendChild(o_qform_tbl);

        //附加表單到容器
        o_qform.appendChild(o_qform_form);

        //初始化
        _createElement(o_qform_tbl_mtd,key=o_qform_type.options[0].value);

        //總類,下拉,onchange事件
        o_qform_type.onchange=function(){
            var opt=this.options[this.selectedIndex];
            var txt=opt.text;
            var val=opt.value;
            o_qform_tbl_mtd.innerHTML="";
            _createElement(o_qform_tbl_mtd,key=val);
        }

        //回傳
        o_qform._createElement=_createElement;
        o_qform.qform_form   =o_qform_form;
        o_qform.qform_tbl    =o_qform_tbl;
        o_qform.qform_tbl_ltd=o_qform_tbl_ltd;
        o_qform.qform_tbl_mtd=o_qform_tbl_mtd;
        o_qform.qform_tbl_rtd=o_qform_tbl_rtd;
        o_qform.qform_type   =o_qform_type;
        o_qform.qform_sbtn   =o_qform_sbtn;
        o_qform.qform_abtn   =o_qform_abtn;
        o_qform.qform_rbtn   =o_qform_rbtn;
        return o_qform;

        //-----------------------------------------------
        //子函式
        //-----------------------------------------------
        function _createElement(obj,key){

            var config=configs[key];

            switch(config['type'].toLowerCase()){
                case 'text':
                    obj.appendChild(_text(config));
                    break;
                case 'select':
                    obj.appendChild(_select(config));
                    break;
                case 'radio':
                    obj.appendChild(_radio(config));
                    break;
                case 'checkbox':
                    obj.appendChild(_checkbox(config));
                    break;
                case 'city_region':
                    //obj.appendChild(_city_region(config));
                    _city_region(oparent=obj,config);
                    break;
            }

            //_text()
            function _text(config){
                var type     =config['type']
                var id       =config['id']
                var name     =config['name']
                var vals     =config['vals']
                var className=config['className']

                var otxt=document.createElement("INPUT");

                otxt.type     ="TEXT";
                otxt.id       =id;
                otxt.name     =name;
                otxt.value    =vals;
                otxt.className=className;

                return otxt;
            }

            //_select()
            function _select(config){
                var type     =config['type']
                var id       =config['id']
                var name     =config['name']
                var vals     =config['vals']
                var className=config['className']

                var osel=document.createElement('SELECT');
                osel.id       =id;
                osel.name     =name;
                osel.className=className;

                for(var val in vals){
                    var txt=vals[val];

                    var opt=document.createElement("option");
                    opt.value=val;
                    opt.text =txt;
                    osel.options.add(opt);
                }

                return osel;
            }

            //_radio()
            function _radio(config){
                var type     =config['type']
                var name     =config['name']
                var vals     =config['vals']
                var className=config['className']

                var ocon=document.createElement("DIV");

                for(var val in vals){
                    var txt=vals[val];

                    var ord =_rd(name);
                    ord.name=name;
                    ord.type=type;
                    ord.value=val;
                    ord.className=className;
                    var otxt=document.createTextNode(txt);

                    ocon.appendChild(ord);
                    ocon.appendChild(otxt);
                }

                return ocon;

                function _rd(name){
                    try{
                    //FOR IE
                        return document.createElement('<input name='+name+'>');
                    }catch(e){
                        return document.createElement('input');
                    }
                }
            }

            //_checkbox()
            function _checkbox(config){
                var type     =config['type']
                var name     =config['name']
                var vals     =config['vals']
                var className=config['className']

                var ocon=document.createElement("DIV");

                for(var val in vals){
                    var txt=vals[val];

                    var och =_checkbox(name);
                    och.name=name;
                    och.type=type;
                    och.value=val;
                    och.className=className;
                    var otxt=document.createTextNode(txt);

                    ocon.appendChild(och);
                    ocon.appendChild(otxt);
                }

                return ocon;

                function _checkbox(name){
                    try{
                    //FOR IE
                        return document.createElement('<input name='+name+'>');
                    }catch(e){
                        return document.createElement('input');
                    }
                }
            }

            //_city_region()
            function _city_region(oparent,config){
                var type     =config['type']
                var name     =config['name']
                var vals     =config['vals']
                var use_type =config['use_type']
                var className=config['className']

                var ocon=document.createElement("DIV");

                //city
                var ocity=document.createElement('SELECT');
                var city_val=vals['city_val'];
                ocity.id       =name['city_name'];
                ocity.name     =name['city_name'];
                ocity.className=className;

                //region
                var oregion=document.createElement('SELECT');
                var region_val=vals['region_val'];
                oregion.id       =name['region_name'];
                oregion.name     =name['region_name'];
                oregion.className=className;

                //appendChild
                ocon.appendChild(ocity);
                ocon.appendChild(oregion);
                ocon.ocity  =ocity;
                ocon.oregion=oregion;
                oparent.appendChild(ocon);

                //binding
                city_region_sel(ocity.id,oregion.id,city_val,region_val,use_type);
                return ocon;
            }
        }

        function city_region_sel(city_id,region_id,city_val,region_val,use_type){
        //-----------------------------------------------
        //縣市鄉鎮下拉
        //-----------------------------------------------
        //city_id       縣市id
        //region_id     鄉鎮id
        //city_val      縣市值,預設 '請選擇'
        //region_val    鄉鎮值,預設 '請選擇'
        //use_type      用途: form|query,預設 'form'
        //              form  用在一般表單裡,即無'請選擇'選項
        //              query 用在查詢表單裡,即有'請選擇'選項
        //-----------------------------------------------

            //縣市鄉鎮陣列
            array_city={
                '請選擇':{
                            '請選擇':'0'
                        },
                '基隆市':{
                            '請選擇':'0',
                            '仁愛區':'200',
                            '信義區':'201',
                            '中正區':'202',
                            '中山區':'203',
                            '安樂區':'204',
                            '暖暖區':'205',
                            '七堵區':'206'
                        },
                '台北市':{
                            '請選擇':'0',
                            '中正區':'100',
                            '大同區':'103',
                            '中山區':'104',
                            '松山區':'105',
                            '大安區':'106',
                            '萬華區':'108',
                            '信義區':'110',
                            '士林區':'111',
                            '北投區':'112',
                            '內湖區':'114',
                            '南港區':'115',
                            '文山區':'116'
                        },
                '新北市':{
                            '請選擇':'0',
                            '萬里區':'207',
                            '金山區':'208',
                            '板橋區':'220',
                            '汐止區':'221',
                            '深坑區':'222',
                            '石碇區':'223',
                            '瑞芳區':'224',
                            '平溪區':'226',
                            '雙溪區':'227',
                            '貢寮區':'228',
                            '新店區':'231',
                            '坪林區':'232',
                            '烏來區':'233',
                            '永和區':'234',
                            '中和區':'235',
                            '土城區':'236',
                            '三峽區':'237',
                            '樹林區':'238',
                            '鶯歌區':'239',
                            '三重區':'241',
                            '新莊區':'242',
                            '泰山區':'243',
                            '林口區':'244',
                            '蘆洲區':'247',
                            '五股區':'248',
                            '八里區':'249',
                            '淡水區':'251',
                            '三芝區':'252',
                            '石門區':'253'
                        },
                '桃園縣':{
                            '請選擇':'0',
                            '中壢市':'320',
                            '平鎮市':'324',
                            '龍潭鄉':'325',
                            '楊梅市':'326',
                            '新屋鄉':'327',
                            '觀音鄉':'328',
                            '桃園市':'330',
                            '龜山鄉':'333',
                            '八德市':'334',
                            '大溪鎮':'335',
                            '復興鄉':'336',
                            '大園鄉':'337',
                            '蘆竹鄉':'338'
                        },
                '新竹市':{
                            '請選擇':'0',
                            '東區':'200',
                            '北區':'200',
                            '香山區':'200'
                        },
                '新竹縣':{
                            '請選擇':'0',
                            '竹北市':'302',
                            '湖口鄉':'303',
                            '新豐鄉':'304',
                            '新埔鎮':'305',
                            '關西鎮':'306',
                            '芎林鄉':'307',
                            '寶山鄉':'308',
                            '竹東鎮':'310',
                            '五峰鄉':'311',
                            '橫山鄉':'312',
                            '尖石鄉':'313',
                            '北埔鄉':'314',
                            '峨眉鄉':'315'
                        },
                '苗栗縣':{
                            '請選擇':'0',
                            '竹南鎮':'350',
                            '頭份鎮':'351',
                            '三灣鄉':'352',
                            '南庄鄉':'353',
                            '獅潭鄉':'354',
                            '後龍鎮':'356',
                            '通霄鎮':'357',
                            '苑裡鎮':'358',
                            '苗栗市':'360',
                            '造橋鄉':'361',
                            '頭屋鄉':'362',
                            '公館鄉':'363',
                            '大湖鄉':'364',
                            '泰安鄉':'365',
                            '銅鑼鄉':'366',
                            '三義鄉':'367',
                            '西湖鄉':'368',
                            '卓蘭鎮':'369'
                        },
                '台中市':{
                            '請選擇':'0',
                            '中區':'400',
                            '東區':'401',
                            '南區':'402',
                            '西區':'403',
                            '北區':'404',
                            '北屯區':'406',
                            '西屯區':'407',
                            '南屯區':'408',
                            '太平區':'411',
                            '大里區':'412',
                            '霧峰區':'413',
                            '烏日區':'414',
                            '豐原區':'420',
                            '后里區':'421',
                            '石岡區':'422',
                            '東勢區':'423',
                            '和平區':'424',
                            '新社區':'426',
                            '潭子區':'427',
                            '大雅區':'428',
                            '神岡區':'429',
                            '大肚區':'432',
                            '沙鹿區':'433',
                            '龍井區':'434',
                            '梧棲區':'435',
                            '清水區':'436',
                            '大甲區':'437',
                            '外埔區':'438',
                            '大安區':'439'
                        },
                '彰化縣':{
                            '請選擇':'0',
                            '彰化市':'500',
                            '芬園鄉':'502',
                            '花壇鄉':'503',
                            '秀水鄉':'504',
                            '鹿港鎮':'505',
                            '福興鄉':'506',
                            '線西鄉':'507',
                            '和美鎮':'508',
                            '伸港鄉':'509',
                            '員林鎮':'510',
                            '社頭鄉':'511',
                            '永靖鄉':'512',
                            '埔心鄉':'513',
                            '溪湖鎮':'514',
                            '大村鄉':'515',
                            '埔鹽鄉':'516',
                            '田中鎮':'520',
                            '北斗鎮':'521',
                            '田尾鄉':'522',
                            '埤頭鄉':'523',
                            '溪州鄉':'524',
                            '竹塘鄉':'525',
                            '二林鎮':'526',
                            '大城鄉':'527',
                            '芳苑鄉':'528',
                            '二水鄉':'530'
                        },
                '南投縣':{
                            '請選擇':'0',
                            '南投市':'540',
                            '中寮鄉':'541',
                            '草屯鎮':'542',
                            '國姓鄉':'544',
                            '埔里鎮':'545',
                            '仁愛鄉':'546',
                            '名間鄉':'551',
                            '集集鎮':'552',
                            '水里鄉':'553',
                            '魚池鄉':'555',
                            '信義鄉':'556',
                            '竹山鎮':'557',
                            '鹿谷鄉':'558'
                        },

                '雲林縣':{
                            '請選擇':'0',
                            '斗南鎮':'630',
                            '大埤鄉':'631',
                            '虎尾鎮':'632',
                            '土庫鎮':'633',
                            '褒忠鄉':'634',
                            '東勢鄉':'635',
                            '台西鄉':'636',
                            '崙背鄉':'637',
                            '麥寮鄉':'638',
                            '斗六市':'640',
                            '林內鄉':'643',
                            '古坑鄉':'646',
                            '莿桐鄉':'647',
                            '西螺鎮':'648',
                            '二崙鄉':'649',
                            '北港鎮':'651',
                            '水林鄉':'652',
                            '口湖鄉':'653',
                            '四湖鄉':'654',
                            '元長鄉':'655'
                        },
                '嘉義市':{
                            '請選擇':'0',
                            '東區':'600',
                            '西區':'600'
                        },
                '嘉義縣':{
                            '請選擇':'0',
                            '番路鄉':'602',
                            '梅山鄉':'603',
                            '竹崎鄉':'604',
                            '阿里山':'605',
                            '中埔鄉':'606',
                            '大埔鄉':'607',
                            '水上鄉':'608',
                            '鹿草鄉':'611',
                            '太保市':'612',
                            '朴子市':'613',
                            '東石鄉':'614',
                            '六腳鄉':'615',
                            '新港鄉':'616',
                            '民雄鄉':'621',
                            '大林鎮':'622',
                            '溪口鄉':'623',
                            '義竹鄉':'624',
                            '布袋鎮':'625'
                        },
                '台南市':{
                            '請選擇':'0',
                            '中西區':'700',
                            '東區':'701',
                            '南區':'702',
                            '北區':'704',
                            '安平區':'708',
                            '安南區':'709',
                            '永康區':'710',
                            '歸仁區':'711',
                            '新化區':'712',
                            '左鎮區':'713',
                            '玉井區':'714',
                            '楠西區':'715',
                            '南化區':'716',
                            '仁德區':'717',
                            '關廟區':'718',
                            '龍崎區':'719',
                            '官田區':'720',
                            '麻豆區':'721',
                            '佳里區':'722',
                            '西港區':'723',
                            '七股區':'724',
                            '將軍區':'725',
                            '學甲區':'726',
                            '北門區':'727',
                            '新營區':'730',
                            '後壁區':'731',
                            '白河區':'732',
                            '東山區':'733',
                            '六甲區':'734',
                            '下營區':'735',
                            '柳營區':'736',
                            '鹽水區':'737',
                            '善化區':'741',
                            '大內區':'742',
                            '山上區':'743',
                            '新市區':'744',
                            '安定區':'745'
                        },
                '高雄市':{
                            '請選擇':'0',
                            '新興區':'800',
                            '前金區':'801',
                            '苓雅區':'802',
                            '鹽埕區':'803',
                            '鼓山區':'804',
                            '旗津區':'805',
                            '前鎮區':'806',
                            '三民區':'807',
                            '楠梓區':'811',
                            '小港區':'812',
                            '左營區':'813',
                            '仁武區':'814',
                            '大社區':'815',
                            '岡山區':'820',
                            '路竹區':'821',
                            '阿蓮區':'822',
                            '田寮區':'823',
                            '燕巢區':'824',
                            '橋頭區':'825',
                            '梓官區':'826',
                            '彌陀區':'827',
                            '永安區':'828',
                            '湖內區':'829',
                            '鳳山區':'830',
                            '大寮區':'831',
                            '林園區':'832',
                            '鳥松區':'833',
                            '大樹區':'840',
                            '旗山區':'842',
                            '美濃區':'843',
                            '六龜區':'844',
                            '內門區':'845',
                            '杉林區':'846',
                            '甲仙區':'847',
                            '桃源區':'848',
                            '那瑪夏':'849',
                            '茂林區':'851',
                            '茄萣區':'852'
                        },
                '屏東縣':{
                            '請選擇':'0',
                            '屏東市':'900',
                            '三地門':'901',
                            '霧台鄉':'902',
                            '瑪家鄉':'903',
                            '九如鄉':'904',
                            '里港鄉':'905',
                            '高樹鄉':'906',
                            '鹽埔鄉':'907',
                            '長治鄉':'908',
                            '麟洛鄉':'909',
                            '竹田鄉':'911',
                            '內埔鄉':'912',
                            '萬丹鄉':'913',
                            '潮州鎮':'920',
                            '泰武鄉':'921',
                            '來義鄉':'922',
                            '萬巒鄉':'923',
                            '崁頂鄉':'924',
                            '新埤鄉':'925',
                            '南州鄉':'926',
                            '林邊鄉':'927',
                            '東港鎮':'928',
                            '琉球鄉':'929',
                            '佳冬鄉':'931',
                            '新園鄉':'932',
                            '枋寮鄉':'940',
                            '枋山鄉':'941',
                            '春日鄉':'942',
                            '獅子鄉':'943',
                            '車城鄉':'944',
                            '牡丹鄉':'945',
                            '恆春鎮':'946',
                            '滿州鄉':'947'
                        },
                '台東縣':{
                            '請選擇':'0',
                            '台東市':'950',
                            '綠島鄉':'951',
                            '蘭嶼鄉':'952',
                            '延平鄉':'953',
                            '卑南鄉':'954',
                            '鹿野鄉':'955',
                            '關山鎮':'956',
                            '海端鄉':'957',
                            '池上鄉':'958',
                            '東河鄉':'959',
                            '成功鎮':'961',
                            '長濱鄉':'962',
                            '太麻里':'963',
                            '金峰鄉':'964',
                            '大武鄉':'965',
                            '達仁鄉':'966'
                        },
                '花蓮縣':{
                            '請選擇':'0',
                            '花蓮市':'970',
                            '新城鄉':'971',
                            '秀林鄉':'972',
                            '吉安鄉':'973',
                            '壽豐鄉':'974',
                            '鳳林鎮':'975',
                            '光復鄉':'976',
                            '豐濱鄉':'977',
                            '瑞穗鄉':'978',
                            '萬榮鄉':'979',
                            '玉里鎮':'981',
                            '卓溪鄉':'982',
                            '富里鄉':'983'
                        },
                '宜蘭縣':{
                            '請選擇':'0',
                            '宜蘭市':'260',
                            '頭城鎮':'261',
                            '礁溪鄉':'262',
                            '壯圍鄉':'263',
                            '員山鄉':'264',
                            '羅東鎮':'265',
                            '三星鄉':'266',
                            '大同鄉':'267',
                            '五結鄉':'268',
                            '冬山鄉':'269',
                            '蘇澳鎮':'270',
                            '南澳鄉':'272'
                        },
                '澎湖縣':{
                            '請選擇':'0',
                            '馬公市':'880',
                            '西嶼鄉':'881',
                            '望安鄉':'882',
                            '七美鄉':'883',
                            '白沙鄉':'884',
                            '湖西鄉':'885'
                        },
                '金門縣':{
                            '請選擇':'0',
                            '金沙鎮':'890',
                            '金湖鎮':'891',
                            '金寧鄉':'892',
                            '金城鎮':'893',
                            '烈嶼鄉':'894',
                            '烏坵鄉':'896'
                        },
                '連江縣':{
                            '請選擇':'0',
                            '南竿鄉':'209',
                            '北竿鄉':'210',
                            '莒光鄉':'211',
                            '東引鄉':'212'
                        }
            };

            //預設值
            city_def  ='桃園縣';
            region_def='桃園市';

            //參數檢驗
            if((city_id==undefined)||(trim(city_id)=='')){
                return false;
            }
            if((region_id==undefined)||(trim(region_id)=='')){
                return false;
            }
            if((use_type==undefined)||(trim(use_type)=='')){
                use_type='form';
            }
            if((city_val==undefined)||(trim(city_val)==''||(trim(city_val)=='請選擇'))){
                if(use_type.toLowerCase()=='form'){
                    city_val=city_def;
                }else{
                    city_val='請選擇';
                }
            }
            if((region_val==undefined)||(trim(region_val)==''||(trim(region_val)=='請選擇'))){
                if(use_type.toLowerCase()=='form'){
                    region_val=region_def;
                }else{
                    region_val='請選擇';
                }
            }

            //縣市鄉鎮下拉
            var ocity   =document.getElementById(city_id);
            var oregion =document.getElementById(region_id);
            if((!ocity)||(!oregion)){
                return false;
            }

            //縣市
            for(var key in array_city){
                if(use_type.toLowerCase()=='form'){
                    if(key.toLowerCase()=='請選擇'){
                        continue;
                    }
                }
                var o_opt=document.createElement('OPTION');
                o_opt.value=key;
                o_opt.text =key;
                ocity.options.add(o_opt);

                if(key.toLowerCase()==city_val){
                   o_opt.selected=true;
                }
            }

            //鄉鎮
            for(var key in array_city[city_val]){
                if(use_type.toLowerCase()=='form'){
                    if(key.toLowerCase()=='請選擇'){
                        continue;
                    }
                }

                var o_opt=document.createElement('OPTION');
                o_opt.value=key;
                o_opt.text =key;
                oregion.options.add(o_opt);

                if(key.toLowerCase()==region_val){
                   o_opt.selected=true;
                }
            }

            //連動處理
            ocity.setAttribute('region_id',region_id);
            ocity.setAttribute('use_type',use_type);
            ocity.onchange=function(){

                //屬性
                var region_id=this.getAttribute('region_id');
                var use_type =this.getAttribute('use_type');

                //取回鄉鎮下拉
                var oregion=document.getElementById(region_id);

                //取回縣市名稱
                var city=this.value;

                //取回對應鄉鎮區名稱
                var regions=[];

                for(var key in array_city[city]){
                    if(use_type.toLowerCase()=='form'){
                        if(key.toLowerCase()=='請選擇'){
                            continue;
                        }
                    }
                    var zip=array_city[city][key];
                    regions.push(key);
                }

                //清掉鄉鎮下拉既有選項
                oregion.innerHTML='';

                //回填新的項目
                for(var i=0;i<regions.length;i++){
                    var o_opt=document.createElement('OPTION');
                    o_opt.value=regions[i];
                    o_opt.text =regions[i];
                    oregion.options.add(o_opt);
                }
            }

            function trim(str){
            //去字串前後空白
                str=str.toString();
                str=str.replace(/^\s+/,'');
                str=str.replace(/\s+$/,'');
                return str;
            }
        }
    }