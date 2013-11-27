<?php
/**
 * 常用的一些处理的方式
 * @author lizhi
 * @date 2011-08-08
 */

/**
 * 随机生成密码
 */
function generatePassword($len=6,$format='ALL') { 
     switch($format) { 
         case 'ALL':
         $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; break;
         case 'CHAR':
         $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
         case 'NUMBER':
         $chars='0123456789'; break;
         default :
         $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
         break;
     }
     mt_srand((double)microtime()*1000000*getmypid()); 
     $password="";
     while(strlen($password)<$len)
        $password.=substr($chars,(mt_rand()%strlen($chars)),1);
     
     return $password;
 }
 
function getprovince() { 
    $allProvince = array(
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
    return $allProvince;
 } 
?>
