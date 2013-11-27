<?php
/**
 * @todo httpsqs的测试程序
 * @author superwen
 * @modify 2013-6-4
 */
class sqsTestTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'sqs';
        $this->name             = 'Test';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [cl:Test|INFO] task does things.
Call it with:
[php symfony cl:Test|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $httpsqs = HttpsqsService::get();
        for($i = 0; $i < 100; $i ++) {
            $array = array("title" => "video_add".$i,
                   "action" => "video_add",
                   "created_at" => time(),
                   "parms" => array("type" => "film",
                                    "url" => "http://www.baidu.com",
                                    "wiki_id" => "12345"));
            $result = $httpsqs->put("epg_queue",json_encode($array)); 
        }
    }
}
