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
          new sfCommandOption('is_overlap', false, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
          // add your own options here
        ));

        sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
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
    	if (isset($options['file_key'])){        
            $file_key = $options['file_key'];
            $file_key = explode(",",$file_key);
            $storage = StorageService::get('photo1');
            foreach($file_key as $key => $name) {
                if(!$options['is_overlap']) {
                    $content = $storage->get($name);
                    if($content) continue;
                }
                if(!file_exists("./tmp/upload/".$name)) {
                    $content = @file_get_contents("http://image.epg.huan.tv/2011/10/10/".$name);
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
