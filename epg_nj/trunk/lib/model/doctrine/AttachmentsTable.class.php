<?php


class AttachmentsTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Attachments');
    }

    public function getFileLink($key)
    {
        $url = sfConfig::get('app_static_url');
        $url .= '%s/%s';
        $file = $this->createQuery()->where('file_name = ?',$key)->fetchOne();

        if($file)
        {
            return sprintf( $url,date('Y/m/d',strtotime($file->getUpdatedAt()) ),$file->getFileName());
        }else{
            return false;
        }
    }
    
    /*
     * 按照规定时间返回附件集合
     * 
     * @var $starttime  string 前一天0点
     * @var $endtime    string 当前时间
     * @author gaobo
     * @time 2012-12-12
     */
    public function getMyAttachments($params)
    {
      $attachments = Doctrine::getTable('Attachments')->createQuery()
        ->where('created_at >= ?',$params['start_time'])
        ->andWhere('created_at <= ?',$params['end_time'])
        ->execute();
      return $attachments;
    }

}