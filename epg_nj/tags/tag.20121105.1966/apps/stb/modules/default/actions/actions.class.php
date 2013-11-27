<?php
/**
 * default actions.
 *
 * @package    epg2.0
 * @subpackage default
 * @author     Huan lifucang
 */
class defaultActions extends sfActions
{
	/**
	 * Executes index action
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
        $this->tag = $request->getParameter('type','电视剧');  //为了给executeHot方法传递
        $mongo = $this->getMondongo();
        $this->wikis=array();
        
        //先从tcl接口获取

        $wiki_repository = $mongo->getRepository('Wiki');
        $url=sfConfig::get('app_lct_url')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&count=20&uid=123";
        //$contents=file_get_contents($url);
        $contents=Common::get_url_content($url);
        if($contents){
            $arr_contents=json_decode($contents);
            foreach($arr_contents[3]->recommend as $value){
                $wiki_id = $value->contid_id;  
                $this->wikis[]=$wiki_repository->findOneById(new MongoId($wiki_id));         
            }
        }

        //获取不到从本地获取
        if(count($this->wikis)==0){
            $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
            $recommends = $wiki_recommend_repository->getRandWiki(20);  
            foreach($recommends as $recommend){
                $this->wikis[]=$recommend->getWiki();
            }    
        } 
        //专题
        $this->themes = Doctrine::getTable('theme')->getThemeByPageAndSize(1,4);
    }
	/**
	 * 正常调用的首页
	 * @param sfRequest $request A request object
	 */
	public function executeDefault(sfWebRequest $request)
	{
        $mongo = $this->getMondongo();
        $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
        $this->recommends = $wiki_recommend_repository->getRandWiki(6);
    }	
	/**
	 * Executes live action
	 * @param sfRequest $request A request object
	 */
	public function executeLive(sfWebRequest $request)
	{
        $mongo = $this->getMondongo();
        $wiki_recommend_repository = $mongo->getRepository('WikiRecommend');
        $this->recommends = $wiki_recommend_repository->getRandWiki(6);
    }
	
	/**
	 * Executes hot action
	 * @param sfRequest $request A request object
	 */
	public function executeHot(sfWebRequest $request)
	{
      	$this->tag = $request->getParameter('type','电视剧');
        $this->types = array("电视剧", "电影", "体育", "娱乐", "少儿", "科教", "财经", "综合");
        $mongo = $this->getMondongo();
        $channels = Doctrine::getTable('Channel')->getChannels();
        $programs = $mongo->getRepository("program");
     	$this->programList = $programs->getLiveProgramByTag($this->tag, $channels,8);   
        if($this->programList){
            $this->programTop = $this->programList[0];   
        }else{
            $this->programTop = NULL;
        }
	}
    /**
    * 404 错误页面
    */
    public function executeError404(sfWebRequest $request) {
      
    }
}
