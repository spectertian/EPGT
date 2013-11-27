<?php
/**
 * theme actions.
 *
 * @package    epg
 * @subpackage theme
 * @author     superwen
 * @version    
 */

require_once dirname(__FILE__).'/../lib/themeGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/themeGeneratorHelper.class.php';

class themeActions extends autoThemeActions
{  
    public function executeIndex(sfWebRequest $request) 
	{
        $this->mc = $request->getParameter('mc', '');
        if($this->mc!=''){
            $this->themes = Doctrine_Query::create()->from('theme')->where("title like ?","%$this->mc%")->execute();
        }else{
            //$this->themes = Doctrine::getTable('theme')->findAll();
            $this->themes = Doctrine::getTable('theme')->createQuery()
                                                       ->orderBy('created_at DESC')
                                                       ->execute();
        }
    }

	public function executeAdd(sfWebRequest $request)
	{
		if($request->isMethod("POST")) {
		    /*
			$form = $request->getParameter('theme');
			$this->theme = new Theme();
			$this->theme->setTitle(trim($form['title']));
			$this->theme->setRemark($form['remark']);
			$this->theme->setImg($form['img']);
			//$this->theme->setCreatedAt(date("Y-m-d H:i:s"));
			//$this->theme->setUpdatedAt(date("Y-m-d H:i:s"));
			if($this->theme->save()==null) {
				$this->getUser()->setFlash("notice",'操作完成!');
				$this->redirect('theme/index');
			} else { 
				$this->getUser()->setFlash("error",'操作失败，请重试!');
				$this->redirect('theme/index');
			}
            */
            
            $this->form = new ThemeForm();
            $ok=$this->processForm($request, $this->form);
			if($ok) {
				$this->getUser()->setFlash("notice",'操作完成!');
				$this->redirect('theme/index');
			} else { 
				$this->getUser()->setFlash("error",'操作失败，请重试!');
				$this->redirect('theme/index');
			} 
		}else {			
			//$this->form = $this->configuration->getForm();
            $this->form = new ThemeForm();
		}
	}

	public function executeEdit(sfWebRequest $request)
	{

		if($request->isMethod("POST")) {
		    $id = $request->getParameter('id');
			$this->theme = Doctrine::getTable('theme')->findOneByID($id);
			if(!$this->theme) {		 
				$this->getUser()->setFlash("error",'该记录不存在!');
				$this->redirect('theme/index');
			} else {
			    /*
				$form = $request->getParameter('theme');
				$this->theme->setTitle(trim($form['title']));
				$this->theme->setRemark($form['remark']);
				$this->theme->setImg($form['img']);
				//$this->theme->setUpdatedAt(date("Y-m-d H:i:s"));
				if($this->theme->save()==null) {
					$this->getUser()->setFlash("notice",'操作完成!');
					$this->redirect('theme/index');
				} else { 
					$this->getUser()->setFlash("error",'操作失败，请重试!');
					$this->redirect('theme/index');
				}
                */	                
                $this->form = new ThemeForm($this->theme);
                $ok=$this->processForm($request, $this->form);
    			if($ok) {
    				$this->getUser()->setFlash("notice",'操作完成!');
    				$this->redirect('theme/index');
    			} else { 
    				$this->getUser()->setFlash("error",'操作失败，请重试!');
    				$this->redirect('theme/index');
    			} 
			}
		}else {			
			$id = $request->getParameter('id');
			$this->theme = Doctrine::getTable('theme')->findOneByID($id);				
			$this->forwardUnless($this->theme, 'theme', 'index');
			//$this->form = $this->configuration->getForm($this->theme);
            $this->form = new ThemeForm($this->theme);
		}
	}

