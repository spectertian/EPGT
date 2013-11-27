<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Province
 *
 * @author ly
 */
class Province {
    static  protected  $allProvince = array(
        "北京"=>"beijing",
        "重庆"=>"chongqing",
        "上海"=>"shanghai",
        "天津"=>"tianjin",
        "安徽"=>"anhui",
        '广东'=>"guangdong",
        "广西"=>"guangxi",
        "黑龙江"=>"heilongjiang",
        "吉林"=>"jilin",
        "辽宁"=>"liaoning",
        "江苏"=>"jiangsu",
        "浙江"=>"zhejiang",
        "陕西"=>"shaanxi",
        "湖北"=>"hubei",
        "湖南"=>"hunan",
        "甘肃"=>"gansu",
        "四川"=>"sichuan",
        "山东"=>"shandong",
        "福建"=>"fujian",
        "河南"=>"henan",
        "云南"=>"yunnan",
        "河北"=>"hebei",
        "江西"=>"jiangxi",
        "山西"=>"shanxi",
        "贵州"=>"guizhou",
        "内蒙古"=>"neimenggu",
        "宁夏"=>"ningxia",
        "青海"=>"qinghai",
        "新疆"=>"xinjiang",
        "海南"=>"hainan",
        "西藏"=>"xizang",
    );


