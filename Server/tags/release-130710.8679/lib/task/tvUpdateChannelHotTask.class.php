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
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
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
