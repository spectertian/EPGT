<?php

class Common
{
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


    static  function convertip($ip)
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
}
?>
