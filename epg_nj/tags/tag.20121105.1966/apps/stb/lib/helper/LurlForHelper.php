<?php
/**
 * url判断函数
 * @param string $url
 * @return <type>
 * author lyong
 */
function lurl_for($url)
{
    if(strpos($url, "?")){
        $connection = "&";
    } else {
        $connection = "?";
    }
    $request = sfContext::getInstance()->getRequest();
    $user = sfContext::getInstance()->getUser();
    $allProvince = Province::getProvince();
    if($request->getParameter('location')){
        $province_url = $request->getParameter('location');
    }elseif($request->getCookie('province')){
        $province_url = $request->getCookie('province');
    }else{
       $province = $user->getUserProvince();
       $province_url = $allProvince[$province];
    }
    $url  = $url.$connection."location=".$province_url;
    
    return url_for($url);
}

/*
 * 返回过滤页面的url
 * @param  array $condition
 * @param  str   $filter_str
 * @param  str   $replace_str
 * @return str   $query_str
 * @author guoqiang.zhang
 */
function getQueryStrFromArray($condition,$filter_str=null,$replace_str=null)
{
  $query_str = "";
  foreach($condition as $key => $value){
    if($key == $filter_str){
        if($query_str){
            $query_str .= "&".$key."=".$replace_str;
        }else{
          $query_str .= $key."=".$replace_str;
        }
    }else{
        $query_str .= $query_str ? "&".$key ."=".$value : $key ."=".$value;
    }
  }
  return $query_str;
}

/*
 *  构建搜索分组url
 *  @param str $q 搜索字符串
 *  @param array $type 分组数据
 *  @param str  $prefix 前缀
 *  @param str $text  文本
 *  @author guoqiang.zhang
 */
function getTypeUrl($q,$type,$no =null,$str=null)
{
    foreach($type as $key => $value){
        if($value){
            $q = trim(str_replace($value, "",$q));
        }
    }
    if($str){
        $q .= " ".$str;
    }
    return $q;
}

/*
 * 从时间区间返回一个显示字符
 * @param str $time
 * @return str 
 * author: guoqiang.zhang
 */
function showTimeRange($str)
{
    if(strpos($str,"-")!== false){
        $tmpArray = explode("-",$str);
        if($tmpArray[0] == $tmpArray[1]){
            return $tmpArray[0];
        }else{
            return $str;
        }
    }else{
        return "";
    }
}
?>
