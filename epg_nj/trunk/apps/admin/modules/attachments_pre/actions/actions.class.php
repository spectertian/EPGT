<?php

/**
 * attachments_pre actions.
 *
 * @package    epg2.0
 * @subpackage attachments_pre
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class attachments_preActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
    	$this->pager = new sfDoctrinePager('AttachmentsPre', 12);
        $q=Doctrine::getTable('AttachmentsPre')->createQuery()->where('verify = 0')->andWhere('isdel = 0')->orderBy('id');
		$this->pager->setQuery($q);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->page=$request->getParameter('page',1);
    }
    
    
    /**
     * 审核操作
     * Enter description here ...
     * @param sfWebRequest $request
     */
    public function executeVerify(sfWebRequest $request)
    {
    	$fileName = $request->getParameter('name');
    	$fileNames = $request->getParameter('ids');
    	$storage = StorageService::get('photo');
    	
    	if ($fileNames){//批量审核
    		foreach ($fileNames as $file) {
    			$storage->rename('pre_'.$file,$file);
    			$attachment = Doctrine::getTable('AttachmentsPre')
		  	      	  ->createQuery()->where('file_name = ?',$file)
		  	      	  ->fetchOne();
	    		if($attachment) $attachment->setVerify(1); $attachment->save();
    		}
    		$this->getUser()->setFlash("notice",'已审核');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}elseif ($fileName){//单个审核
    		$storage->rename('pre_'.$fileName,$fileName);
	    	$attachment = Doctrine::getTable('AttachmentsPre')
		  	      	  ->createQuery()->where('file_name = ?',$fileName)
		  	      	  ->fetchOne();
	    	if($attachment) $attachment->setVerify(1); $attachment->save();
    		$this->getUser()->setFlash("notice",'已审核');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}else {
    		$this->getUser()->setFlash("error",'审核失败');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}
    	
    }
    
    /**
     * 删除操作
     * Enter description here ...
     * @param sfWebRequest $request
     */
    public function executeDelete(sfWebRequest $request)
    {
    	$fileName = $request->getParameter('name');
    	$fileNames = $request->getParameter('ids');
    	if ($fileNames){//批量删除
    		foreach ($fileNames as $file) {
    			$attachmentPre = Doctrine::getTable('AttachmentsPre')
		  	      	  ->createQuery()->where('file_name = ?',$file)
		  	      	  ->fetchOne();
	    		if($attachmentPre) $attachmentPre->setIsdel(1); $attachmentPre->save();
                //同时删除附件表的记录
                $attachment = Doctrine::getTable('Attachments')->findOneByFileName($file);	
        		if($attachment) {
        			$attachment->delete();
        		}
    		}
    		$this->getUser()->setFlash("notice",'已删除');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}elseif ($fileName){//单个删除
	    	$attachmentPre = Doctrine::getTable('AttachmentsPre')
		  	      	  ->createQuery()->where('file_name = ?',$fileName)
		  	      	  ->fetchOne();
	    	if($attachmentPre) $attachmentPre->setIsdel(1); $attachmentPre->save();
            //同时删除附件表的记录
            $attachment = Doctrine::getTable('Attachments')->findOneByFileName($fileName);	
    		if($attachment) {
    			$attachment->delete();
    		}
    		$this->getUser()->setFlash("notice",'已删除');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}else {
    		$this->getUser()->setFlash("error",'请选择要删除的图片');
    		$this->redirect($this->generateUrl('',array(
                                                    'module'=>'attachments_pre',
                                                    'action'=>'index',
                                               )));
    	}
    	
    }
}
