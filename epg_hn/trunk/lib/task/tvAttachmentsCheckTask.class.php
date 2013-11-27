<?php
/**
 *  @todo: 更新文件数据
 *  @author: superwen
 */
class attachmentsCheckTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
        ));

        sfConfig::set('app_photo1_config', array('hosts' => 'localhost:6001', 'domain' => 'epg', 'class' => 'image'));
        sfConfig::set('app_photo1_type', 'MogilefsStorage');
        sfConfig::set('app_static1_url','http://image.epg.huan.tv/');

        $this->namespace        = 'tv';
        $this->name             = 'AttachmentsCheck';
        $this->briefDescription = '';	
        $this->detailedDescription = <<<EOF
The [attachmentsCopy|INFO] task does things.
Call it with:

[php symfony attachmentsCopy|INFO]
EOF;
      }

    protected function execute($arguments = array(), $options = array())
    {
        global $argv;
        
        $i = 0;
        if (isset($options['file_key'])){
            sleep(1);
            $file_key = $options['file_key'];
            $storage = StorageService::get('photo1');
            if(file_exists("./upload/".$file_key)) {
                $storage->save($file_key,"./upload/".$file_key);
                unlink("./upload/".$file_key);
            }
        }else{    
            $cmd = implode(" ", $argv);
            $this->connectMaster($options);
            $counts = Doctrine::getTable('Attachments')->count();
            $i = 1000;
            $limit = 100;
            $img_mime = array('image/gif','image/jpeg');
            while ($i < $counts) {
                $attmts = Doctrine::getTable('Attachments')->createQuery()->offset($i)->limit($limit)->execute();
                foreach($attmts as $attmt) {
                    unset($content);
                    $name = $attmt->getFileName();
                    $re = getimagesize("http://image.epg.huan.tv/2011/10/10/".$name);
                    if($re && in_array($re['mime'],$img_mime)) {                        
                        echo $i . "\t+++\t" . $name."\n";
                    } else {                        
                        echo $i . "\t---\t" . $name."\n";
                        file_put_contents("./log/checkAttachments.log",$name,FILE_APPEND);
                    }
                }
                $i = $i + $limit;
            }
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