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
public function getMyAttachments($params)
    {
      $attachments = Doctrine::getTable('Attachments')->createQuery()
        ->where('created_at >= ?',$params['start_time'])
        ->andWhere('created_at <= ?',$params['end_time'])
        ->execute();
      return $attachments;
    }
    
}