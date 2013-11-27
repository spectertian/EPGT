<?php
/**
 *  @todo: 更新文件数据
 *  @author: superwen
 *  @editor: lifucang 2013-06-08
 */
class attachmentsCheckTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
          new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
          new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
          new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'AttachmentsCheck';
        $this->briefDescription = '';	
        $this->detailedDescription = <<<EOF
The [AttachmentsCheck|INFO] task does things.
Call it with:

[php symfony tv:AttachmentsCheck|INFO]
EOF;
      }

    protected function execute($arguments = array(), $options = array())
    {
        global $argv;
        
        if (isset($options['file_key'])){
            
        }else{    
            $cmd = implode(" ", $argv);
            $this->connectMaster($options);
            $counts = Doctrine::getTable('Attachments')->createQuery()->count();
            $storage = StorageService::get('photo');
            $i = 0;
            $limit = 200;
            while ($i < $counts) {
                echo $i." ===\n";
                $attmts = Doctrine::getTable('Attachments')->createQuery()->orderBy('id desc')->offset($i)->limit($limit)->execute();
                foreach($attmts as $attmt) {
                    $name = $attmt->getFileName();
                    $content = $storage->get($name);
                    if(!$content) {
                        echo $name."\n";
                        exec("php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --need_examine=no --env=prod --file_key=".$name);
                    }
                }
                $i = $i + $limit;
                sleep(1);
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