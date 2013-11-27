<?php
/**
 * @todo httpsqs的守护进程
 * @author superwen
 * @modify 2013-6-4
 */
class sqsDaemonTask extends sfMondongoTask
{    
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('queue', null, sfCommandOption::PARAMETER_REQUIRED, 'The queue name', 'epg_queue')
        ));
        
        $this->queues = array('epg_queue' => array('sleep' => 2, 'next_queue' => "epg_queue_retry", "retrynum" => 2),
                              'epg_queue_retry' => array('sleep' => 20, 'next_queue' => "epg_queue_death", "retrynum" => 3),
                              'epg_queue_death' => array());

        $this->namespace        = 'sqs';
        $this->name             = 'Daemon';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [cl:Daemon|INFO] task does things.
Call it with:

[php symfony cl:Daemon|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {         
        $httpsqs = HttpsqsService::get();
        $queue = $options['queue'];
        
        while(true) {  
            $result = $httpsqs->gets($queue);
            echo $result["data"]."\n";
            if ($result["data"] != "HTTPSQS_GET_END" && $result["data"] != "HTTPSQS_ERROR") {  
                $plan = @json_decode($result["data"],true);
                if(!$plan || !isset($plan['action'])) {
                    echo "==== error ====\n";
                    echo $data."\n";
                    echo "==== /error ====\n";
                    continue;
                }
                $retry = isset($plan["retry"]) ? $plan["retry"] : 0;
                echo $plan['action']." ".$retry." start!\n";
                
                switch($plan['action']) {
                    case "video_add":
                        $planresult = $this->video_add($plan);
                        break;
                    case "wiki_insert":
                    case "wiki_add":
                        $planresult = $this->wiki_insert($plan);
                        break;
                    case "wiki_update":
                        $planresult = $this->wiki_update($plan);
                        break;  
                    default:
                        $planresult = true;
                }
                if(!$planresult) {                    
                    if($retry < 2) {
                        echo $plan['action']." ".$retry." lost and reput to ".$queue."!\n";
                        $plan['retry'] = $retry + 1;
                        $this->queue_reput($queue,$plan);
                    }else{
                        $plan['retry'] = 0;
                        if(isset($this->queues[$queue]["next_queue"])) {
                            echo $plan['action']." ".$retry." lost and reput to ".$this->queues[$queue]["next_queue"]."!\n";
                            $this->queue_reput($this->queues[$queue]["next_queue"],$plan);
                        }
                    }
                }else {
                    echo $plan['action']." ".$retry." success!\n";  
                    usleep(200);
                }
            } else {  
                echo $queue." waiting.\n";
                sleep($this->queues[$queue]['sleep']);
            }  
        }   
    }
    
    protected function video_add($data)
    {
        sleep(1);
        if($data['parms']['video_id'] == "video_add5"){ 
            return false;
        } 
        return true;
    }
    
    protected function wiki_insert($data)
    {
        sleep(1);
        if($data['parms']['wiki_id'] == "wiki_update5"){           
            return false;
        }
        return true;
    }
    
    protected function wiki_update($data)
    {
        sleep(1);
        if($data['parms']['wiki_id'] == "wiki_update5"){           
            return false;
        }
        return true;
    }
    
    /*
     * reput a lost queue
     * @param queue string
     * @param date array
     * @param pos int
     */
    protected function queue_reput($queue, $data)
    {
        $httpsqs = HttpsqsService::get();
        $httpsqs->put($queue, json_encode($data));        
    }
}
