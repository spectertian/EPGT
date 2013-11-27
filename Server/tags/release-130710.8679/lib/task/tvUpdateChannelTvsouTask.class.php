<?php
/**
 * 获取tvsou的频道更新情况
 * 放到计划任务中，每1小时执行
 * @author superwen
 */
class tvUpdateChannelTvsouTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
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
        //获取程序开始执行的时间 
        $stime = microtime(true); 
        $date = date('Y-m-d');
        $this->connectMaster($options);
        $content = Common::get_url_content("http://hz.tvsou.com/jm/hw/CatchLog.asp");
        $xml = simplexml_load_string($content);        
        $channels = Doctrine::getTable("Channel")->getChannels();
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
        //获取程序执行结束的时间
        $etime=microtime(true);
        //输出差值 
        echo $etime-$stime;    
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