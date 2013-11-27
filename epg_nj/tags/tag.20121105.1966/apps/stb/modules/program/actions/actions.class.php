<?php

/**
 * program actions.
 *
 * @package    epg2.0
 * @subpackage program
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl'); 
class programActions extends sfActions
{
   /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
        $this->forward('default', 'module');
    }
    
    public function executeGetCurrentProgramByChannelname(sfWebRequest $request)
    {
        $channelname = $request->getParameter("channelname");
        $array_merge = $request->getParameter("array_merge",'');
        if(!$channelname) {
            return $this->renderText(json_encode(null));
        } 
        $channel = Doctrine::getTable('Channel')->findOneByName($channelname);
        if(!$channel){
            return $this->renderText(json_encode(null));
        } 
        $mongo = sfContext::getInstance()->getMondongo();
        $program_repo = $mongo->getRepository('program');
        $program = $program_repo->getLiveProgramByChannel($channel->getCode());
        $wiki  = $program->getWiki();
        
        if(!$array_merge) {
            $nodeArray = array(
    					'name' => $program['name'],
    					'date' => $program['date'],
    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
    					'wiki_id' => $program['wiki_id'],
    					'wiki_cover' => file_url($wiki['cover']),
    					'tags' => $wiki['tags'],
    	            ); 
        }else {
            $tags = $wiki->getDirector() ? array_merge($wiki->getDirector(),$wiki['tags']) : $wiki['tags'];
            $tags = $wiki->getStarring() ? array_merge($wiki->getStarring(),$tags) : $tags;
            $nodeArray = array(
    					'name' => $program['name'],
    					'date' => $program['date'],
    					'start_time' => date("H:i",$program['start_time']->getTimestamp()),
    					'end_time' => date("H:i",$program['end_time']->getTimestamp()),
    					'wiki_id' => $program['wiki_id'],
    					'wiki_cover' => file_url($wiki['cover']),
    					'tags' => $tags,
    	            );
        }
        return $this->renderText(json_encode($nodeArray));            
    }
    
    public function executeGetCurrentProgramTagsByChannelname(sfWebRequest $request)
    {
        $channelname = $request->getParameter("channelname");
        if(!$channelname) {
            return $this->renderText(json_encode(null));
        } 
        $channel = Doctrine::getTable('Channel')->findOneByName($channelname);
        if(!$channel){
            return $this->renderText(json_encode(null));
        } 
        $mongo = sfContext::getInstance()->getMondongo();
        $program_repo = $mongo->getRepository('program');
        $program = $program_repo->getLiveProgramByChannel($channel->getCode());
        $wiki  = $program->getWiki();
        
        $tags = $wiki->getDirector() ? array_merge($wiki->getDirector(),$wiki['tags']) : $wiki['tags'];
        $tags = $wiki->getStarring() ? array_merge($wiki->getStarring(),$tags) : $tags;
        return $this->renderPartial('tags', array('tags'=>$tags));
    }
}
