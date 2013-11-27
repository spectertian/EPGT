<?php

/**
 * memcache actions.
 *
 * @package    epg2.0
 * @subpackage memcache
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class memcacheActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $key = $request->getParameter('key');
            $md5 = $request->getParameter('md5');
            if($key==''||empty($key)){
                $this->key='';
                $this->getUser()->setFlash("notice",'请输入key值!');
            }else{
                $this->key=$key;
                if($md5==1)
                    $key=md5($key);
                $memcache = tvCache::getInstance();
                $values = $memcache->get($key);
        		if(!$values)
                    $values='该key值不存在';
                $this->value= $values;
            }
            //$this->redirect('memcache/index');
    	}else {
    	    $this->value='';
            $this->key='';
    	}
    }
    public function executeDelete(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $key = $request->getParameter('key');
            $md5 = $request->getParameter('md5');
            if($key==''||empty($key)){
                $this->key='';
                $this->getUser()->setFlash("notice",'请输入key值!');
            }else{
                $this->key=$key;
                if($md5==1)
                    $key=md5($key);                
                $memcache = tvCache::getInstance();
                $ok = $memcache->delete($key);
        		if($ok)
                    $tishi='该key值已删除';
                else
                    $tishi='删除失败';    
                $this->value= $tishi;
            }
            $this->setTemplate('index');
    	}else {
    	    $this->value='';
            $this->key='';
            $this->setTemplate('index');
    	}
    }    
}
