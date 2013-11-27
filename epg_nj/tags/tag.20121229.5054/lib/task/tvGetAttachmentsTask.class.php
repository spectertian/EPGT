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
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('start_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The Start_time'),
            new sfCommandOption('end_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The End_time'),
            new sfCommandOption('isprint', null, sfCommandOption::PARAMETER_OPTIONAL, 'The End_time',true)
            // add your own options here
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
        $totalNum = 0;
        $successNum = 0;
        $falseNum   = 0; 
        $start_time = $options['start_time'] ? $options['start_time'] : date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-1,date("Y")));;
        $end_time = $options['end_time'] ? $options['end_time'] : date("Y-m-d H:i:s",time());
        $postdata = array('jsonstr'=>'{"action":"GetAttachments","device":{"dnum": "123"},"user":{"userid":"123"},"param":{"start_time" : "'.$start_time.'","end_time":"'.$end_time.'"}}');
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://www.epg.huan.tv/json');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$postdata);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $dataArr = json_decode(strval($data),true);
        if(isset($dataArr['attachments'])){
            $attachObj = new Attachments();
            foreach($dataArr['attachments'] as $v){
                $attachment = Doctrine::getTable('Attachments')->findOneByFileName($v['file_name']);
                if($attachment){
                    if($options['isprint'] != "false") {
                        echo $v['file_name']."-------\n";
                    }
                    $falseNum++;
                }else{  
                    if($options['isprint'] != "false") {
                        echo $v['file_name']."+++++++\n";
                    }
                    $attachObj->setCategoryId($v['category_id']);
                    $attachObj->setFileKey($v['file_key']);
                    $attachObj->setFileName($v['file_name']);
                    $attachObj->setSourceName($v['source_name']);
                    $attachObj->setCreatedAt($v['created_at']);
                    $attachObj->setUpdatedAt($v['updated_at']);
                    $attachObj->save();
                    $successNum++;
                    exec("php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --file_key=".$v['file_name']);
                }
            }
            $totalNum = count($dataArr['attachments']);
        }else{
            if($options['isprint'] != "false") {
                echo 'get nothing!\n';
            }
        }
        echo $start_time ." - ". $end_time."\n";
        echo 'total:',$totalNum,'  success:',$successNum,'----','  fail:',$falseNum,"\n\n";
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
