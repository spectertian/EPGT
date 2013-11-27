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

        sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
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
            
        }else{    
            $cmd = implode(" ", $argv);
            $this->connectMaster($options);
            $counts = Doctrine::getTable('Attachments')->createQuery()->where('created_at >= ?','2012-12-07 18:00:25')->andWhere('created_at <= ?','2012-12-10 19:18:00')->count();
            $storage = StorageService::get('photo1');
            $i = 0;
            $limit = 100;
            while ($i < $counts) {
                echo $i." ===\n";
                $attmts = Doctrine::getTable('Attachments')->createQuery()->where('created_at >= ?','2012-12-07 18:00:25')->andWhere('created_at <= ?','2012-12-10 19:18:00')->offset($i)->limit($limit)->execute();
                foreach($attmts as $attmt) {
                    $name = $attmt->getFileName();
                    $content = $storage->get($name);
                    if($content) {
                        echo $name."---------\n";
                    }else {
                        echo $name."+++++++++++\n";
                        exec("php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --env=prod --file_key=".$name);
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