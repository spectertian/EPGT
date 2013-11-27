<?php
/**
 * @tobo   从tmp/upload下获取文件
 * @author lifucang
 * @time   2013-05-28
 */
class tvGetAttachmentsFromLocalTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetAttachmentsFromLocal';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetAttachmentsFromLocal|INFO] task does things.
Call it with:

  [php symfony tv:GetAttachmentsFromLocal|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $this->connectMaster($options); 
        $ftp_files = $this->getNewDir('./tmp/upload/');
        $execPath = sfConfig::get('app_exec_path');
        if($ftp_files){
            foreach($ftp_files as $file) { 
                if($file=='.'||$file=='..') continue;
                echo $file,"\n";   
                exec($execPath." tv:AttachmentsCopy --is_overlap=true --need_examine=no --file_key=".$file);
                //备份之前数据
                /*
                $content = Common::get_url_content("http://image.epg.huan.tv/2011/10/10/".$file, 15);
                if($content) {
                    file_put_contents("./tmp/logo/".$file,$content);     
                    echo $file,"\n";      
                }else{
                    echo $file," error \n";
                }
                sleep(1);
                */ 
            }
            echo "finished!\n";
        }
    }
    /**
     * 找到最新的一个目录
     * @param void $ftp_conn
     */    
    private function getNewDir($path) {
        $ftp_files = scandir($path);
        if(count($ftp_files) > 0) {
            return $ftp_files;
        }else{
            return false;
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
