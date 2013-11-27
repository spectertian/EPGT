<?php
/**
 *  @todo: 更新文件数据
 *  @author: superwen
 */
class attachmentsCopyTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
          new sfCommandOption('need_examine', null, sfCommandOption::PARAMETER_OPTIONAL, 'need to examine', 'yes'),
          new sfCommandOption('file_key', null, sfCommandOption::PARAMETER_OPTIONAL, 'file_key'),
          new sfCommandOption('is_overlap', false, sfCommandOption::PARAMETER_OPTIONAL, 'is_overlap'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'AttachmentsCopy';
        $this->briefDescription = '';	
        $this->detailedDescription = <<<EOF
The [attachmentsCopy|INFO] task does things.
Call it with:

[php symfony attachmentsCopy|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $this->connectMaster($options);  
        global $argv;        
    	if (isset($options['file_key'])){        
            $file_key = $options['file_key'];
            $file_key = explode(",",$file_key); 
            //不需要审核
            $need_examine=isset($options["need_examine"])?$options["need_examine"]:'yes';
            $storage = StorageService::get('photo');
            foreach($file_key as $key => $name) {
                //如果设置了is_overlap值，则不执行该判断
                if(!$options['is_overlap']) {
                    $content = $storage->get($name);
                    if($content) continue;
                }
                if(!file_exists("./tmp/upload/".$name)) {
                    $content = Common::get_url_content("http://image.epg.huan.tv/show/10/10/".$name, 15);
                    if($content) {
                        file_put_contents("./tmp/upload/".$name,$content);                     
                        sleep(1);                     
                    }
                }
                if(file_exists("./tmp/upload/".$name)) {
                    if($need_examine == "no") {
                        $filename = $name;
                    }else{
                        $filename = 'pre_'.$name;
                        $AttachmentsPre = Doctrine::getTable('AttachmentsPre')->findOneByFileName($name);
                        if(!$AttachmentsPre){
                            $AttachmentsPre=new AttachmentsPre();
                            $AttachmentsPre->setFileName($name);
                            $AttachmentsPre->setVerify(0);
                            $AttachmentsPre->save();   
                        }
                    }
                    $storage->save($filename,"./tmp/upload/".$name);
                    @unlink("./tmp/upload/".$name);
                }
            }     
            /*    
            if($need_examine == "no") {
                //echo iconv('utf-8','gbk',"不需要审核\n");
                $storage = StorageService::get('photo');
                foreach($file_key as $key => $name) {
                    if(!$options['is_overlap']) {
                        $content = $storage->get($name);
                        if($content) continue;
                    }
                    if(!file_exists("./tmp/upload/".$name)) {
                        $content = Common::get_url_content("http://image.epg.huan.tv/show/10/10/".$name, 5);
                        if($content) {
                            file_put_contents("./tmp/upload/".$name,$content);                     
                            sleep(1);                     
                        }
                    }
                    if(file_exists("./tmp/upload/".$name)) {
                        $storage->save($name,"./tmp/upload/".$name);
                        @unlink("./tmp/upload/".$name);
                    }
                }
            }else {
                //需要图片审核
                //echo iconv('utf-8','gbk',"需要审核\n");
                $storage = StorageService::get('photopre');
                foreach($file_key as $key => $name) {
                    if(!$options['is_overlap']) {
                        $content = $storage->get($name);
                        if($content) continue;
                    }
                    if(!file_exists("./tmp/upload/".$name)) {
                        $content = Common::get_url_content("http://image.epg.huan.tv/show/10/10/".$name, 5);
                        if($content) {
                            file_put_contents("./tmp/upload/".$name,$content);                     
                            sleep(1);                     
                        }
                    }
                    if(file_exists("./tmp/upload/".$name)) {
                        $storage->save($name,"./tmp/upload/".$name);
                        @unlink("./tmp/upload/".$name);
                    }
                    $AttachmentsPre=new AttachmentsPre();
                    $AttachmentsPre->setFileName($name);
                    $AttachmentsPre->setVerify(0);
                    $AttachmentsPre->save();
                }
            }   
            */         
        }else{
           
        }
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
