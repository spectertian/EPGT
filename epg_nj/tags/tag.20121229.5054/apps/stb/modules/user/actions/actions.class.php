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
    /*
    * @author jianghongwei 
    */
    public function executeIndex(sfWebRequest $request)
    {
        
    }
    /*
    * 收藏
    * @author jianghongwei
    */
    public function executeCliplist(sfWebRequest $request) 
    {
        $this->user_id = $request->getParameter('uid','8250103049780978');
        /*
        if($this->user_id==0) {
            if(!$this->getUser()->isAuthenticated() ) {
                $this->redirect('user/login');
            }
            $this->user_id = $this->getUser()->getAttribute('user_id');
        }
        */
        $mongo = $this->getMondongo();
        $userRep = $mongo->getRepository("User");
        $this->user = $userRep->findOneById(new MongoId($this->user_id));
        if($this->user==NULL){
            $this->getResponse()->setTitle("用户片单 - 我爱电视");
        }else{
            $this->getResponse()->setTitle($this->user->getNickname()." 的片单 - 我爱电视");
        }
        $mongo = $this->getMondongo();
        $this->chipPager = new sfMondongoPager('SingleChip', 14);
        $this->chipPager->setFindOptions(array('query'=>array('user_id'=>$this->user_id,'is_public'=>true),'sort'=>array('created_at' => -1)));
        $this->chipPager->setPage($request->getParameter('page', 1));
        $this->chipPager->init();
    }  
}