	public function executeDelete(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
		if($this->theme) {
			if($this->theme->delete())
				$this->getUser()->setFlash("notice",'删除成功!');
			else				
				$this->getUser()->setFlash("error",'删除失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
			$this->forwardUnless($this->theme, 'theme', 'index');
		}
		$this->redirect($this->generateUrl('',array('module'=>'theme','action'=>'index')));
	}
	
    public function executePublish(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           $publish = $request->getPostParameter('publish',0);
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'操作失败！请选择需要发布的节目！');
           }else{
               foreach($ids as $id){
                   $theme = Doctrine::getTable('theme')->findOneByID($id);
                   $theme ->setPublish($publish);
                   $theme ->save();
               }
               $this->getUser()->setFlash("notice",'操作成功!');
           }
       }
	   $referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/index";
   	   $this->redirect($referer);
    }
	public function executePublishoff(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
		if($this->theme) {
			$this->theme->setPublish(0);
			if($this->theme->save())
				$this->getUser()->setFlash("notice",'设置成功!');
			else				
				$this->getUser()->setFlash("error",'设置失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
		}
		$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/index";
		$this->redirect($referer);
	}

	public function executePublishon(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
		if($this->theme) {
			$this->theme->setPublish(1);
			if($this->theme->save())
				$this->getUser()->setFlash("notice",'设置成功!');
			else				
				$this->getUser()->setFlash("error",'设置失败!');
		}else{
			$this->getUser()->setFlash("error",'该记录不存在!');
		}		
		$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/index";
		$this->redirect($referer);
	}

	public function executeListwikis(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
		if(!$this->theme) {
			$this->redirect("theme/index");
		}
		$this->items = $this->theme -> getAllItem();
	}

	public function executeAddwiki(sfWebRequest $request)
	{
	    if($request->isMethod("POST")){
    		$id = intval($request->getParameter('id'));
    		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
    		if(!$this->theme) {			
    			$this->getUser()->setFlash("error",'该记录不存在!');
    			$this->redirect("theme/index");
    		}
    		$wiki_id = trim($request->getParameter('wiki_id'));
    		$remark = trim($request->getParameter('remark'));
            $img = trim($request->getParameter('img'));            
            $is_add=true;
            if($wiki_id==''){
    			$this->getUser()->setFlash("notice",'请输入wiki名称!');
                $is_add=false;
            }
            if($is_add){
        		$mongo = sfContext::getInstance()->getMondongo();
        		$WikiRepository = $mongo->getRepository('Wiki');
        		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
        		if(!$wiki) {			
        			$this->getUser()->setFlash("error",$wiki_id.' 的wiki不存在!');
        			$this->redirect("theme/listwikis?id=".$id);
        		}
        		$themeitem = new ThemeItem();
        		$themeitem -> setThemeId($id);
        		$themeitem -> setWikiId($wiki_id);
        		$themeitem -> setRemark($remark);		
                $themeitem -> setImg($img);
        		$themeitem -> setCreatedAt(date("Y-m-d H:i:s"));
        		$themeitem -> setUpdatedAt(date("Y-m-d H:i:s"));
                /*
        		if($themeitem -> save()) {
        			$this->getUser()->setFlash("notice",'添加成功!');
        		} else {
        			$this->getUser()->setFlash("error",'添加失败!');
        		}
                */
                $themeitem -> save();
                $this->getUser()->setFlash("notice",'添加成功!');
        		//$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/listwikis?id=".$id;
        		$this->redirect("theme/listwikis?id=".$id);
            }

	    }else{
    		$id = intval($request->getParameter('id'));
    		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
    		if(!$this->theme) {
    			$this->redirect("theme/index");
    		}
	    }
	}

	public function executeEditwiki(sfWebRequest $request)
	{
	    if($request->isMethod("POST")){
    		$id = intval($request->getParameter('id'));
    		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
    		if(!$this->theme) {			
    			$this->getUser()->setFlash("error",'该记录不存在!');
    			$this->redirect("theme/index");
    		}
    		$wiki_id = trim($request->getParameter('wiki_id'));
    		$remark = trim($request->getParameter('remark'));
            $img = trim($request->getParameter('img'));
            $item_id = intval($request->getParameter('item_id'));
            
    		$mongo = sfContext::getInstance()->getMondongo();
    		$WikiRepository = $mongo->getRepository('Wiki');
    		$wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
    		if(!$wiki) {			
    			$this->getUser()->setFlash("error",$wiki_id.' 的wiki不存在!');
    			$this->redirect("theme/listwikis?id=".$id);
    		}
    		$themeitem = Doctrine::getTable('ThemeItem')->findOneByID($item_id);	
    		$themeitem -> setThemeId($id);
    		$themeitem -> setWikiId($wiki_id);
    		$themeitem -> setRemark($remark);		
            $themeitem -> setImg($img);
    		//$themeitem -> setCreatedAt(date("Y-m-d H:i:s"));
    		$themeitem -> setUpdatedAt(date("Y-m-d H:i:s"));
            /*
    		if($themeitem -> save()) {
    			$this->getUser()->setFlash("notice",'添加成功!');
    		} else {
    			$this->getUser()->setFlash("error",'添加失败!');
    		}
            */
            $themeitem -> save();
            $this->getUser()->setFlash("notice",'修改成功!');
    		//$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/listwikis?id=".$id;
    		$this->redirect("theme/listwikis?id=".$id);
	    }else{
    		$id = intval($request->getParameter('id'));
            $item_id = intval($request->getParameter('item_id'));
    		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
    		if(!$this->theme) {
    			$this->redirect("theme/index");
    		}
            $this->theme_item = Doctrine::getTable('ThemeItem')->findOneByID($item_id);    
            $Wiki =  $this->getMondongo()->getRepository("Wiki")->findOneById(new MongoId($this->theme_item->getWikiId()));
            $this->wiki_title=$Wiki->getTitle();                
	    }
	}
	public function executeDelwiki(sfWebRequest $request)
	{
		$id = intval($request->getParameter('id'));
		$this->theme = Doctrine::getTable('theme')->findOneByID($id);	
		if(!$this->theme) {			
			$this->getUser()->setFlash("error",'该记录不存在!');
			$this->redirect("theme/index");
		}
		$wiki_id = trim($request->getParameter('wiki_id'));
        $item_id = trim($request->getParameter('item_id'));
		//$items = Doctrine_Core::getTable('ThemeItem')->createQuery('a')->where('a.theme_id = ?', $id)->andwhere('a.wiki_id = ?', $wiki_id)->execute();
        $items = Doctrine_Core::getTable('ThemeItem')->createQuery('a')->where('a.id = ?', $item_id)->execute();
		if($items -> delete()) {
			$this->getUser()->setFlash("notice",'删除成功!');
		} else {
			$this->getUser()->setFlash("error",'删除失败!');
		}
		$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "theme/listwikis?id=".$id;
		$this->redirect($referer);
	}
    public function executeLoadWiki(sfWebRequest $request)
    {
        $str='';
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);
        /*
        foreach($this->wikis as $wiki){
            $str     = '<li>' . $wiki->getTitle()."|".$wiki->getDisplayName() . '</li>';
        }
        return $this->renderText('<ul>'.$str.'</ul>'); 
        */ 
    }    
    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid())
        {
            $theme = $form->save();
            return $theme;
        }else{
            return false;
        }
    }       
}