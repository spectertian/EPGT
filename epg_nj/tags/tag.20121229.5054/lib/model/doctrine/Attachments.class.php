<?php

/**
 * Attachments
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    epg
 * @subpackage model
 * @author     Mozi Tek
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Attachments extends BaseAttachments
{
    public function delete(Doctrine_Connection $conn = null) {
        $storage = StorageService::get('photo');
        $storage->delete($this->getFileName());
        parent::delete($conn);
    }
    
    public function getFileThumbNail()
    {
        sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');

        if($this->IsImage())
        {
            if($fileurl = $this->getFileUrl(120))
            {
                return $fileurl;
            }else{
                return image_path('icon/mediamanager.png');
            }
        }else{
            return image_path('save_f2.png');
        }
    }

    public function getFileUrl($size = 0)
    {
        $url = sfConfig::get('app_static_url');
        $url .= '%s/%s';
        $UpdatedAt = $this->getUpdatedAt();
        $file_Thumb = $this->getThumb();
        $file_key = $this->getFileKey();
        $thumb = json_decode($file_Thumb,true);
        
        if( $size == 0 )
        {
            return sprintf($url,date('Y/m/d',strtotime($UpdatedAt) ),$this->getFileName());
        }

        if( $size > 0 && count($thumb) == 0  )
        {  
            return false;
        }else{
            //兼容80尺寸缩略图，如果出现第三尺寸，则直接判断当前尺寸文件是否存在，如果不存在则返回FLASH即可
            if( strlen($thumb[$size]) > 0 )
            {
                return sprintf($url,date('Y/m/d',strtotime($UpdatedAt)),$thumb[$size]);
            }else{
                if($size == 120)
                {
                    return sprintf($url,date('Y/m/d',strtotime($UpdatedAt)),$thumb['80']);
                }else{
                    return sprintf($url,date('Y/m/d',strtotime($UpdatedAt)),$thumb['120']);
                }
            }
        }
        return sprintf($url,date('Y/m/d',strtotime($UpdatedAt) ),$file_key.'_'.$size.'.jpg');
    }

    public function IsImage()
    {
        switch($this->getFileType())
        {
            case  'jpg':
            case  'png':
            case  'gif':
            case  'bmp':
                return true;
                break;
            default:
                return false;
        }
    }

    protected function getFileType()
    {
        if( strpos($this->getFileName(),'.') > -1 )
        {
          $file_ext_tmp = explode('.',$this->getFileName());
          $file_ext = strtolower(array_pop($file_ext_tmp));
          return $file_ext;
        }

        return false;
    }

}