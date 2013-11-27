<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
/**
 * search actions.
 *
 * @package    epg2.0
 * @subpackage search
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $program_repository = $mongo->getRepository('Program');
        $program = $program_repository->getOneLiveProgramByCode();
        $this->wiki = $program->getWiki(); 
    }

    public function executeResult(sfWebRequest $request)
    {
        $this->key  = trim($request->getParameter('key',''));
        $query_arr=array();
        if($this->key!=''){
            $query_arr['$or']=array(array('title'=>new MongoRegex("/.$this->key.*/i")),array('slug'=>new MongoRegex("/.$this->key.*/i")),array('tags'=>array('$in'=>array($this->key))));
        }
        $mongo = $this->getMondongo();
        $wiki_repository = $mongo->getRepository('Wiki');
        $this->wikis = $wiki_repository->find(array('query'=>$query_arr,'limit'=>16));
        $this->count=(string)count($this->wikis);
    }   
    
	public function executeWiki(sfWebRequest $request)
	{
		if ($request->isXmlHttpRequest()) 
		{
			$id = $request->getParameter('id');
	    	$mongo = $this->getMondongo();
	    	$pository = $mongo->getRepository('Wiki');
	    	$wiki = $pository->findOneById(new MongoId($id));
	    	$result=array('title'=>$wiki->getTitle(),'slug'=>$wiki->getSlug(),'content'=>mb_substr($wiki->getContent(), 0, 100, 'utf-8'));
			return $this->renderText(json_encode($result));
		}
		else 
		{
			$this->forward404();
		}
		
	}     
}
