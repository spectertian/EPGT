<?php
/**
 * @tobo   获取EPG平台附件信息,入本地数据库attachments,并执行更新文件计划任务tv:AttachmentsCopy
 * @author gaobo
 * @time   2012-12-13
 */
class tvGetAttachmentsTask extends sfMondongoTask
{
    var $category;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('start_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The Start_time'),
            new sfCommandOption('end_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The End_time'),
            new sfCommandOption('isprint', null, sfCommandOption::PARAMETER_OPTIONAL, 'The End_time',true),
            new sfCommandOption('need_examine', null, sfCommandOption::PARAMETER_OPTIONAL, 'need_examine','yes')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetAttachments';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:GetAttachments|INFO] task does things.
Call it with:
[php symfony tv:GetAttachments|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $this->connectMaster($options);
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetAttachments');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $httpsqs = HttpsqsService::get();  
        $totalNum = 0;
        $successNum = 0;
        $falseNum   = 0; 
        $apikey = sfConfig::get('app_epghuan_apikey');
        $secretkey = sfConfig::get('app_epghuan_secretkey');
        $need_examine=isset($options["need_examine"])?$options["need_examine"]:'yes';
        $start_time = $options['start_time'] ? $options['start_time'] : date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y")));;
        $end_time = $options['end_time'] ? $options['end_time'] : date("Y-m-d H:i:s",time());
        $postdata = array('jsonstr'=>'{"action":"GetAttachments","device":{"dnum": "123"},"user":{"userid":"123"},"developer":{"apikey":"'.$apikey.'","secretkey":"'.$secretkey.'"},"param":{"start_time" : "'.$start_time.'","end_time":"'.$end_time.'"}}');
        $url = sfConfig::get('app_epghuan_url');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$postdata);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $dataArr = json_decode(strval($data),true);
        echo $start_time ." - ". $end_time."\n";
        echo "total: ",$dataArr['total'],"\n";
        sleep(1);
        if(isset($dataArr['attachments'])){
            
            foreach($dataArr['attachments'] as $v){
                $attachment = Doctrine::getTable('Attachments')->findOneByFileName($v['file_name']);
                if($attachment){
                    if($options['isprint'] != "false") {
                        echo iconv('utf-8','gbk',$v['source_name']).'###'.$v['file_name']."---\n";
                    }
                    $falseNum++;
                }else{  
                    if($options['isprint'] != "false") {
                        echo iconv('utf-8','gbk',$v['source_name']).'###'.$v['file_name']."+++\n";
                    }
                    $attachObj = new Attachments();
                    $attachObj->setCategoryId($v['category_id']);
                    $attachObj->setFileKey($v['file_key']);
                    $attachObj->setFileName($v['file_name']);
                    $attachObj->setSourceName($v['source_name']);
                    $attachObj->setCreatedAt($v['created_at']);
                    $attachObj->setUpdatedAt($v['updated_at']);
                    $attachObj->save();
                    $successNum++;
                    
                    $queue = $this->attachmentCopySqs($v['file_name']);
                    $queueOk = $httpsqs->put("epg_queue",$queue);
                    //未成功放入队列，直接执行
                    if(!$queueOk){
                        exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy  --need_examine=$need_examine --file_key=".$v['file_name']);
                    }
                    //exec("php /www/newepg/symfony tv:AttachmentsCopy --file_key=".$v['file_name']." >> /www/newepg/tmp/GetAttachmentsXX.txt");
                }
            }
            //$totalNum = count($dataArr['attachments']);
        }else{
            if($options['isprint'] != "false") {
                echo 'get nothing!\n';
            }
        }
        echo 'success:',$successNum,'  ----  ','exist:',$falseNum,"\n\n";
        $content="$start_time--$end_time---num:".$successNum;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    
    private function attachmentCopySqs($file_key) {
        $array = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array("file_key" => $file_key)
                   );
        return json_encode($array);
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
