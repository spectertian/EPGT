<?php

/**
 * Recommend document.
 */
class Recommend extends \BaseRecommend
{    
    /**
     * 通过mb function 来获取相应的字数
     * @param int num
     * @return void|obj
     * @author lizhi
     */
    public function getDesc($lengh = 0) {
      $desc = trim($this->data['fields']['desc']);
        if ($lengh != 0) {
            $desc = mb_strimwidth(strip_tags($desc), 0, $lengh, '...', 'utf-8');
        }
        return $desc;
    }
}