<?php

/**
 * developer actions.
 *
 * @package    epg2.0
 * @subpackage developer
 * @author     majun
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class developerActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$page    = $request->getParameter('page', 1);
  	
  	$this->q = trim($request->getGetParameter('q', ''));

  	$query_arr=array();
  	if($this->q!=''){
  		$query_arr['$or']=array(array('name'=>new MongoRegex("/.*$this->q.*/i")));
  	}
  	
  	$query = array('query'=>$query_arr,'sort' => array('updated_at' => -1));
  	
  	$this->query=$query;
  	$this->pageTitle    = '开发者列表' ;
  	$this->developer = new sfMondongoPager('Developer', 20);
  	$this->developer->setFindOptions($query);
  	
  	$this->developer->setPage($page);
  	$this->developer->init();
  	
  }
  
  /**
   * 添加开发者
   * @param sfWebRequest $request
   */
  public function executeNew(sfWebRequest $request)
  {
  	$developer = $request->getParameter('developer');
  	if ($developer&&$developer['name']){
  		$dev = new Developer();
  		$dev -> setName($developer['name']);
  		$dev -> setDesc($developer['desc']);
  		$dev -> setState($developer['state']);
  		$dev -> setSources($developer['sources']);
  		$dev -> save();
  		if ($this->makeAndSaveApiKey($developer['name'])){
  			$this->getUser()->setFlash('notice', '保存成功');
  			$this->redirect('/developer/index');
  		}else{
  			$this->getUser()->setFlash('error', '保存失败');
  			$this->redirect('/developer/index');
  		}
  	}else {
    	$this->setTemplate("new");
  	}
  }
  
  /**
   * 修改
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request)
  {
	$id = $request->getParameter('id');
	$dev = $request->getParameter('developer');
	$mongo = $this->getMondongo();
	$repository = $mongo->getRepository('Developer');	
	if ($dev){
		$developer = $repository->findOneById(New MongoId($dev['id']));
		$developer -> setName($dev['name']);
  		$developer -> setDesc($dev['desc']);
  		$developer -> setState($dev['state']);
  		$developer -> setSources($dev['sources']);
  		$developer -> save();		
  		tvCache::getInstance()->set("state_".$developer->getapikey(),$dev['state'],time() +  60*60*24*180); //状态，是否锁定	
  		$this->getUser()->setFlash('notice', '保存成功');
  		$this->redirect('/developer/index');
	}else {
		$this->developer = $repository->findOneById(New MongoId($id));
		$this->redirectUnless($this->developer, 'developer/index');
		$this->setTemplate("edit");
	}
  }
  
  /**
   * 锁定开发者
   * @param sfWebRequest $request
   */
  public function executeLockDeveloper(sfWebRequest $request)
  {
  	$id = $request->getParameter('id');
  	$unlock = $request->getParameter('unlock');
  	$mongo = $this->getMondongo();
  	$repository = $mongo->getRepository('Developer');
  	$developer = $repository->findOneById(New MongoId($id));
  	if ($unlock){
  		$developer -> setState(1);
  		tvCache::getInstance()->set("state_".$developer->getapikey(),1,time() +  60*60*24*180); //状态，是否锁定
  	}else {
  		$developer -> setState(0);
  		tvCache::getInstance()->set("state_".$developer->getapikey(),0,time() +  60*60*24*180); //状态，是否锁定		
  	}
  	$developer -> save();
  	$this->getUser()->setFlash('notice', '该开发者已锁定');
  	$this->redirect($request->getReferer());
  }
  /**
   * 删除
   * @param sfWebRequest $request
   */
  public function executeDelete(sfWebRequest $request)
  {
  	$id = $request->getParameter('id');
  	$ids = $request->getParameter('ids');
  	if ($ids){
  		foreach ($ids as $id){
  			$this->deleteDeveloper($id);
  		}
  	}else {
  		$this->deleteDeveloper($id);
  	}
  	$this->getUser()->setFlash('notice', '删除成功');
  	$this->redirect($request->getReferer());
  }
  
  public function deleteDeveloper($id)
  {
  	$mongo = $this->getMondongo();
  	$repository = $mongo->getRepository('Developer');
  	$developer = $repository->findOneById(New MongoId($id));
  	$developer->delete();
  	tvCache::getInstance()->delete("secretkey_".$developer->getapikey());
  	tvCache::getInstance()->delete("state_".$developer->getapikey());
  }
  
  /**
   * 根据开发者名称生成 apikey secretkey
   * $developerName
   */
  public function makeAndSaveApiKey($developerName)
  {
  	$mongo = $this->getMondongo();
  	$repository = $mongo->getRepository('Developer');
  	
  	$developer = $repository->findOne(array('query' => array('name'=> $developerName)));
	if ($developer){
	  	$id = $developer->getId(); //获得已插入数据 id
		//获得8位随机码
		$apikey = Common::randomString(8);
		if (! $repository->findOne(array('query' => array('apikey'=> $apikey)))){//key 重复验证
			$secretkey = md5($id.$apikey);
			$developer->setApikey($apikey);
			$developer->setSecretkey($secretkey);
			$developer->save();
			return true;
		}else{
			$this->makeAndSaveApiKey($developerName);
		}
	}else {
		return false;
	}
  }
}
