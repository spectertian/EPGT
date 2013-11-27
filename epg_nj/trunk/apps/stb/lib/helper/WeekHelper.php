<?php
/**
 * 功能：取得给定日期所在周的开始日期和结束日期
 * 参数：$gdate 日期，默认为当天，格式：YYYY-MM-DD
 *      $first 一周以星期一还是星期天开始，0为星期天，1为星期一
 * 返回：数组array("开始日期", "结束日期");
 * 
 */
function aweek($gdate = "", $first = 1){
    
     if(!$gdate) $gdate = date("Y-m-d");
     $w = date("w", strtotime($gdate));//取得一周的第几天,星期天开始0-6
     $dn = $w ? $w - $first : 6;//要减去的天数
     //本周开始日期
     $st = date("Y-m-d", strtotime("$gdate -".$dn." days"));
     //本周其他日期
     $st2 = date("Y-m-d", strtotime("$st +1 days"));  
     $st3 = date("Y-m-d", strtotime("$st +2 days")); 
     $st4 = date("Y-m-d", strtotime("$st +3 days")); 
     $st5 = date("Y-m-d", strtotime("$st +4 days")); 
     $st6 = date("Y-m-d", strtotime("$st +5 days"));    
     //本周结束日期
     $en = date("Y-m-d", strtotime("$st +6 days"));
     //上周开始日期
     $last_st = date('Y-m-d',strtotime("$st - 7 days"));
     //上周结束日期
     $last_en = date('Y-m-d',strtotime("$st - 1 days"));
     //return array($st, $en,$last_st,$last_en);//返回开始和结束日期
     return array('周一'=>$st,'周二'=>$st2,'周三'=>$st3,'周四'=>$st4,'周五'=>$st5,'周六'=>$st6,'周日'=>$en);//返回一周日期

}
?>