<?php
/**
 * 测试任务
 * 
 */
class upChannelTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));
        $this->namespace        = 'up';
        $this->name             = 'Channel';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $allProvince = Province::getProvinceAll();
        //$allCity = Province::getCityAll();
        foreach($allProvince as $key => $province) {
            echo $province."\n";
            $md5 = md5($province);
            $tv_station_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
            if(is_array($tv_station_ids) && (count($tv_station_ids) > 0)) {
                foreach($tv_station_ids as $id) {
                    Doctrine::getTable('TvStation')->update_data($id,"province",$province);
                }
                $channels = Doctrine::getTable('Channel')->createQuery("c")
                                ->where('c.tv_station_id IN ('.implode(",",$tv_station_ids).')')
                                ->execute(); 
                foreach($channels as $channel) {
                    echo $channel->getName()."\n";
                    $channel->setProvince($province);
                    $channel->save();
                }  
            }
            /*
            foreach($allCity[$key] as $city) {
                echo $city."\n";
                $md5 = md5($city);
                $tv_station_ids = Doctrine::getTable('TvStation')->get_tv_station_id_by_md5($md5);
                if(is_array($tv_station_id) && (count($tv_station_ids) > 0)) {
                    foreach($tv_station_ids as $id) {
                        Doctrine::getTable('TvStation')->update_data($id,"province",$province);
                        Doctrine::getTable('TvStation')->update_data($id,"city",$city);
                    }
                    $channels = Doctrine::getTable('Channel')->createQuery("c")
                                    ->where('c.tv_station_id IN ('.implode(",",$tv_station_ids).')')
                                    ->execute(); 
                    foreach($channels as $channel) {
                        echo $channel->getName()."\n";
                        $channel->setProvince($province);
                        $channel->setCity($city);
                        $channel->save();
                    }  
                }
            }
            */
            echo "\n";
        }
    }
}
?>