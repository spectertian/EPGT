<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 获取维基类型名称
 * @param <String> $className
 */
function getTitleByClassName($className) {
    switch ($className) {
        case 'Wiki_FlimTV_Film':
            return '电影';

        case 'Wiki_FilmTV_Teleplay':
            return '电视剧';
            
        default:
            return '未知';
    }
}
?>
