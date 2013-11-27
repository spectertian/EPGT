<?php 
/*
 * files actions.
 *
 * @package    epg
 * @subpackage files
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class filesActions extends sfActions
{
    public function executeShow(sfWebRequest $request)
    {
        $key = $request->getParameter('key');
        $mime = new MIMETypes();
        $mime_type = $mime->getMimeType($key);
        $this->getResponse()->setContentType($mime_type);
        // 定死Last-Modified 为2010-12-16日
        $this->getResponse()->setHttpHeader('Last-Modified', $this->getResponse()->getDate(1292478406));
        $storage = StorageService::get('photo');
        $content = $storage->get($key);
        $this->getResponse()->setHttpHeader('Content-Length', strlen($content));
        $this->forward404Unless($content);
        return $this->renderText($content);
    }
    
    public function executeIndex(sfWebRequest $request)
    {
      return $this->renderText('此页面不存在！');
    }

    /**
     * 图片动态缩略图生成
     * @param sfWebRequest $request
     * @return <type>
     */
    public function executeThumb_image(sfWebRequest $request) {
        $key = $request->getParameter('key');
        $w = $request->getParameter('w', null);
        $h = $request->getParameter('h', null); 
        $storage = StorageService::get('photo');
        $image = $storage->get($key);
        if ($image) {
            $mime = new MIMETypes();
            $mime_type = $mime->getMimeType($key);
            $this->getResponse()->setContentType($mime_type);
            $src_filename = "/tmp/src_".$key; // 定义原始文件保存名称
            $dest_filename = '/tmp/'.$w.'_'.$h.'_'.$key; // 定义缩略图文件保存名称
            file_put_contents($src_filename, $image);
            ImageService::create_thumb($src_filename, $dest_filename, $w, $h);
            $image_content = file_get_contents($dest_filename);
            unlink($src_filename);
            unlink($dest_filename);
            $this->getResponse()->setHttpHeader('Last-Modified', $this->getResponse()->getDate(1292478406));
            $this->getResponse()->setHttpHeader('Content-Length', strlen($image_content));
            return $this->renderText($image_content);
        } else {
            //return $this->renderText('图片不存在!');
            $key='1313030676759.png';
            $mime = new MIMETypes();
            $mime_type = $mime->getMimeType($key);
            $this->getResponse()->setContentType($mime_type);
            $src_filename = "/tmp/src_".$key; // 定义原始文件保存名称
            $dest_filename = '/tmp/'.$w.'_'.$h.'_'.$key; // 定义缩略图文件保存名称
            file_put_contents($src_filename, $image);
            ImageService::create_thumb($src_filename, $dest_filename, $w, $h);
            $image_content = file_get_contents($dest_filename);
            unlink($src_filename);
            unlink($dest_filename);
            $this->getResponse()->setHttpHeader('Last-Modified', $this->getResponse()->getDate(1292478406));
            $this->getResponse()->setHttpHeader('Content-Length', strlen($image_content));
            return $this->renderText($image_content);       
        }
    }
}
