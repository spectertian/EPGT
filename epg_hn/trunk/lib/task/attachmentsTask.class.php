<?php
/**
 *  @todo: 更新文件数据
 *  @author: huang
 */
class attachmentsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
      // add your own options here
    ));

    sfConfig::set('app_photo_config', array('hosts' => '192.168.1.31:6001', 'domain' => '5itv', 'class' => 'image'));
    sfConfig::set('app_photo_type', 'MogilefsStorage');
    sfConfig::set('app_static_url','http://image.5i.tv/');


    $this->namespace        = 'fileChange';
    $this->name             = 'attachments';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [attachments|INFO] task does things.
Call it with:

  [php symfony attachments|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $files = Doctrine::getTable('Attachments')->findAll();

    foreach($files as $file)
    {
        $file_key = $file->getFileKey();
        $file_name = $file->getFileName();
        $file_date = $file->getUpdatedAt();
        $thumb_json = array();
        $size = 120;
        $key = time().rand(100, 999);
        $url = sfConfig::get('app_static_url');
        $url .= '%s/%s';
        $storage = StorageService::get('photo');
        
        if(strlen($file_key) == 0 )
        {
            $current_file_path = sprintf($url,date('Y/m/d',strtotime($file_date) ),$file->getFileName());
            $thumb_sizes = array(
                                array('key' => $key.'_120.jpg', 'size' => array(120, 120))
                            );
            foreach($thumb_sizes as $thumb_size)
            {
                if(is_array($thumb_size['size']))
                {
                    ImageService::create_thumb($current_file_path,'/tmp/'.$thumb_size['key'], $thumb_size['size'][0],$thumb_size['size'][1]);
                    $thumb_json = array( '120'=> $key.'_120.jpg' ,'80'=>'' );
                    $storage->save($thumb_size['key'],'/tmp/'.$thumb_size['key']);
                }
                unlink('/tmp/'.$thumb_size['key']);
            }
            $file->setThumb(json_encode($thumb_json))->save();
        }else{
            $file_url = sprintf($url,date('Y/m/d',strtotime($file_date) ),$file_key.'_'.$size.'.jpg');
            $file_off = @fopen($file_url,'r');
            if($file_off)
            {
                $thumb[$size] = $file_key.'_'.$size.'.jpg';
                $thumb['80'] = '';
             }else{
                $thumb['120'] = '';
                $thumb['80'] = $file_key.'_80.jpg';
             }
            $file->setThumb(json_encode($thumb))->save();
        }
        
    }
  }
}