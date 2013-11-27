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

}