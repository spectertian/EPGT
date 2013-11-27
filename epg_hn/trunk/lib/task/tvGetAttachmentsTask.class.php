<?php
/**
 * @tobo   获取EPG平台附件信息,入本地数据库attachments,并执行更新文件计划任务tv:AttachmentsCopy
 * @author gaobo
 * @time   2012-12-13
 * @modify superwen 2013-4-7
 */
class tvGetAttachmentsTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'admin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('start_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The Start_time'),
            new sfCommandOption('end_time', null, sfCommandOption::PARAMETER_OPTIONAL, 'The End_time')
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetAttachments';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {           
        $this->connectMaster($options);  
        $totalNum = $successNum = $falseNum = 0;
        $start_time = $options['start_time'] ? $options['start_time'] : date("Y-m-d H:i:s", mktime(0,0,0,date("m"),date("d")-1,date("Y")));;
        $end_time = $options['end_time'] ? $options['end_time'] : date("Y-m-d H:i:s", time());
        $postdata = array('jsonstr'=>'{"action":"GetAttachments","developer":{"apikey":"UNU6HKY8","secretkey":"42057dae179f6f33ab758496bb5687c3"},"device":{"dnum": "123"},"user":{"userid":"123"},"param":{"start_time" : "'.$start_time.'","end_time":"'.$end_time.'"}}');
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.epg.huan.tv/json");
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$postdata);
        $data = curl_exec($curl);
        curl_close($curl);        
         
        $dataArr = json_decode(strval($data),true); 
        echo date("Y-m-d H:i:s")."\n";
        echo $start_time ." - ". $end_time."\n";
        echo "total: ",$dataArr['total'],"\n";
        
        if(isset($dataArr['attachments'])){            
            foreach($dataArr['attachments'] as $v){
                $attachment = Doctrine::getTable('Attachments')->findOneByFileName($v['file_name']);
                if($attachment){
                    $falseNum++;
                }else{ 
                    $attachObj = new Attachments();
                    $attachObj->setCategoryId($v['category_id']);
                    $attachObj->setFileKey($v['file_key']);
                    $attachObj->setFileName($v['file_name']);
                    $attachObj->setSourceName($v['source_name']);
                    $attachObj->setCreatedAt($v['created_at']);
                    $attachObj->setUpdatedAt($v['updated_at']);
                    $attachObj->save();
                    $successNum++;
                }
                
                //上传文件
                $storage = StorageService::get('photo');
                $content = $storage->get($v['file_name']);
                if(!$content) {
                    echo $v['file_name']."+++\n";
                    $content = Common::get_url_content("http://image.epg.huan.tv/2011/10/10/".$v['file_name'], 5);
                    file_put_contents("./tmp/upload/".$v['file_name'], $content);                     
                    sleep(1); 
                    if(!is_file("./tmp/upload/".$v['file_name'])) {
                        sleep(1);
                    }
                    $storage->save($v['file_name'], "./tmp/upload/".$v['file_name']);
                    @unlink("./tmp/upload/".$v['file_name']);
                }
            }
        }else{
        }
        echo 'success:',$successNum,'  ----  ','exist:',$falseNum,"\n\n";
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
