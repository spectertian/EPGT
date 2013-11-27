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
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'updateChannelTvsou';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {
        $stime = microtime(true); 
        $date = date('Y-m-d');
        $content = Common::get_url_content("http://hz.tvsou.com/jm/hw/CatchLog.asp");
        $xml = @simplexml_load_string($content);  
        if($xml){            
            $databaseManager = new sfDatabaseManager($this->configuration);
            $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
            $channels = Doctrine::getTable("Channel")->getChannels();
            foreach ($channels as $channel){
                $config = json_decode($channel->getConfig(),true);
                $channel_id = $config['tvsou']['channel_id'];
                foreach($xml->C as $c) {
                    $channelID = $c->ChannelID;
                    $catchDate = $c->CatchDate;
                    $createtime = $c->createtime;
                    if($catchDate == $date && $channel_id == $channelID){
                        $channel->setTvsouUpdate($createtime);
                        $channel->save();
                    }
                }
            }
        }
        $etime = microtime(true);
        echo "系统运行时间为：".($etime-$stime)."s\n";    
    }  
}