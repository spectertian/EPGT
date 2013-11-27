<?php

require_once dirname(__FILE__).'/../lib/mediaGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/mediaGeneratorHelper.class.php';

/**
 * media actions.
 *
 * @package    epg
 * @subpackage media
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mediaActions extends autoMediaActions
{

    public function executeIndex(sfWebRequest $request)
    {
        //$this->attachments = Doctrine::getTable('Attachments')->createQuery()->where('category_id = ?',0)->orderBy('created_at DESC')->execute();
        $this->categorys = Doctrine::getTable('AttachmentCategorys')->getSelectCategorys();
        $this->category_id = $request->getParameter('category_id', 0);
        $this->page = $request->getParameter("page", 1);
        $this->popup = $request->getParameter("popup",false);
		$this->source_name = $request->getParameter("source_name",'');
		$this->wiki_title = $request->getParameter("wiki_title",'');
		$this->wiki = '';
		if($this->wiki_title)
		{
			
			$mongo = $this->getMondongo();
	        $repository = $mongo->getRepository('Wiki');
	        $this->wiki = $repository->findOneByTitle($this->wiki_title);
		}
    }

    public function executeCategory_files(sfWebRequest $request)
    {
        if($request->isXmlHttpRequest())
        {
            //$this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
            $category_id = $request->getParameter('category_id', 0);
            $page = $request->getParameter("page", 1);
            $popup = $request->getParameter("popup");
			$source_name = $request->getParameter("source_name");
			$wiki_title = $request->getParameter("wiki_title",'');
	        $mongo = $this->getMondongo();
	        $repository = $mongo->getRepository('Wiki');
	        $wiki = $repository->findOneByTitle($wiki_title);
            if ($popup) {
                $popup = true;
            } else {
                $popup = false;
            }
            return $this->renderComponent("media", "list", array("category_id" => $category_id,
                                                          "page" => $page, "popup" => $popup, "source_name"=>$source_name,"wiki"=>$wiki,"wiki_title"=>$wiki_title));
            
        }
        else
        {
        	$this->executeIndex($request);
        	$this->setTemplate('index');
        }
        
    }
    public function executeCfbywikititle(sfWebRequest $request)
    {
		if($request->isXmlHttpRequest())
        {    	
            //$this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
//            $category_id = $request->getParameter('category_id', 0);
//            $page = $request->getParameter("page", 1);
//            $popup = $request->getParameter("popup");
			$wiki_title = $request->getParameter("wiki_title");
	        $mongo = $this->getMondongo();
	        $repository = $mongo->getRepository('Wiki');
	        $wiki = $repository->findOneByTitle($wiki_title);
            return $this->renderComponent("media", "list", array("wiki"=>$wiki,"wiki_title"=>$wiki_title));
                
        }
        else
        {
        	$this->executeIndex($request);
        	$this->setTemplate('index');
        }     
    }
    /**
     * $old_key 同名则进行替换 key<=>file_name不变，只是图片变了，保证引用页面不会’暂无图片‘ 
     * @param sfWebRequest $request
     */
    public function executeUploader( sfWebRequest $request )
    {
        if($request->getMethod() == 'POST')
        {
            $this->getResponse()->setContentType('application/x-json');
            $file = $request->getFiles('Filedata');
            $category_id = $request->getParameter('category_id',0);
            if(strlen(trim($file['name'])) != 0 )
            {
            	/*
                $old_key = '';            	
                $attachment = Doctrine::getTable('Attachments')->findOneBySourceName($file['name']);
            	if($attachment){
	            	$old_key = $attachment->getFileName();
            		$attachment->delete();
            	}*/
                $file_name = $file['name'];
                $file_ext_tmp = explode('.',$file_name);
                $file_ext = strtolower(array_pop($file_ext_tmp));
                $time=time();
                $key = $time.rand(100, 999);
                $storage = StorageService::get('photo');
                //$thumb_json = array();
                
                switch($file_ext)
                {
                    case 'jpg':
                    case 'gif':
                    case 'bmp':
                    case 'png':
                          $thumb_size = array('key' => $key.'.'.$file_ext, 'size' => 'source');
                          if(!$old_key)
                          	$storage->save($thumb_size['key'],$file['tmp_name']);
                          else
                          	$storage->save($old_key,$file['tmp_name']);
                          unlink('/tmp/'.$thumb_size['key']);
                        break;
                    default :
                        //非图片文件直接保存
                        //$storage->save($key.'.'.$file_ext,$file['tmp_name']);
                        break;
                }
                
                unlink($file['tmp_name']);
                $attachments = new Attachments();
                $attachments->setFileName($key.'.'.$file_ext)
                        //->setThumb(json_encode($thumb_json))
                        ->setUpdatedAt(date("Y-m-d H:i:s",$time))
                        ->setCreatedAt(date("Y-m-d H:i:s",$time))
                        ->setSourceName($file_name)
                        ->setCategoryId($category_id)
                        ->save();                           
                return $this->renderText(json_encode($file));
            }else{
                return $this->renderText(json_encode(array('error'=>1,'message'=>'文件上传失败！')));
            }
            // $this->redirect($request->getReferer());
        }
    }

    public function executeDelete(sfWebRequest $request)
    {
        if(is_numeric($request->getParameter('id')))
        {
            $id = intval($request->getParameter('id'));
            $file = Doctrine::getTable('Attachments')->findOneById($id);
            if(!$file){
    			$this->getUser()->setFlash("error",'删除失败!');
    			$this->redirect('media', 'index');
            }else{
                $file->delete();
                $this->getUser()->setFlash('notice','文件删除成功！');
            }

        }
        $this->popup = $request->getParameter("popup",false);
    	if($this->popup)//弹出层调用 popub为true  1
        	return $this->renderComponent("media", "list", array("popup" => $this->popup));
        else
        	$this->redirect($this->generateUrl('',array(
                                                    'module'=>'media',
                                                    'action'=>'index',
                                               )));
    }

    public function executeBatchDelete(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');
        $files = Doctrine::getTable('Attachments')->createQuery()->whereIn('id',$ids)->execute();
        
        foreach($files as $file){
            $file->delete();
        }

        $this->getUser()->setFlash('notice','文件删除成功！');

        $this->redirect($this->generateUrl('',array(
                                                    'module'=>'media',
                                                    'action'=>'index',
                                               )));
//        $this->redirect($request->getReferer());
    }
    
    //ajax式批量删除 弹出层--->调用
    public function executeBatchDeletePopup(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');
        $this->popup = $request->getParameter("popup",false);
        $files = Doctrine::getTable('Attachments')->createQuery()->whereIn('id',$ids)->execute();
        
        foreach($files as $file){
            $file->delete();
        }

        $this->getUser()->setFlash('notice','文件删除成功！');
		return $this->renderComponent("media", "list", array("popup" => $this->popup));
    }
    
    public function executeBatchChangeCategorys(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');
        $category_id = $request->getParameter('change_category_id',0);
        $files = Doctrine::getTable('Attachments')->createQuery()->whereIn('id',$ids)->execute();

        foreach($files as $file){
            $file->setCategoryId($category_id)
                 ->save();
        }

        $this->getUser()->setFlash('notice','分类修改成功！');

        $this->redirect($this->generateUrl('',array(
                                                    'module'=>'media',
                                                    'action'=>'index',
                                               )));

    }
    //create:lfc
    public function executeBatchChangeCategory(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');
        $category_id = $request->getParameter('change_category_ida',0);
        $change=true;
        if(!$ids){
            $this->getUser()->setFlash('notice','请选择要移动的文件！');
            $change=false;
        }
        if(!$category_id){
            $this->getUser()->setFlash('notice','请选择要移动到的分类！');
            $change=false;
        }
        if($change){
            $files = Doctrine::getTable('Attachments')->createQuery()->whereIn('id',$ids)->execute();
    
            foreach($files as $file){
                $file->setCategoryId($category_id)
                     ->save();
            }
    
            $this->getUser()->setFlash('notice','分类修改成功！');
        }
        $this->redirect($this->generateUrl('',array(
                                                    'module'=>'media',
                                                    'action'=>'index',
                                               )));

    }
    public function executeLink( sfWebRequest $request )
    {
		if($request->isXmlHttpRequest()){    
        	$this->executeCategory_files($request);
        }else {
			$this->executeIndex($request);
        }
    }
    
    /**
     *显示缩略图处理页面
     * @param sfWebRequest $request
     */
    public function executeThumbnail(sfWebRequest $request){
        $this->url = $request->getParameter("url");
        $this->categoryId = $request->getParameter("category_id");
        $this->setTemplate("thumbnail");
    }

    /**
     * 保存缩略图
     * @param sfWebRequest $request
     * @return <type>
     */
    function executeSaveThumbnail(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()){
              sfContext::getInstance()->getConfiguration()->loadHelpers(array('GetFileUrl'));
              $url = $request->getParameter("url");
              $width = $request->getParameter("width");
              $height = $request->getParameter("height");
              $lenth = strrpos($url,"/");
              $key = substr($url,$lenth+1);
              $new_url = thumb_url($key, $width, $height);
            return $this->renderText(json_encode($new_url));
        }
    }
    
    public function executeCutPic(sfWebRequest $request)
    {
        $this->url = $request->getParameter("url");
        $this->category_id = $request->getParameter("category_id");
        $this->setTemplate("cutpic");
    }

    /**
     * 采集图片保存
     */
    public function executeCollectionPic(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()){
            $url = $request->getParameter("url");
            $category_id = $request->getParameter("category_id");
            preg_match("|http:\/\/\w+.(\w+.\w+)|",$url,$matches);
            if($matches){
                if( $matches[1] == "douban.com" ){
                    $Referer = "http://movie.douban.com/photos/photo/";
                }elseif( $matches[1] == "baidu.com" ){
                    $Referer = "http://baike.baidu.com/";
                }else{
                    $Referer = "http://".$matches[1]."/";
                }
            }
            //echo $Referer;exit;
            $context = array(
              'http'=>array(
                    'method'=>"GET",
                    'header'=> "Accept-Language: zh-cn,zh;q=0.5 \r\n".
                    "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18\r\n".
                    //"Referer: http://movie.douban.com/photos/photo/\r\n".
                    "Referer: $Referer\r\n".
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8 \r\n".
                    "Connection: keep-alive \r\n"
                  )
            );
        $context = stream_context_create($context);
        $picContent = file_get_contents($url, false, $context);
        if($picContent){
            $key = time().rand(100, 999);
            $picType = substr($url, -4,4);
            $fileName = $key.$picType;

            $temp = "/tmp/".$fileName;
            //$temp = $fileName;
            //写入临时文件
            @file_put_contents($temp,$picContent);
            $storage = StorageService::get('photo');
            $storage->save($fileName,$temp);
            @unlink($temp);
            $attachments = new Attachments();
            $attachments->setFileName($fileName)
                        //->setThumb(json_encode($thumb_json))
                        ->setSourceName($fileName)
                        ->setCategoryId($category_id)
                        ->save();

            return $this->renderText(json_encode($fileName));
        }else{
            return false;
        }
            
        }
    }
    /**
     * 保存封面裁剪图片
     * @param sfWebRequest $request
     */
    public function executeSaveCutPic(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest()){
            $x1 = $request->getParameter("x1");
            $y1 = $request->getParameter("y1");
            $width = $request->getParameter("width");
            $height = $request->getParameter("height");
            $url = $request->getParameter("url");
            // $category_id = $request->getParameter("category_id");
            // $key = time().rand(100, 999);
            // $fileName = $key.'.jpg';
            $lenth = strrpos($url,"/");
            $fileName = substr($url,$lenth+1);
            $dstFile = "/tmp/".$fileName;
            ImageService::cut_pic($url, $dstFile, $width, $height, $x1, $y1,array("width"=>200,"height"=>300));
            //保存
            $storage = StorageService::get('photo');
            $storage->save($fileName,$dstFile);
            @unlink($dstFile);
            // $attachments = new Attachments();
            // $attachments->setFileName($fileName)
                   // ->setSourceName($fileName)
                   // ->setCategoryId($category_id)
                   // ->save();
            // $lenth = strrpos($url,"/");
            // $new_url = substr($url, 0,$lenth)."/".$fileName;
            // $new_url = sfConfig::get('app_static_url')."/".date("Y/m/d",time())."/".$fileName;
            return $this->renderText(json_encode($url));
        }
    }
}
