<?php

class tvUpdateChannelTvsouTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'updateChannelTvsou';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:updateChannelTvsou|INFO] task does things.
Call it with:

  [php symfony tv:updateChannelTvsou|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        $stime=microtime(true); //获取程序开始执行的时间 
        $this->connectMaster($options);
        /*
        //select * from channel where config like '{"tvsou": {"channel_id": "999"%'
        $date=date('Y-m-d');
        $content = @file_get_contents("http://hz.tvsou.com/jm/CatchLog.asp");
        $xml = simplexml_load_string($content);
        if($xml) {
            foreach($xml->C as $c) {
                $channelID=$c->ChannelID;
                $catchDate=$c->CatchDate;
                $createtime=$c->createtime;
                if($catchDate==$date){
                    $q = Doctrine_Query::create() 
                         ->update('channel') 
                         ->set('tvsou_update=?',$createtime) 
                         ->where('config like ?', '{"tvsou": {"channel_id": "'.$channelID.'"%')
                         ->execute();   
                }
            }
        }
        */
        //另一种方法
        $date=date('Y-m-d');
        $content = @file_get_contents("http://hz.tvsou.com/jm/CatchLog.asp");
        $xml = simplexml_load_string($content);        
        $channels = Doctrine::getTable("Channel")->findAll();
        foreach ($channels as $channel){
            $config=json_decode($channel->getConfig(),true);
            $channel_id=$config['tvsou']['channel_id'];
            foreach($xml->C as $c) {
                $channelID=$c->ChannelID;
                $catchDate=$c->CatchDate;
                $createtime=$c->createtime;
                if($catchDate==$date&&$channel_id==$channelID){
                    $channel->setTvsouUpdate($createtime);
                    $channel->save();
                }
            }
        }  
        $etime=microtime(true);//获取程序执行结束的时间
        echo $etime-$stime;    //输出差值 
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
