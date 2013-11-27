<?php

/**
 * terminal actions.
 *
 * @package    epg2.0
 * @subpackage terminal
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class terminalActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
        $this->pageTitle = '终端类型管理';
        $this->pager = new sfMondongoPager('Terminal', 20);
        $this->pager->setFindOptions(array('sort' => array('created_at' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }
    public function executeAdd(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
            $form = $request->getParameter('terminal');
            $version=trim($form['version']);
            $version= str_replace(chr(13),',',$version);
            $version='{'.$version.'}';
            $arr_version=json_decode($version);
            
            $terminal=new Terminal();
            $terminal->setBrand(trim($form['brand']));
            $terminal->setClienttype(trim($form['clienttype']));
            $terminal->setVersion($arr_version);
            $ok=$terminal->save();
    		if($ok==null) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    			$this->redirect('terminal/index');
    		} else { 
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    			$this->redirect('terminal/index');
    		}   

    	}else {
            $this->form = new TerminalForm();
            //获取运营商列表
            $mongo = $this->getMondongo();
            $this->sp = $mongo->getRepository('sp')->find();
    	}
    }     
    public function executeEdit(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
            $form = $request->getParameter('terminal');
            $version=trim($form['version']);
            $version= str_replace(chr(13),',',$version);
            $version='{'.$version.'}';
            $arr_version=json_decode($version);
            
            $id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $terminal = $mongo->getRepository('Terminal')->findOneById(new MongoId($id));
            $terminal->setBrand(trim($form['brand']));
            $terminal->setClienttype(trim($form['clienttype']));
            $terminal->setVersion($arr_version);
            $ok=$terminal->save();
    		if($ok==null) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    			$this->redirect('terminal/index');
    		} else { 
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    			$this->redirect('terminal/index');
    		}   
    	}else {
            $this->id = $request->getParameter('id');
            $mongo = $this->getMondongo();
            $rs = $mongo->getRepository('Terminal')->findOneById(new MongoId($this->id));
            $this->form = new TerminalForm($rs);
            //获取运营商列表
            $mongo = $this->getMondongo();
            $this->sp = $mongo->getRepository('sp')->find();
    	}
    }       
    public function executeDelete(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('Terminal')->findOneById(new MongoId($id));
        if (!is_null($repository)) $repository->delete();
        $this->getUser()->setFlash('notice', '已删除！');
        $this->redirect('terminal/index');
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
