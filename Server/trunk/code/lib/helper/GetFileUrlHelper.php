<?php
function file_url($key = null)
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

/**
 * 获取动态缩略图
 * @param <string> $key
 * @param <int> $width
 * @param <int> $height
 */
function thumb_url($key=null, $width=75, $height=110) {
    if (empty($key)) return '';
    
    return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
}


