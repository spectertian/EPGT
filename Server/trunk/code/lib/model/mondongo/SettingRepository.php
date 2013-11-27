<?php

/**
 * Repository of Setting document.
 */
class SettingRepository extends \BaseSettingRepository
{
    /**
     * 根据key返回value
     * @param $key key值
     * @param $change_to_array 为true则转化为数组后在返回
     * @return <type>
     * @author wn
     */    
    public function getValueByKey($key,$change_to_array=false) {
        $setting = $this->findOne(
                    array(
                        'query' => array(
                    		"key" =>$key
                            ),
                     )
                );
        if($setting)
        {
			$value = $setting->getValue();
			if($change_to_array)
			{
				return json_decode($value);
			}
			else
				return $value;
        	
        }
        else
        	return '';
    } 	
}