<?php


class IpTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Ip');
    }

    /**
     * 返回城市名称
     * @param <String> $ip
     * @return String
     */
    public function getCity($ip) {
        $ip_arr = explode('.', trim($ip));
        $num    = $ip_arr['0'] * 256 * 256 * 256 + $ip_arr['1'] * 256 * 256 + $ip_arr['2'] * 256 +$ip_arr['3'];
        $city   = $this->createQuery()->addWhere('ip1 <=?', $num)
                    ->addWhere('ip2 >=?', $num)
                    ->fetchOne();
        if(!$city) {
            $city   = new Ip();
            $city->setProvince('上海');
            $city->setCity('上海');
        }
        return $city;
    }
}