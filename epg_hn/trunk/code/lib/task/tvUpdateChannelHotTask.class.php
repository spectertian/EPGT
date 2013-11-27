<?php

class tvUpdateChannelHot extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'UpdateChannelHot';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:UpdateChannelHot|INFO] task does things.
Call it with:

  [php symfony tv:UpdateChannelHot|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
   
        // initialize the database connection
        //sfConfig::set('sf_debug', false);
        //$databaseManager = new sfDatabaseManager($this->configuration);
        //$connection = $databaseManager->getDatabase(isset($options['connection']) ? $options['connection'] : null)->getConnection();
        //$connection->setAttribute(Doctrine_Core::ATTR_AUTO_FREE_QUERY_OBJECTS, true );     
           
        //$databaseManager = new sfDatabaseManager($this->configuration);
        //$connection = $databaseManager->getDatabase($options['connection'])->getConnection();    
        $this->connectMaster($options);
        //当前时间-更新时间>5分钟的删除lfc
        $shijian= date('Y-m-d H:i:s');
        $sql="time_to_sec(timediff('".$shijian."', updated_at))>?";    
        /* 
        $q = Doctrine_Query::create()
                 ->delete('UserLiving') 
                 ->where($sql,300);
                 //->where('time_to_sec(timediff(now(), update_at))>?',300); 
        $numrows = $q->execute();
        */
        $q = Doctrine_Query::create() 
             ->update('UserLiving') 
             ->set('isliving=?',0)  
             ->where($sql,300)
             ->execute(); 
        //分组统计各频道当前人数，即热度
        $arrhot = Doctrine_Query::create()
            ->select('channel,count(*) as hot')
            ->from('UserLiving')
            ->where('isliving=?',1)  //只统计活动的
            ->groupBy('channel')
            ->fetchArray();
        //首先更新所有频道热度为0
        $q = Doctrine_Query::create() 
             ->update('channel') 
             ->set('hot=?',0)
             ->execute();
        //再更新有热度的频道       
        foreach($arrhot as $value){
            $q = Doctrine_Query::create() 
                 ->update('channel') 
                 ->set('hot=?',$value['hot']) 
                 ->where('code = ?', $value['channel'])
                 ->execute(); 
        }
  }
  
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }  
}
