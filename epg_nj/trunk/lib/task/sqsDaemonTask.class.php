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
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('queue', null, sfCommandOption::PARAMETER_REQUIRED, 'The queue name', 'epg_queue')
        ));
        
        $this->queues = array('epg_queue' => array('sleep' => 4, 'next_queue' => "epg_queue_retry", "retrynum" => 3),
                              'epg_queue_retry' => array('sleep' => 5, 'next_queue' => "epg_queue_death", "retrynum" => 3),
                              'epg_queue_death' => array());

        $this->namespace        = 'sqs';
        $this->name             = 'Daemon';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [cl:Daemon|INFO] task does things.
Call it with:

[php symfony sqs:Daemon|INFO]
EOF;
    //symfony sqs:Daemon --queue=epg_queue_retry
    }

    protected function execute($arguments = array(), $options = array())
    {     
        $mongo = $this->getMondongo();
        $httpsqs = HttpsqsService::get();
        //$httpsqs_retry = HttpsqsService::get();
        //$httpsqs_death = HttpsqsService::get();
        $queue = $options['queue'];  
        echo date("Y-m-d H:i:s"),"------start------\n";     
        //每30分钟执行一次，每分钟大概处理10个图片，所以条件是while($k<300)
        //在没有消息队列的情况下，一次间隔4秒，300*4=1200秒/60=20分钟 执行完毕
        $k = 0;
        while($k<300) {  
            $result = $httpsqs->gets($queue);
            $data = $result["data"];
            if ($data != "HTTPSQS_GET_END" && $data != "HTTPSQS_ERROR") {  
                $plan = @json_decode($data,true);
                if(!$plan || !isset($plan['action'])) {
                    echo "==== error ====\n";
                    echo $data."\n";
                    echo "==== /error ====\n";
                    continue;
                }
                $retry = isset($plan["retry"]) ? $plan["retry"] : 0;
                //echo $plan['title']." ".$retry." start!\n";
                
                switch($plan['action']) {
                    case "video_add":
                        $planresult = $this->video_add($plan);
                        break;
                    case "epg_update":
                        $planresult = $this->epg_update($plan);
                        break; 
                    case "attachment_copy":
                        $planresult = $this->attachment_copy($plan,$options);
                    default:
                        $planresult = true;
                }
                if(!$planresult) {                    
                    if($retry < $this->queues[$queue]['retrynum']) {
                        echo date("Y-m-d H:i:s"),'|',$plan['action'],'|',$plan['title']," (",$retry,") reput to ",$queue,"!\n";
                        $plan['retry'] = $retry + 1;
                        //$this->queue_reput($queue,$plan);
                        $httpsqs->put($queue, json_encode($plan));
                    }else{
                        $plan['retry'] = 0;
                        //记录到消息队列日志
                        $queueLog=new QueueLog();
                        $queueLog->setContent(json_encode($plan));
                        $queueLog->setState(0);
                        $queueLog->save();
                        echo date("Y-m-d H:i:s"),'|',$plan['action'],'|',$plan['title']," write to log!\n";
                        /*
                        //加入下一个队列
                        if(isset($this->queues[$queue]["next_queue"])) {
                            $next_queue = $this->queues[$queue]["next_queue"];
                            echo date("Y-m-d H:i:s"),$plan['action'],'|',$plan['title']," (",$retry,") reput to ",$next_queue,"!\n";
                            //$this->queue_reput($next_queue,$plan);
                            $httpsqs->put($next_queue, json_encode($plan));
                        }
                        */
                    }
                }else {
                    echo date("Y-m-d H:i:s"),'|',$plan['action'],'|',$plan['title']," (",$retry,") success!\n";    
                }
                
            } else {  
                //echo $queue." waiting.\n";
                sleep($this->queues[$queue]['sleep']);
            } 
            $k++; 
        }   
        echo date("Y-m-d H:i:s"),"------end------\n";     
    }
    //测试用
    protected function video_add($data)
    {
        sleep(1);
        if($data['title'] == "video_add5"){ 
            return false;
        } 
        return true;
    }
    //测试用
    protected function epg_update($data)
    {
        sleep(1);
        if($data['title'] == "epg_update5"){           
            return false;
        }
        return true;
    }
    
    protected function attachment_copy($data,$options)
    {
        $this->connectMaster($options);
        $file_key = $data['parms']['file_key'];
        if(!$file_key) {
            return true;
        }
        $need_examine = isset($data['parms']["need_examine"]) ? $data['parms']["need_examine"] : true;
        $is_overlap = isset($data['parms']["is_overlap"]) ? $data['parms']["is_overlap"] : false;
        
        $storage = StorageService::get('photo');
        if(!$is_overlap) {
            $content = $storage->get($file_key);
            if($content) return true;
        }
        if(!file_exists("./tmp/upload/".$file_key)) {
            $content = Common::get_url_content("http://image.epg.huan.tv/show/10/10/".$file_key, 20);
            if(!$content) return false;
            file_put_contents("./tmp/upload/".$file_key, $content);                     
            sleep(1);
        }
        if(file_exists("./tmp/upload/".$file_key)) {
            if($need_examine) {
                $AttachmentsPre = Doctrine::getTable('AttachmentsPre')->findOneByFileName($file_key);
                if(!$AttachmentsPre){
                    $AttachmentsPre=new AttachmentsPre();
                    $AttachmentsPre->setFileName($file_key);
                    $AttachmentsPre->setVerify(0);
                    $AttachmentsPre->save();   
                }
                $storage->save('pre_'.$file_key, "./tmp/upload/".$file_key);
            }else{ 
                $storage->save($file_key, "./tmp/upload/".$file_key);
            }
            @unlink("./tmp/upload/".$file_key);
            return true;
        }else{
            return false;
        }    
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
    
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }
}
