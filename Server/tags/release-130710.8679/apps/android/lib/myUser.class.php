<?php

class myUser extends sfBasicSecurityUser
{
    /**
     * 获取当前用户城市名称
     * @author zhigang
     */
    public function getUserCity() {
        $city = $this->getAttribute("user_city");
        if (!$city) {
            $this->initUserCityProvinceByIP();
            $city = $this->getAttribute("user_city");
        }

        return $city;
    }

    /**
     * 获取当前用户省份
     * @author zhigang
     */
    public function getUserProvince() {
        $this->initUserCityProvinceByIP();
        $province = $this->getAttribute("province");

        return $province;
    }

    /**
     * 根据IP初始化用户所在省份与城市
     */
    protected function initUserCityProvinceByIP() {
        $user_agent = sfContext::getInstance()->getRequest()->getHttpHeader('user-agent');
        preg_match('|city=([a-z]{2,20})|i', $user_agent, $match);

        if (isset($match[1]) && !empty($match[1])) {
            $province = array_search($match[1], Province::getProvince());
        } else {
            $ip = Common::get_remote_ip();
            $city_info = Doctrine::getTable('Ip')->getCity($ip);
            $city = $city_info->getCity();
            $province = $city_info->getProvince();
            $this->setAttribute("user_city", $city);
        }

        $this->setAttribute("province", $province);
    }
}