    static  protected  $ProvinceAll = array(
        '67' =>'北京市',
        '68' =>'天津市',
        '69' =>'上海市',                                        
        '70' =>'重庆市',
        '37' =>'湖北',
        '38' =>'广东',
        '39' =>'江西',
        '40' =>'安徽',
        '41' =>'福建',
        '42' =>'广西',
        '43' =>'云南',
        '44' =>'四川',
        '45' =>'贵州',
        '46' =>'湖南',
        '47' =>'浙江',
        '48' =>'江苏',
        '49' =>'河南',
        '50' =>'河北',
        '51' =>'山东',
        '52' =>'山西',
        '53' =>'陕西',
        '54' =>'甘肃',
        '55' =>'青海',
        '56' =>'宁夏',
        '57' =>'内蒙古自治区',
        '58' =>'辽宁',
        '59' =>'吉林',
        '60' =>'黑龙江',
        '61' =>'新疆自治区',
        '62' =>'西藏自治区',
        '63' =>'海南',
        '64' =>'澳门',
        '65' =>'香港',
        '66' =>'台湾'
    );    
    static  protected  $CityAll = array(


    '67' => Array
        (
		'446' => '东城区',
    	'447' => '崇文区',
    	'448' => '丰台区',
    	'449' => '朝阳区',
    	'450' => '门头沟区',
    	'451' => '西城区',
    	'452' => '海淀区',
    	'453' => '宣武区',
    	'454' => '房山区',
    	'455' => '石景山区',
    	'456' => '昌平县',
    	'457' => '密云县',
    	'458' => '通县',
    	'459' => '延庆县',
    	'460' => '顺义县',
    	'461' => '大兴县',
    	'462' => '怀柔县',
    	'463' => '平谷县',
	
		),
	
    '68' => Array
        (
		'464' => '和平区',
    	'465' => '红桥区',
    	'466' => '西郊区',
    	'467' => '河东区',
    	'468' => '塘沽区',
    	'469' => '北郊区',
    	'470' => '河西区',
    	'471' => '汉沽区',
    	'472' => '大港区',
    	'473' => '河北区',
    	'474' => '东郊区',
    	'475' => '南开区',
    	'476' => '南郊区',
    	'477' => '蓟县',
    	'478' => '武清县',
    	'479' => '宁河县',
    	'480' => '宝坻县',
    	'481' => '静海县',
	
		),
	
    '69' => Array
        (
		'53' => '黄埔区',
    	'482' => '长宁区',
    	'483' => '杨浦区',
    	'484' => '南市区',
    	'485' => '闸北区',
    	'486' => '普陀区',
    	'487' => '卢湾区',
    	'488' => '静安区',
    	'489' => '宝山区',
    	'490' => '徐汇区',
    	'491' => '虹口区',
    	'492' => '闵行区',
    	'493' => '上海县',
    	'494' => '南汇区',
    	'495' => '青浦县',
    	'496' => '嘉定县',
    	'497' => '奉贤县(南桥镇)',
    	'498' => '崇明县',
    	'499' => '松江县',
    	'500' => '川沙县',
    	'501' => '金山县',
	
		),
	
    '70' => Array
        (
		'502' => '万州区',
    	'503' => '涪陵区',
    	'504' => '渝中区',
    	'505' => '大渡口区',
    	'506' => '江北区',
    	'507' => '沙坪坝区',
    	'508' => '九龙坡区',
    	'509' => '南岸区',
    	'510' => '北碚区',
    	'511' => '万盛区',
    	'512' => '双桥区',
    	'513' => '渝北区',
    	'514' => '巴南区',
    	'515' => '黔江区',
    	'516' => '长寿区',
    	'517' => '江津市',
    	'518' => '合川市',
    	'519' => '永川市',
    	'520' => '南川市',
    	'521' => '綦江县',
    	'522' => '潼南县',
    	'523' => '铜梁县',
    	'524' => '大足县',
    	'525' => '荣昌县',
    	'526' => '璧山县',
    	'527' => '梁平县',
    	'528' => '城口县',
    	'529' => '丰都县',
    	'530' => '垫江县',
    	'531' => '武隆县',
    	'532' => '忠县',
    	'533' => '开县',
    	'534' => '云阳县',
    	'535' => '奉节县',
    	'536' => '巫山县',
    	'537' => '巫溪县',
    	'538' => '石柱自治县',
    	'539' => '秀山自治县',
    	'540' => '酉阳自治县',
    	'541' => '彭水自治县',
	
		),
	
    '37' => Array
        (
		'1' => '黄石',
    	'2' => '宜昌',
    	'13' => '武汉市',
    	'55' => '襄樊市',
    	'56' => '十堰市',
    	'57' => '沙市市',
    	'58' => '荆门市',
    	'59' => '鄂州市',
    	'60' => '随州市',
    	'61' => '老河口市',
    	'62' => '枣阳市',
    	'63' => '孝感市',
    	'64' => '应城市',
    	'65' => '安陆市',
    	'66' => '广水市',
    	'67' => '麻城市',
    	'68' => '武穴市',
    	'69' => '黄州市',
    	'70' => '咸宁市',
    	'71' => '蒲圻市',
    	'72' => '仙桃市',
    	'73' => '石首市',
    	'74' => '天门市',
    	'75' => '洪湖市',
    	'76' => '潜江市',
    	'77' => '荆州',
    	'78' => '枝城市',
    	'79' => '当阳市',
    	'80' => '丹江口市',
    	'82' => '郧阳',
    	'83' => '恩施市',
    	'84' => '利川市',
    	'85' => '鄂西土家族苗族自治州',
	
		),
	
    '38' => Array
        (
		'3' => '深圳',
    	'4' => '广州',
    	'5' => '东莞',
    	'6' => '惠州',
    	'12' => '珠海',
    	'86' => '汕头市',
    	'87' => '韶关市',
    	'88' => '河源市',
    	'89' => '梅州市',
    	'90' => '惠州市',
    	'91' => '汕尾市',
    	'92' => '中山市',
    	'93' => '江门市',
    	'94' => '佛山市',
    	'95' => '阳江市',
    	'96' => '湛江市',
    	'98' => '茂名市',
    	'99' => '肇庆市',
    	'100' => '清远市',
	
		),
	
    '39' => Array
        (
		'7' => '九江',
    	'8' => '南昌',
    	'101' => '景德镇市',
    	'10' => '上饶',
    	'102' => '萍乡市',
    	'103' => '新余市',
    	'104' => '鹰潭市',
    	'105' => '宜春市',
    	'106' => '抚州市',
    	'107' => '吉安市',
    	'108' => '赣州市',
    	'109' => '高安市',
    	'110' => '樟树市',
	
		),
	
    '40' => Array
        (
		'111' => '合肥市',
    	'112' => '淮南市',
    	'113' => '淮北市',
    	'114' => '芜湖市',
    	'115' => '铜陵市',
    	'116' => '蚌埠市',
    	'117' => '马鞍山市',
    	'118' => '安庆市',
    	'119' => '黄山市',
    	'120' => '宿州市',
    	'121' => '滁州市',
    	'122' => '巢湖市',
    	'123' => '宣州市',
    	'124' => '贵池市',
    	'125' => '东至市',
    	'126' => '六安市',
    	'127' => '阜阳市',
    	'128' => '毫州市',
    	'129' => '界首市',
	
		),
	
    '41' => Array
        (
		'14' => '福州市',
    	'15' => '厦门',
    	'130' => '三明市',
    	'131' => '莆田市',
    	'132' => '泉州市',
    	'133' => '漳州市',
    	'135' => '永安市',
    	'136' => '石狮市',
    	'137' => '福清市',
    	'138' => '南平市',
    	'139' => '邵武市',
    	'140' => '武夷山市',
    	'142' => '宁德市',
    	'143' => '福安市',
    	'144' => '龙岩市',
    	'145' => '漳平市',
	
		),
	
    '42' => Array
        (
		'16' => '南宁市',
    	'17' => '桂林',
    	'146' => '柳州市',
    	'147' => '梧州市',
    	'148' => '北海市',
    	'149' => '凭祥市',
    	'150' => '合山市',
    	'151' => '玉林市',
    	'152' => '贵港市',
    	'153' => '百色市',
    	'154' => '河池市',
	
		),
	
    '43' => Array
        (
		'18' => '昆明市',
    	'19' => '丽江',
    	'155' => '东川市',
    	'156' => '昭通市',
    	'157' => '曲靖市',
    	'158' => '玉溪市',
    	'159' => '思茅',
    	'160' => '临沧',
    	'161' => '保山市',
    	'162' => '个旧市',
    	'163' => '开远市',
    	'164' => '西双版纳',
    	'165' => '楚雄市',
    	'166' => '大理市',
    	'167' => '畹町市',
    	'168' => '德宏',
    	'169' => '怒江',
    	'170' => '迪庆',
	
		),
	
    '44' => Array
        (
		'20' => '成都市',
    	'21' => '绵羊市',
    	'171' => '自贡市',
    	'172' => '攀枝花市',
    	'173' => '泸州市',
    	'174' => '德阳市',
    	'175' => '广元市',
    	'176' => '遂宁市',
    	'177' => '内江市',
    	'178' => '乐山市',
    	'179' => '广汉市',
    	'180' => '江油市',
    	'181' => '都江堰市',
    	'182' => '峨眉山市',
    	'183' => '万县市',
    	'184' => '涪陵市',
    	'185' => '黔江',
    	'186' => '宜宾市',
    	'187' => '南充市',
    	'188' => '华蓥市',
    	'189' => '达县市',
    	'190' => '雅安市',
    	'191' => '阿坝自治州',
    	'192' => '甘孜',
    	'193' => '西昌市',
    	'194' => '凉山',
	
		),
	
    '45' => Array
        (
		'22' => '贵阳市',
    	'23' => '遵义',
    	'195' => '六盘水市',
    	'196' => '赤水市',
    	'197' => '铜仁市',
    	'198' => '德江市',
    	'200' => '毕节',
    	'201' => '安顺市',
    	'202' => '兴义市',
    	'203' => '凯里市',
    	'204' => '都匀市',
    	'205' => '贵定市',
    	
		),
	
    '46' => Array
        (
		'24' => '长沙市',
    	'25' => '株洲',
    	'26' => '湘潭',
    	'27' => '衡阳',
    	'28' => '怀化',
    	'206' => '岳阳市',
    	'207' => '常德市',
    	'208' => '大庸市',
    	'209' => '醴陵市',
    	'210' => '湘乡市',
    	'211' => '来阳市',
    	'212' => '汩罗市',
    	'213' => '津市市',
    	'214' => '韶山市',
    	'215' => '郴州市',
    	'216' => '资兴市',
    	'218' => '永州市',
    	'219' => '冷水滩市',
    	'221' => '娄底市',
    	'222' => '冷水江市',
    	'223' => '涟源市',
    	'224' => '洪江市',
    	'225' => '益阳市',
    	'226' => '沅江市',
    	'227' => '吉首市',
	
		),
	
    '47' => Array
        (
		'29' => '杭州市',
    	'30' => '宁波',
    	'31' => '温州',
    	'32' => '绍兴',
    	'228' => '嘉兴市',
    	'229' => '金华市',
    	'230' => '舟山市',
    	'231' => '余姚市',
    	'232' => '海宁市',
    	'233' => '兰溪市',
    	'234' => '瑞安市',
    	'235' => '萧山市',
    	'236' => '江山市',
    	'238' => '东阳市',
    	'239' => '义乌市',
    	'240' => '慈溪市',
    	'241' => '奉化市',
    	'242' => '诸暨市',
    	'243' => '椒江市',
    	'244' => '临海市',
    	'245' => '黄岩市',
    	'247' => '丽水市',
    	'248' => '龙泉市',
	
		),
	
    '48' => Array
        (
		'33' => '南京市',
    	'34' => '连云港',
    	'35' => '常州',
    	'36' => '镇江',
    	'249' => '徐州市',
    	'250' => '淮阴市',
    	'251' => '盐城市',
    	'252' => '扬州市',
    	'253' => '无锡市',
    	'254' => '苏州市',
    	'255' => '泰州市',
    	'256' => '仪征市',
    	'257' => '常熟市',
    	'258' => '张家港市',
    	'259' => '江阴市',
    	'260' => '宿迁市',
    	'261' => '丹阳市',
    	'262' => '东台市',
    	'263' => '兴化市',
    	'264' => '淮安市',
    	'265' => '宜兴市',
    	'266' => '昆山市',
    	'267' => '启东市',
    	'268' => '新沂市',
    	'269' => '溧阳市',
	
		),
	
    '49' => Array
        (
		'37' => '郑州市',
    	'28' => '开封',
    	'39' => '信阳',
    	'270' => '洛阳',
    	'271' => '许昌',
    	'272' => '潢川',
    	'273' => '三门峡',
    	'274' => '商丘',
    	'275' => '安阳',
    	'276' => '驻马店',
    	'277' => '焦作',
    	'278' => '鹤壁',
    	'279' => '周口',
    	'280' => '南阳',
    	'281' => '濮阳',
    	'282' => '漯河',
    	'283' => '新乡',
    	'284' => '平顶山',
	
		),
	
    '50' => Array
        (
		'299' => '沧州',
    	'300' => '石家庄',
    	'301' => '衡水',
    	'302' => '保定',
    	'303' => '秦皇岛',
    	'304' => '张家口',
    	'305' => '唐山',
    	'306' => '承德',
    	'307' => '廊坊',
    	'308' => '邯郸',
    	'309' => '邢台',
	
		),
	
    '51' => Array
        (
		'373' => '菏泽',
    	'374' => '淄博',
    	'375' => '日照',
    	'376' => '临沂',
    	'377' => '德州',
    	'378' => '潍坊',
    	'379' => '烟台',
    	'380' => '莱芜',
    	'381' => '威海',
    	'382' => '济宁',
    	'383' => '泰安',
    	'384' => '滨州',
    	'385' => '枣庄',
    	'386' => '济南',
    	'387' => '青岛',
    	'388' => '东营',
    	'389' => '聊城',
	
		),
	
    '52' => Array
        (
		'390' => '大同',
    	'391' => '晋城',
    	'392' => '忻州',
    	'393' => '吕梁',
    	'394' => '长治',
    	'395' => '太原',
    	'396' => '临汾',
    	'397' => '阳泉',
    	'398' => '朔州',
    	'399' => '运城',
    	'400' => '晋中',
	
		),
	
    '53' => Array
        (
		'401' => '榆林',
    	'402' => '商洛',
    	'403' => '渭南',
    	'404' => '延安',
    	'405' => '汉中',
    	'406' => '咸阳',
    	'407' => '宝鸡',
    	'408' => '西安',
    	'409' => '安康',
    	'410' => '铜川',
	
		),
	
    '54' => Array
        (
		'285' => '陇南',
    	'286' => '金昌',
    	'287' => '甘南藏族自治州',
    	'288' => '庆阳',
    	'289' => '定西',
    	'290' => '天水',
    	'291' => '兰州',
    	'292' => '白银',
    	'293' => '平凉',
    	'294' => '嘉峪关',
    	'295' => '张掖',
    	'296' => '临夏回族自治州',
    	'297' => '武威',
    	'298' => '酒泉',
	
		),
	
    '55' => Array
        (
		'364' => '海西蒙古族藏族自治州',
    	'365' => '黄南藏族自治州',
    	'366' => '玉树藏族自治州',
    	'367' => '海南藏族自治州',
    	'368' => '西宁',
    	'369' => '海北藏族自治州',
    	'370' => '海东',
    	'371' => '格尔木',
    	'372' => '果洛藏族自治州',
	
		),
	
    '56' => Array
        (
		'360' => '固原',
    	'361' => '银川',
    	'362' => '石嘴山',
    	'363' => '吴忠',
	
		),
	
    '57' => Array
        (
		'348' => '通辽',
    	'349' => '乌兰察布盟',
    	'350' => '赤峰',
    	'351' => '呼伦贝尔',
    	'352' => '呼和浩特',
    	'353' => '包头',
    	'354' => '锡林郭勒盟',
    	'355' => '乌海',
    	'356' => '阿拉善盟',
    	'357' => '鄂尔多斯',
    	'358' => '巴彦淖尔盟',
    	'359' => '兴安盟',
	
		),
	
    '58' => Array
        (
		'334' => '大连',
    	'335' => '鞍山',
    	'336' => '铁岭',
    	'337' => '抚顺',
    	'338' => '葫芦岛',
    	'339' => '辽阳',
    	'340' => '锦州',
    	'341' => '沈阳',
    	'342' => '营口',
    	'343' => '盘锦',
    	'344' => '阜新',
    	'345' => '朝阳',
    	'346' => '本溪',
    	'347' => '丹东',
	
		),
	
    '59' => Array
        (
		'323' => '梅河口',
    	'324' => '松原',
    	'325' => '吉林',
    	'326' => '四平',
    	'327' => '通化',
    	'328' => '白山',
    	'329' => '延边朝鲜族自治州',
    	'330' => '辽源',
    	'331' => '白城',
    	'332' => '珲春',
    	'333' => '长春',
	
		),
	
    '60' => Array
        (
		'310' => '七台河',
    	'311' => '牡丹江',
    	'312' => '鹤岗',
    	'313' => '伊春',
    	'314' => '大兴安岭',
    	'315' => '哈尔滨',
    	'316' => '鸡西',
    	'317' => '大庆',
    	'318' => '佳木斯',
    	'319' => '双鸭山',
    	'320' => '绥化',
    	'321' => '齐齐哈尔',
    	'322' => '黑河',
	
		),
	
    '61' => Array
        (
		'411' => '乌鲁木齐市',
    	'412' => '克拉玛依市',
    	'413' => '石河子市',
    	'414' => '吐鲁番市',
    	'415' => '哈密市',
    	'416' => '和田市',
    	'417' => '阿克苏市',
    	'418' => '咯什市',
    	'419' => '阿图什市',
    	'420' => '库尔勒市',
    	'421' => '昌吉市',
    	'422' => '博乐市',
    	'423' => '奎屯市',
    	'424' => '伊宁市',
    	'425' => '塔城市',
    	'426' => '阿勒泰市',
	
		),
	
    '62' => Array
        (
		'427' => '拉萨市',
    	'428' => '那曲区',
    	'429' => '昌都县',
    	'430' => '乃东县',
    	'431' => '日喀则市',
    	'432' => '噶尔县',
    	'433' => '林芝',
	
		),
	
    '63' => Array
        (
		'48' => '海口',
    	'49' => '琼洲',
    	'50' => '三亚',
    	'434' => '海口市',
    	'435' => '三亚市',
    	'436' => '通什市',
    	'437' => '琼山县',
    	'438' => '文昌县',
    	'439' => '琼海县',
    	'440' => '万宁县',
    	'441' => '定安县',
    	'442' => '屯昌县',
    	'443' => '澄迈县',
    	'444' => '临高县',
    	'445' => '儋县',
	
		),
	
    '64' => Array
        (
		'554' => '澳门',
	
		),
	
    '65' => Array
        (
		'553' => '香港',
	
		),
	
    '66' => Array
        (
		'542' => '台北市',
    	'543' => '大同区',
    	'544' => '中山区',
    	'545' => '士林区',
    	'546' => '松山区',
    	'547' => '万华区',
    	'548' => '信义区',
    	'549' => '中正区',
    	'550' => '大安区',
    	'551' => '高雄',
    	'552' => '台南',
	
		),

    );     
    static  public function getProvince(){
        return self::$allProvince;
    }
    static  public function getProvinceAll(){
        return self::$ProvinceAll;
    } 
    static  public function getCityAll(){
        return self::$CityAll;
    }        
}
?>
