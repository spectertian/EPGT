<?php
/**
 * @todo activeMQ的测试程序
 * @author superwen
 * @modify 2013-6-4
 */
class mqDaemonTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('toppic', null, sfCommandOption::PARAMETER_REQUIRED, 'The topic name', ''),
        ));

        $this->namespace        = 'mq';
        $this->name             = 'Daemon';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $stomp = StompService::get();
        $topic = $options['topic'] ? $options['topic'] : "epg/testTopic";
        $stomp->subscribe($topic);
        while(1) {
            if ($stomp->hasFrame()) {
                $frame = $stomp->readFrame();
                if ($frame != NULL) {
                    print "Received: " . $frame->body . " - time now is " . date("Y-m-d H:i:s"). "\n";
                    $stomp->ack($frame);
                }
                //sleep(1);
            } else {
                print "No frames to read\n";
            }
        }
    }
}
