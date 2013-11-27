<?php
/**
 * @todo activeMQ的测试程序
 * @author superwen
 * @modify 2013-6-4
 */
class mqTestTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'mq';
        $this->name             = 'Test';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $stomp = StompService::get();
        for($i = 0; $i < 100; $i ++) {
            $array = array("title" => "video_add".$i,
                   "action" => "video_add",
                   "created_at" => time(),
                   "parms" => array("type" => "film",
                                    "url" => "http://www.baidu.com",
                                    "wiki_id" => "12345"));
            $result = $stomp->send("testQueue",json_encode($array)); 
        }
    }
}
