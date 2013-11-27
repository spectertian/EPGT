<?php
/**
 *  @todo: 更新文件数据
 *  @author: superwen
 */
class attachmentsCopyTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
          new sfCommandOption('file_key', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
          // add your own options here
        ));

        sfConfig::set('app_photo1_config', array('hosts' => 'localhost:6001', 'domain' => 'epg', 'class' => 'image'));
        sfConfig::set('app_photo1_type', 'MogilefsStorage');
        sfConfig::set('app_static1_url','http://image.epg.huan.tv/');

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
        global $argv;
        
        $i = 0;
        $storage = StorageService::get('photo1');
        $d = dir("./upload/");
        while (false !== ($entry = $d->read())) {
            if(!is_dir($entry)) {
                echo $entry."<br>\n";
                $storage->save($entry,"./upload/".$entry);
                unlink("./upload/".$entry);
            }
        } 
        exit;
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
            while ($i < $counts) {
                $attmts = Doctrine::getTable('Attachments')->createQuery()->offset($i)->limit($limit)->execute();
                foreach($attmts as $attmt) {
                    unset($content);
                    $name = $attmt->getFileName();
                    echo $i . "\t" . $name."\n";
                    $content = @file_get_contents("http://image.epg.huan.tv/2011/10/10/".$name);
                    if($content) {
                        file_put_contents("./upload/".$name,$content);
                        exec("php ".$cmd." --file_key=".$name);
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