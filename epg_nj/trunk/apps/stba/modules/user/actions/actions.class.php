<?php
/**
 * user actions.
 * @package    epg2.0
 * @subpackage user
 * @author     jianghongwei
 * @version    1.0
 */
class userActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        
    }
    /*
    * 我的片单
    * @author jianghongwei
    */
    public function executeCliplist(sfWebRequest $request) 
    {
        /*
        $this->user_id = $request->getParameter('uid','8250103049780978');
        $mongo = $this->getMondongo();
        $this->chipPager = new sfMondongoPager('SingleChip', 14);
        $this->chipPager->setFindOptions(array('query'=>array('user_id'=>$this->user_id,'is_public'=>true),'sort'=>array('created_at' => -1)));
        $this->chipPager->setPage($request->getParameter('page', 1));
        $this->chipPager->init();
        */
    }  
}
