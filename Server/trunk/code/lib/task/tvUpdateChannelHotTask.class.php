<?php
/**
 * 更新频道热度
 * 放到计划任务中，每5分钟执行
 * @author superwen
 */
class tvUpdateChannelHot extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
        ));

        $this->namespace    = 'tv';
        $this->name         = 'UpdateChannelHot';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:UpdateChannelHot|INFO] task does things.
Call it with:
    [php symfony tv:UpdateChannelHot|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $this->connectMaster($options);
        
        //当前时间-更新时间>5分钟的删除lfc
        $timeout_sql = "time_to_sec(timediff('".date('Y-m-d H:i:s')."', updated_at))>?";        
        $query = Doctrine_Query::create()
                         ->delete('UserLiving') 
                         ->where($timeout_sql,3000)
                         ->limit(5000);
        $numrows = $query->execute();
        
        //首先更新所有频道热度为0
        $hot_query = Doctrine_Query::create() 
                        ->update('channel') 
                        ->set('hot=?',0)
                        ->execute();
                 
        //分组统计各频道当前人数，即热度
        $arrhot = Doctrine_Query::create()
                        ->select('channel,count(*) as hot')
                        ->from('UserLiving')
                        ->where('isliving=?', 1)
                        ->groupBy('channel')
                        ->fetchArray();                        
        
        //再更新有热度的频道             
        foreach($arrhot as $value){
            Doctrine_Query::create() 
                        ->update('channel') 
                        ->set('hot=?',$value['hot']) 
                        ->where('code = ?', $value['channel'])
                        ->execute(); 
        }
        
        //删除transfer_statistics24小时前记录
        $mongo = $this->getMondongo();
        $ts_repos = $mongo->getRepository('TransferStatistics');
        $ts_repos->remove();
        $numrows = $query->execute();     

        
        /*
         * 每五分钟执行           提交用户收看信息 ReportUserLivingAction
         * @editer gaobo 
         */
        $memcache = tvCache::getInstance();
        $memcache_key = 'ReportUserLivingAction';
        $cacheArray = $memcache->get($memcache_key);
        print_r($cacheArray);
        if($cacheArray){
            foreach($cacheArray as $userid=>$channel){
                //判断是否存在该频道
                $arrchannel = Doctrine::getTable('Channel')->createQuery()
                ->where('code = ?', $channel)
                ->orWhere('name = ?', $channel)
                ->fetchOne();
                if($arrchannel) {
                    $channel_code=$arrchannel->getCode();
                } else {
                    $nodeArray = $this->getErrArray("true",null,null,'该频道不存在');
                    return $this->arrayToDom($nodeArray);
                }
                
                //是否有该用户记录
                $userliving = Doctrine::getTable('UserLiving')->createQuery()
                ->where('user_id = ?', $userid)
                ->fetchOne();
                if ($userliving) {
                    //是否有该用户访问该频道记录
                    $userlivinga = Doctrine::getTable('UserLiving')->createQuery()
                    ->where('user_id = ?', $userid)
                    ->andWhere('channel = ?', $channel_code)
                    ->fetchOne();
                    if($userlivinga) {
                        $userlivinga->setIsliving(1);
                        $userlivinga->setUpdatedAt(date('Y-m-d H:i:s'));
                        $userlivinga->save();
                        //$info='有该用户查看该频道记录，更新';
                        //$info=2;
                        $ts = new transferStatistics();
                        $ts->setUserid($userid);
                        $ts->setTochannelCode($channel_code);
                        $ts->save();
                    } else {
                        //更新该用户其他频道活动标志为0
                        $q = Doctrine_Query::create()
                        ->update('UserLiving')
                        ->set('isliving=?',0)
                        ->where('user_id = ?', $userid);
                        $numrows = $q->execute();
                        //插入该频道记录
                        $living=new UserLiving();  //实例化类后调用
                        $living->setUserId($userid);
                        $living->setChannel($channel_code);
                        $living->setCreatedAt(date('Y-m-d H:i:s'));
                        $living->setUpdatedAt(date('Y-m-d H:i:s'));
                        $living->setIsliving(1);
                        $living->save();
                        //$info='无该用户查看该频道记录，添加并更新该用户其他频道活动状态';
                        //$info=3;
                        $ts = new transferStatistics();
                        $ts->setUserid($userid);
                        $ts->setTochannelCode($channel_code);
                        $ts->save();
                    }
                } else {
                    $living=new UserLiving();  //实例化类后调用
                    $living->setUserId($userid);
                    $living->setChannel($channel_code);
                    $living->setCreatedAt(date('Y-m-d H:i:s'));
                    $living->setUpdatedAt(date('Y-m-d H:i:s'));
                    $living->setIsliving(1);
                    $living->save();
                    //$info='无该用户，保存记录';
                    //$info=1;
                    $ts = new transferStatistics();
                    $ts->setUserid($userid);
                    $ts->setTochannelCode($channel_code);
                    $ts->save();
                }
            }
        }
        $memcache->delete($memcache_key);
    }
    
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) 
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }    
}
