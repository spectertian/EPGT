<?php

/**
 * setting actions.
 *
 * @package    epg2.0
 * @subpackage setting
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class settingActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
    
    }
    public function executeEdit(sfWebRequest $request)
    {
    	if($request->isMethod("POST")) {
    	    $key = $request->getParameter('key');
            $value = $request->getParameter('value');
            $caozuo = $request->getParameter('caozuo');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            if($caozuo=='add'){
                $setting=new Setting();
                $setting->setKey($key);
                $setting->setValue(json_encode(explode(',',trim($value))));
                $setting->save();
            }else{
                $query = array('query' => array( "key" => $key ));
                $setting = $repository->findOne($query);
                $setting->setValue(json_encode(explode(',',trim($value))));
                $setting->save();
            }
			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect('setting/edit');
    	}else {
    	    $this->PageTitle='热门搜索关键词';
            $this->key = $request->getParameter('key','hotsearchkey');
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            $query = array('query' => array( "key" => $this->key ));
            $rs = $repository->findOne($query);
            if($rs){
                $this->action='edit';
                $this->value=implode(',',json_decode($rs->getValue()));
            }else{
                $this->action='add';
            }
    	}
    }   
}
