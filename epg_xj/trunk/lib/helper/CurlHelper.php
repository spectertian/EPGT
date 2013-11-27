<?php

/**
 * curl 封装函数
 *
 * @param 请求地址 $url
 * @param 提交的表单数据 $postStr
 * @param cookie文件路径 $cookieFile
 * @param 设置cookie $cookies
 * @param referer $referer
 * @param 代理 $proxy
 * @return unknown
 */
function curlRequest($url, $postStr='', $cookies='', $referer='', $proxy='') {
    $url = trim($url);
    $proxy      = trim($proxy);
    $userAgent  = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
    $ch = curl_init();
    if( !empty($proxy) ){
        curl_setopt ($ch, CURLOPT_PROXY, $proxy);
    }

    /*curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
    curl_setopt($ch, CURLOPT_PROXYPORT, "8888");
    curl_setopt($ch, CURLOPT_PROXYTYPE, "http");*/

    if( !empty($cookies) ){
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    }
    if ( !empty($postStr) )
    {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
    }
    if( !empty($referer) ){
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    if (!empty($cookieFile)) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    }
    //设为TRUE在输出中包含头信息
    //echo $url;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch,CURLOPT_ENCODING , "gzip");
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $res = curl_exec($ch);
    return $res;
}
