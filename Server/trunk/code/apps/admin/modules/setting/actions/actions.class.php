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
    	if($request->isMethod("POST")) 
    	{
    		
    		//三个关键字设定入库
    		for($i=0;$i<=2;$i++)
    		{
    			$key = $_POST['key'][$i];
    			$caozuo = $_POST['caozuo'][$i];
	            $value = $_POST['value'][$i]; 
	               
	            $mongo = $this->getMondongo();
	            $repository = $mongo->getRepository('Setting');
	            if($caozuo=='add')
	            {
	                $setting=new Setting();
	                $setting->setKey($key);
	                $setting->setValue(json_encode(explode(',',trim($value))));
	                $setting->save();
	            }
	            else
	            {
	                $query = array('query' => array( "key" => $key ));
	                $setting = $repository->findOne($query);
	                $setting->setValue(json_encode(explode(',',trim($value))));
	                $setting->save();
	            }

    			
    		}
          
			$this->getUser()->setFlash("notice",'操作完成!');
			$this->redirect('setting/edit');
    	}
    	else 
    	{
    	    $this->PageTitle='关键字设定';
    	    
    	    $this->HotSearchTitle='热门关键搜索词';
    	    $this->DefaultCollectionChannelTitle='默认收藏频道';
    	    $this->SportSearchTitle='体育关键词';
    	    
            $this->HotSearchKey = 'hotsearchkey';
            $this->DefaultCollectionChannelKey = 'collectionsearchkey';
            $this->SportSearchKey = 'sportsearchkey';
            
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('Setting');
            
            //热门关键搜索词
            $host_query = array('query' => array( "key" => $this->HotSearchKey ));
            $hot_rs = $repository->findOne($host_query);
            if($hot_rs)
            {
                $this->hot_action='edit';
                $this->hot_value=implode(',',json_decode($hot_rs->getValue()));
            }
            else
            {
                $this->hot_action='add';
            }
            
            //默认收藏频道
    		$default_query = array('query' => array( "key" => $this->DefaultCollectionChannelKey ));
            $default_rs = $repository->findOne($default_query);
            if($default_rs)
            {
                $this->default_action='edit';
                $this->default_value=implode(',',json_decode($default_rs->getValue()));
            }
            else
            {
                $this->default_action='add';
            }
            
            //体育关键词
    		$sport_query = array('query' => array( "key" => $this->SportSearchKey ));
            $sport_rs = $repository->findOne($sport_query);
            if($sport_rs)
            {
                $this->sport_action='edit';
                $this->sport_value=implode(',',json_decode($sport_rs->getValue()));
            }
            else
            {
                $this->sport_action='add';
            }
    	}
    }

}
