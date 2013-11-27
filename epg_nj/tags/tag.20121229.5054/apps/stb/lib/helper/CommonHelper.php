<?php
/**
 * 常用的一些处理的方式
 * @author lizhi
 * @date 2011-08-08
 */

/**
 * 随机生成密码
 */
function generatePassword($len=6,$format='ALL') 
{ 
    switch($format) { 
         case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
            break;
         case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; 
            break;
         case 'NUMBER':
            $chars='0123456789'; 
            break;
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
?>
