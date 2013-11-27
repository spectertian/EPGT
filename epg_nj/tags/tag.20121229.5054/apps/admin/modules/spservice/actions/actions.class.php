<?php

/**
 * spservice actions.
 *
 * @package    epg2.0
 * @subpackage spservice
 * @author     Huan gaobo
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
class spserviceActions extends sfActions
{
  
  /**
   * Get mongodb handler
   * @return mongo | object
   */
  public static $mdb = null; 
  
  public function getMdb()
  {
    if(null == self::$mdb){
      $mongo = $this->getMondongo();
      return self::$mdb = $mongo->getRepository("SpService");
    }else{
      return self::$mdb;
    }
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->currentNav = array("分类管理","NIT管理"); 
    $type_ = strval($request->getParameter('type_',0));
    $name = strval($request->getParameter('name',''));
    $this->haveCode = $request->getParameter('haveCode',0);
    //$typeQuery = array();
    //$nameQuery = array();
    //$haveCodeQuery = array();
    $queryArray = array();
    $query = array('sort' => array('logicNumber' => 1));
    if($type_){
      //$typeQuery = array('tags'=>$type);
        $queryArray['tags'] = $type_;
    }
    if ($this->haveCode == 1){
    	$queryArray['channel_code'] = array('$ne'=>null);
    }else if ($this->haveCode == 2){
    	$queryArray['channel_code'] = null;
    	//$queryArray['channel_code'] = array($exists=>false);
    }
    if($name){
      //$nameQuery = array('name'=>new MongoRegex("/.*".$name.".*/i"));
    	 $queryArray['name'] = new MongoRegex("/.*".$name.".*/i");
    }
    //$newquery = array_merge($typeQuery,$nameQuery,$haveCodeQuery);
    
    if(!empty($queryArray)){
      //print_r(array_merge($query,$newquery));exit;
        $query['query']= $queryArray;
    }
    //print_r($query);exit;
    
    $this->pageTitle = '运营商管理';
    $this->pager = new sfMondongoPager('SpService', 20);
    $this->pager->setFindOptions($query);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    $this->type_ = $type_;
    $this->name = $name;
    
  }
  
  /* protected function executeGetText(sfWebRequest $request)
  {
    $fp = fopen('/www/newepg/web_admin/channel_id.txt', 'r');
    $mongo = self::getMdb();
    if($fp){
      for($i=1;!feof($fp);$i++){
        $arrTemp = explode(',', fgets($fp));
        $onelist = $mongo->findOne(array(
    					'query'=>array(
    						'name'=>$arrTemp[0],
    					)
    				));
        //var_dump(str_replace("\r\n", '', $arrTemp[1]));
        $onelist->setChannelId(str_replace("\r\n", '', $arrTemp[1]));
        $onelist->save();
      }
    }
    exit;
  } */
  
  public function executeGetSpcodeByName(sfWebRequest $request)
  {
    $name = trim($request->getParameter('name',null));
    //var_dump($name);exit;
    $rs   = array();
    if($name){
        //exit('ccc');
        $arrObj = Doctrine::getTable('channel')->createQuery()
          ->where("name like ? ","%$name%")
          ->execute();
        if($arrObj){
          foreach($arrObj as $k=>$v){
            $rs[] = $v->getName().'|'.$v->getCode();
            //$rs[$k]['code'] = $v->getCode();
          }
        }
        exit(json_encode($rs,true));
      }else{
        exit(json_encode($rs,true));
      }
  }
  
  /**
   *
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeAdd(sfWebRequest $request)
  {
    if($request->isMethod("POST")){
      $params['sp_code']      = $request->getPostParameter('sp_code','');
      $params['name']         = $request->getPostParameter('name','');
      $params['serviceId']    = $request->getPostParameter('serviceId','');
      $params['frequency']    = $request->getPostParameter('frequency','');
      $params['symbolRate']   = $request->getPostParameter('symbolRate','');
      $params['modulation']   = $request->getPostParameter('modulation','');
      $params['onId']         = $request->getPostParameter('onId','');
      $params['tsId']         = $request->getPostParameter('tsId','');
      $params['logicNumber']  = $request->getPostParameter('logicNumber','');
      $params['videoPID']     = $request->getPostParameter('videoPID','');
      $params['audioPID']     = $request->getPostParameter('audioPID','');
      $params['PCRPID']       = $request->getPostParameter('PCRPID','');
      $params['isFree']       = $request->getPostParameter('isFree',1);
      $params['location']     = $request->getPostParameter('location','');
      $params['tags']         = $request->getPostParameter('tags',array());
      $params['channel_code'] = $request->getPostParameter('channel_code','');
      $params['channel_logo'] = $request->getPostParameter('channel_logo','');
      
      $addone  = new SpService();
      //$addone->setId(new MongoId());
      $addone->setSpCode($params['sp_code']);
      $addone->setName($params['name']);
      $addone->setServiceId($params['serviceId']);
      $addone->setFrequency($params['frequency']);
      $addone->setSymbolRate($params['symbolRate']);
      $addone->setModulation($params['modulation']);
      $addone->setOnId($params['onId']);
      $addone->setTsId($params['tsId']);
      $addone->setLogicNumber($params['logicNumber']);
      $addone->setVideoPID($params['videoPID']);
      $addone->setAudioPID($params['audioPID']);
      $addone->setPCRPID($params['PCRPID']);
      $addone->setIsFree($params['isFree']);
      $addone->setLocation($params['location']);
      $addone->setTags(explode(',',$params['tags']));
      $addone->setChannelCode($params['channel_code']);
      $addone->setChannelLogo(self::getLogo_($params['channel_code']));
      $addone->setHot($params['hot']);
      $addone->setCreatedAt(date("Y-m-d H:i:s"));
      
      $status = $addone->save();
      if(!$status){
        $this->getUser()->setFlash("notice",'添加成功!');
        $this->redirect('spservice/index');
      }else{
        $this->getUser()->setFlash("error",'添加失败!');
      }
      unset($params);
    }
    return sfView::SUCCESS;
  }
  
  /**
   * 
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeEdit(sfWebRequest $request)
  {
    if($request->isMethod("POST")){
      $params['postid']       = $request->getPostParameter('id','');
      $params['sp_code']      = $request->getPostParameter('sp_code','');
      $params['name']         = $request->getPostParameter('name','');
      $params['serviceId']    = $request->getPostParameter('serviceId','');
      $params['frequency']    = $request->getPostParameter('frequency','');
      $params['symbolRate']   = $request->getPostParameter('symbolRate','');
      $params['modulation']   = $request->getPostParameter('modulation','');
      $params['onId']         = $request->getPostParameter('onId','');
      $params['tsId']         = $request->getPostParameter('tsId','');
      $params['logicNumber']  = $request->getPostParameter('logicNumber','');
      $params['videoPID']     = $request->getPostParameter('videoPID','');
      $params['audioPID']     = $request->getPostParameter('audioPID','');
      $params['PCRPID']       = $request->getPostParameter('PCRPID','');
      $params['isFree']       = intval($request->getPostParameter('isfree',false));
      $params['location']     = $request->getPostParameter('location','');
      $params['tags']         = $request->getPostParameter('tags',array());
      $params['channel_code'] = $request->getPostParameter('channel_code','');
      $params['channel_logo'] = $request->getPostParameter('channel_logo','');
      $params['updatedAt']    = date("Y-m-d H:i:s");
      if($params['isFree']){
        $params['isFree'] = true;
      }else{
        $params['isFree'] = false;
      }
      
      $mongo    = self::getMdb();
      $endione  = $mongo->findOneById(new MongoId($params['postid']));
      $endione->setSpCode($params['sp_code']);
      $endione->setName($params['name']);
      $endione->setServiceId($params['serviceId']);
      $endione->setFrequency($params['frequency']);
      $endione->setSymbolRate($params['symbolRate']);
      $endione->setModulation($params['modulation']);
      $endione->setOnId($params['onId']);
      $endione->setTsId($params['tsId']);
      $endione->setLogicNumber($params['logicNumber']);
      $endione->setVideoPID($params['videoPID']);
      $endione->setAudioPID($params['audioPID']);
      $endione->setPCRPID($params['PCRPID']);
      $endione->setIsFree($params['isFree']);
      $endione->setLocation($params['location']);
      $endione->setTags(explode(',',$params['tags']));
      $endione->setChannelCode($params['channel_code']);
      $endione->setChannelLogo(self::getLogo_($params['channel_code']));
      $endione->setUpdatedAt(date('Y-m-d H:i:s'));
      if(!$endione->save()){
        $this->getUser()->setFlash("notice",'修改成功!');
      }else{
        $this->getUser()->setFlash("error",'修改失败!');
      }
      $this->redirect('spservice/index');
      unset($params);
    }else{
    	$getid = strval($request->getGetParameter('id',null));
    	if($getid){
    	  $mongo   = self::getMdb();
    	  $editone = $mongo->findOneById(new MongoId($getid));
    	  
    	  $this->id           = $editone->getId();
    	  $this->sp_code      = $editone->getSpCode();     //: string
    	  $this->name         = $editone->getName();       //: string
    	  $this->serviceId    = $editone->getServiceId();  //: string #节目Id#######
    	  $this->frequency    = $editone->getFrequency();  //: string #节目所在频点的频率值。单位是kHz
    	  $this->symbolRate   = $editone->getSymbolRate(); //: string #节目所在频点的符号率。单位是Kbps。
    	  $this->modulation   = $editone->getModulation(); //: string #节目所在频点的调制方式
    	  $this->onId         = $editone->getOnId();       //: string #节目所在频点的原始网络Id
    	  $this->tsId         = $editone->getTsId();       //: string #节目所在频点的频点Id
    	  $this->logicNumber  = $editone->getLogicNumber();//: integer #节目的逻辑频道号####### sort
    	  $this->videoPID     = $editone->getVideoPID();   //: string #节目的视频PID
    	  $this->audioPID     = $editone->getAudioPID();   //: string #节目的音频PID
    	  $this->PCRPID       = $editone->getPCRPID();     //: string #节目的PCR PID
    	  $this->isFree       = $editone->getIsFree();     //: string #节目是加扰节目还是免费节目
    	  $this->location     = $editone->getLocation();   //: string #节目的location
    	  $this->tags         = implode(($editone->getTags()), ',');   //: raw #节目分类[cctv,tv,hd,pay,local]
    	  $this->channel_code = $editone->getChannelCode();//: string #频道号
    	  $this->channel_logo = $editone->getChannelLogo();//: string #频道logo
    	  $this->createdAt    = date('Y-m-d H:i:s',$editone->getCreatedAt()->getTimestamp());  //: integer #频道热度
    	  if($editone->getUpdatedAt()){
    	    $this->updatedAt = date('Y-m-d H:i:s',$editone->getUpdatedAt()->getTimestamp());
    	  }else{
    	    $this->updatedAt = '0000-00-00 00:00:00';
    	  }
    	}
    }
    return sfView::SUCCESS;
  }
  
  /**
   *
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeDelete(sfWebRequest $request)
  {
    $getid = $request->getGetParameter('id','');
    if($getid){
      $mongo = self::getMdb();
      $deleteone = $mongo->findOneById(new MongoId($getid));
        if($deleteone){
          if(!$deleteone->delete()){
            $this->getUser()->setFlash('notice','delete success!');
          }else{
            $this->getUser()->setFlash('error','delete false! try again');
          }
        }
        $this->redirect('spservice/index');
    }
  }
  
  public function executeBatchDelete(sfWebRequest $request)
  {
    if($request->isMethod("POST"))
    {
      $ids = $request->getPostParameter('ids');
      if(count($ids)==0)
      {
        $this->getUser()->setFlash("error",'参数错误：未选择频道');
      }
      else
      {
        $mongo = self::getMdb();
        foreach($ids as $id){
          $channel = $mongo->findOneById(new MongoId($id));
          if (!is_null($channel)) $channel->delete();
        }
        $this->getUser()->setFlash("notice",'批量删除成功!');
      }
    }
    $referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : "channel/index";
    $this->redirect($referer);
  }
  
  public function executeGetChannelLogo(sfWebRequest $request)
  {
    $channelCode = $request->getParameter('channelCode',null);
    if($channelCode){
      $channelCode = strval($channelCode);
      $channels = Doctrine::getTable("Channel")->findInCodes($channelCode, null, 1);
      foreach ($channels as $val){
        $logostr = file_url($val->getLogo()).'*'.$val->getId();
      }
      //sfContext::getInstance()->getConfiguration()->loadHelpers(array('file_url'));
      exit($logostr);
    }
  }
  
  public function executeSaveChannelCode(sfWebRequest $request)
  {
    $id   = $request->getParameter('id',null);
    $name = trim($request->getParameter('name',null));
    if($id && $name){
      $mongo = self::getMdb();
      $arr = explode('|',$name);
      $endione  = $mongo->findOneById(new MongoId($id));
      $endione->setChannelCode($arr[1]);
      $endione->setChannelLogo(self::getLogo_($arr[1]));
      $endione->setUpdatedAt(date('Y-m-d H:i:s'));
      if(!$endione->save()){
        exit('1');
      }
    }else{
      exit('2');
    }
  }
  
  private function getLogo_($channelCode = '')
  {
    if($channelCode){
      $channelCode = strval($channelCode);
      $channels = Doctrine::getTable("Channel")->findOneByCode($channelCode);
      return $channels->getLogo();
    }
    return '';
  }
  /* 
  public function executeTagsarray()
  {
    exit(json_encode(array(
                  '第一类'=>array('第一种','第二种','第三种'),
                  '第二类'=>array('第一中','第二中','第三种')
                ),true));
  } */
}
