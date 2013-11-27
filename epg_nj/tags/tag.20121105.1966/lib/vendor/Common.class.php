<?php

class Common
{
    static function get_url_content($url) {   
        if(function_exists('file_get_contents')) {   
            $ctx = stream_context_create(array(      
                        'http' => array('timeout' => 1)      
                   ));               
            $file_contents = @file_get_contents($url,0,$ctx);   
        } else {   
            $ch = curl_init();   
            $timeout = 5;   
            curl_setopt ($ch, CURLOPT_URL, $url);   
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);   
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);   
            $file_contents = curl_exec($ch);   
            curl_close($ch);   
        }   
        return $file_contents;   
    }   
    
    static function get_url_info($url){
    	$c = curl_init();
    	curl_setopt($c, CURLOPT_URL, $url);
    	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    	$data = curl_exec($c);
    	curl_close($c);
    	return $data;
    }
    
	static function post_user_json($post_data) 
	{
		if(is_array($post_data)){
			$post_data = json_encode($post_data);
		}
		$opts = array('http'=>array('method'=>"POST",
								'header'=>"Accept-language: en\r\n",
								'content'=>$post_data));	

		$context = stream_context_create($opts);
		$bkjson = @file_get_contents(sfConfig::get('app_huan_uc_url'), false, $context);
		if($bkjson)
			return $bkjson;
		else
			return false;
	}

	static function post_json($url,$post_data) 
	{
		if(is_array($post_data)){
			$post_data = json_encode($post_data);
		}
		$opts = array('http'=>array('method'=>"POST",
								'header'=>"Accept-language: en\r\n",
								'content'=>$post_data));	

		$context = stream_context_create($opts);
		$bkjson = @file_get_contents($url, false, $context);
		if($bkjson)
			return $bkjson;
		else
			return false;
	}
    static function get_area_by_ip($ip)
    {
        $area   = self::convertip($ip);
        $area   = mb_convert_encoding($area, 'UTF-8', 'gbk');
        $city   =  self::city_math($area);
        if($city)
        {
            return array('code' => 1, 'city' => $city);;
        }

        if(preg_match('/^(.+?)省/', $area, $city))
        {
            $city   = self::key_exist(1, $city);
        }
        elseif(preg_match('/^(.+?)市/', $area, $city))
        {
            $city   = self::key_exist(1, $city);
            $return = array('code' => 1, 'city' => $city);
        }
        elseif(preg_match('/^香港/', $area, $city))
        {
            $return = array('code' => 1, 'city' => '香港');
        }
        elseif(preg_match('/^澳门/', $area, $city))
        {
            $return = array('code' => 1, 'city' => '澳门');
        }
        else
        {
            $return = array('code' => 0, 'city' => '未知');
        }
        return $return;
    }

    static function city_math($area)
    {
        $return = false;
        $city   = '广东,江苏,山东,四川,台湾,浙江,辽宁,河南,湖北,福建,河北,湖南,';
        $city  .= '上海,香港,北京,黑龙江,天津,重庆,江西,山西,安徽,陕西,海南,云南,';
        $city  .= '甘肃,内蒙古,贵州,新疆,西藏,青海,广西,澳门,宁夏,吉林,香港,澳门';
        $city   = explode(',', $city);
        foreach ($city as $key => $value)
        {
            if(preg_match('/^'.$value.'/', $area))
            {
                $return =  $value;
                break;
            }
        }
        return $return;
    }


    static function convertip($ip)
    {
        $ip1num  = '';
        $ip2num  = '';
        $ipAddr2 = '';
        $ipAddr1 = '';
        $dat_path = dirname(dirname(dirname(__FILE__))) . '/data/QQWry.Dat';
        if (!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
            return 'IP Address Error';
        }
        if (!$fd = @fopen($dat_path, 'rb')) {
            return 'IP date file not exists or access denied';
        }
        $ip = explode('.', $ip);
        $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
        $DataBegin = fread($fd, 4);
        $DataEnd = fread($fd, 4);
        $ipbegin = implode('', unpack('L', $DataBegin));
        if ($ipbegin < 0)
            $ipbegin += pow(2, 32);
        $ipend = implode('', unpack('L', $DataEnd));
        if ($ipend < 0)
            $ipend += pow(2, 32);
        $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
        $BeginNum = 0;
        $EndNum = $ipAllNum;
        while ($ip1num > $ipNum || $ip2num < $ipNum) {
            $Middle = intval(($EndNum + $BeginNum) / 2);
            fseek($fd, $ipbegin + 7 * $Middle);
            $ipData1 = fread($fd, 4);
            if (strlen($ipData1) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip1num = implode('', unpack('L', $ipData1));
            if ($ip1num < 0)
                $ip1num += pow(2, 32);

            if ($ip1num > $ipNum) {
                $EndNum = $Middle;
                continue;
            }
            $DataSeek = fread($fd, 3);
            if (strlen($DataSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
            fseek($fd, $DataSeek);
            $ipData2 = fread($fd, 4);
            if (strlen($ipData2) < 4) {
                fclose($fd);
                return 'System Error';
            }
            $ip2num = implode('', unpack('L', $ipData2));
            if ($ip2num < 0)
                $ip2num += pow(2, 32);
            if ($ip2num < $ipNum) {
                if ($Middle == $BeginNum) {
                    fclose($fd);
                    return 'Unknown';
                }
                $BeginNum = $Middle;
            }
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(1)) {
            $ipSeek = fread($fd, 3);
            if (strlen($ipSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
            fseek($fd, $ipSeek);
            $ipFlag = fread($fd, 1);
        }
        if ($ipFlag == chr(2)) {
            $AddrSeek = fread($fd, 3);
            if (strlen($AddrSeek) < 3) {
                fclose($fd);
                return 'System Error';
            }
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr2 .= $char;
            $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
            fseek($fd, $AddrSeek);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
        } else {
            fseek($fd, -1, SEEK_CUR);
            while (($char = fread($fd, 1)) != chr(0))
                $ipAddr1 .= $char;
            $ipFlag = fread($fd, 1);
            if ($ipFlag == chr(2)) {
                $AddrSeek2 = fread($fd, 3);
                if (strlen($AddrSeek2) < 3) {
                    fclose($fd);
                    return 'System Error';
                }
                $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
                fseek($fd, $AddrSeek2);
            } else {
                fseek($fd, -1, SEEK_CUR);
            }
            while (($char = fread($fd, 1)) != chr(0)) {
                $ipAddr2 .= $char;
            }
        }
        fclose($fd);
        if (preg_match('/http/i', $ipAddr2)) {
            $ipAddr2 = '';
        }
        $ipaddr = "$ipAddr1";
        $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);
        $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
        $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
        if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
            $ipaddr = 'Unknown';
        }
        return $ipaddr;
    }
    
     static  function get_remote_ip() {
        $onlineip = '127.0.0.1';
        if(getenv('HTTP_CLIENT_IP')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR')) {
            $onlineip = getenv('REMOTE_ADDR');
        } else {
            $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        }
        if ($onlineip == '::1'){
            $onlineip   = '127.0.0.1';
        }
        return $onlineip;
     }

     static function key_exist($index, $city) {
         if(array_key_exists($index, $city))
         {
             return $city[$index];
         }
         return '未知';
     }

     static function ObjectArraySort($a, $b) {
         $a_tv_station_id   = $a->getChannel()->getTvStationId();
         $b_tv_station_id   = $b->getChannel()->getTvStationId();
         $a_type           = $a->getChannel()->getType();
         $b_type           = $b->getChannel()->getType();

        //央视
         if($a_tv_station_id ==1) {
             if ($b_tv_station_id ==1) {
                 return 0;
             }

             if ($b_tv_station_id !=1) {
                 return -1;
             }

             return -1;
         }
         
         //卫视
         if($a_type == 'tv') {
             if ($b_tv_station_id == 1) {
                 return 1;
             }
             if($b_type == 'tv') {
                 return 0;
             }
             return -1;
         }

         if ($a_tv_station_id != 1 && $a_type != 'tv') {
             if ($b_tv_station_id == 1) {
                 return 1;
             }
             if ($b_type == 'tv') {
                 return 1;
             }

             if ($b_type == '') {
                 return 0;
             }
             return 0;
         }

        /*$return = array();
        print_r($sortArray);

        $i  = 0;
        //将对象放进数组
         foreach ($objectArray as $rs) {
             $i++;
            $id         = $rs->getTvStationId();
            $k          = array_search($id, $sortArray);
            
            if (key_exists($rs->getTvStationId(), $sortArray) && !is_array($sortArray[$rs->getTvStationId()])) {
                $sortArray[$rs->getTvStationId()]   = array();
            }
            $sortArray[$rs->getTvStationId()][]  = $rs;
         }
         foreach ($sortArray as $key => $value) {
             echo $key.'<br/>';
         }
         exit;

         //将数组还原对象
//         foreach ($sortArray as $key => $value) {
//             if(is_array($value)) {
//                 foreach ($value as $k=> $v) {
//                    $return[]  = $v;
//                 }
//             }else{
//                 $return[]  = $value;
//             }
//             exit;
//         }
         return $return;*/
     }

     /**
      *  createStreamContext
      *  @author luren
     */
     static function createStreamContext() {
         $context = array(
              'http'=>array(
                    'method'=>"GET",
                    'timeout' => 5,
                    'header'=> "Accept-Language : zh-cn,zh;q=0.5 \r\n".
                        "User-Agent : Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html) \r\n".
                        "Accept	: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8 \r\n".
                        "Connection : keep-alive \r\n"
                  )
        );

        return stream_context_create($context);
     }

	 static function postUserActionToLCT($data) {
		$post_data = json_encode($data);
		$opts = array('http'=>array('method' => 'POST',
							'header' => "Content-Type: application/json\r\n",
							'content' => $post_data));
		$context = stream_context_create($opts);
		@file_get_contents(sfConfig::get('app_lct_server_url').'api/userbehavior/create', false, $context);
	 }
     
    static function file_url($key = null)
    {
        if(is_null($key))
        {
            return false;
        }else{
            $url =  sfConfig::get('app_static_url');
            $url.='%s/%s/%s/%s';
            $key_prefix = explode('.', $key);
            $key_prefix_year = substr($key_prefix[0],-2);
            $key_prefix_month = substr($key_prefix[0],-5,3);
            $key_prefix_day = substr($key_prefix[0],-9,4);
            return sprintf($url,$key_prefix_year,$key_prefix_month,$key_prefix_day,$key);
        }
    } 
    
    static function getParentid($arr,$id)
    {
        foreach($arr as $key=>$value){
            if($key==$id){
                return $key;
            }else{
        		if(array_key_exists($id, $value)){
        			return $key;				
        		}
            } 
        }
    }    

    /**
     *  str_censor
     *  @author superwen
     */
    static function str_censor($string) 
    {
        @include_once(S_ROOT.'./data/data_censor.php');
		if($_SGLOBAL['censor']['banned'] && preg_match($_SGLOBAL['censor']['banned'], $string)) {
			showmessage('information_contains_the_shielding_text');
		} else {
			$string = empty($_SGLOBAL['censor']['filter']) ? $string :
				@preg_replace($_SGLOBAL['censor']['filter']['find'], $_SGLOBAL['censor']['filter']['replace'], $string);
		}
        return $string;
    }
    
    static function Pinyin($_String, $_First = false, $_Code='UTF8'){ 
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
                "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
                "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
                "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
                "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
                "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
                "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
                "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
                "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
                "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
                "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
                "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
                "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
                "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
                "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
                "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
                "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
                "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
                "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
                "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
                "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
                "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
                "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
                "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
                "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
                "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
                "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
                "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
                "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
                "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
                "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
                "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
                "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
                "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
                "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
                "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
                "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
                "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
                "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
                "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
                "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
                "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if($_Code != 'gb2312') $_String = Common::_U2_Utf8_Gb($_String);
        $_Res = '';
        for($i=0; $i<strlen($_String); $i++) {
            $_P = ord(substr($_String, $i, 1));
            if($_P>160) {
                $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
            }
            if($_First)
                $_Res .= substr(Common::_Pinyin($_P, $_Data),0,1);
            else 
                $_Res .= Common::_Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }
    
    static function _Pinyin($_Num, $_Data){
        if($_Num>0 && $_Num<160 ){
            return chr($_Num);
        }elseif($_Num<-20319 || $_Num>-10247){
            return '';
        }else{
            foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
            return $k;
        }
    }
    
    static function _U2_Utf8_Gb($_C){
        $_String = '';
        if($_C < 0x80){
            $_String .= $_C;
        }elseif($_C < 0x800) {
            $_String .= chr(0xC0 | $_C>>6);
            $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x10000){
            $_String .= chr(0xE0 | $_C>>12);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C>>18);
            $_String .= chr(0x80 | $_C>>12 & 0x3F);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }
}
?>
