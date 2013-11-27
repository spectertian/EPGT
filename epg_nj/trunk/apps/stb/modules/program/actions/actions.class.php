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
        $mongo = sfContext::getInstance()->getMondongo();
        $channelname = $request->getParameter("channelname");
        if(!$channelname) {
            return $this->renderText(json_encode(null));
        } 
        $channel = $mongo->getRepository('SpService')->getSpByname($channelname);
        if(!$channel){
            return $this->renderText(json_encode(null));
        } 
        $program_repo = $mongo->getRepository('program');
        $program = $program_repo->getLiveProgramByChannel($channel->getChannelCode());
        if(!$program){
            return $this->renderText(json_encode(null));
        }        
        $wiki  = $program->getWiki();
        if(!$wiki){
            return $this->renderText(json_encode(null));
        } 
        $nodeArray = array(
                    'name' => $program['name'],
                    'date' => $program['date'],
                    'start_time' => strtotime($program['date']." ".date("H:i",$program['start_time']->getTimestamp())),
                    'end_time' => strtotime($program['date']." ".date("H:i",$program['end_time']->getTimestamp())),
                    'wiki_id' => $program['wiki_id'],
                    'wiki_slug' => $wiki['slug'],
                    'wiki_cover' => file_url($wiki['cover']),
                    'tags' => $wiki['tags'],
                ); 
       
        return $this->renderText(json_encode($nodeArray));            
    }
    public function executeGetCurrentProgramTagsByChannelname(sfWebRequest $request)
    {  
        $mongo = sfContext::getInstance()->getMondongo();
        $channelname = $request->getParameter("channelname");
        if(!$channelname) {
            return $this->renderText(json_encode(null));
        } 
        $channel = $mongo->getRepository('SpService')->getSpByname($channelname);
        if(!$channel){
            return $this->renderText(json_encode(null));
        } 
        $program_repo = $mongo->getRepository('program');
        $program = $program_repo->getLiveProgramByChannel($channel->getChannelCode());
        $tags=array();
        if($program){
            $wiki = $program->getWiki();
            $tags = $wiki->getDirector() ? array_merge($wiki->getDirector(),$wiki['tags']) : $wiki['tags'];
            $tags = $wiki->getStarring() ? array_merge($wiki->getStarring(),$tags) : $tags;
        }
        if(count($tags)==0){
            $keyWord = $request->getParameter("keyword");
            if($keyWord!=''&&$keyWord!=null){
                $wikiRep = $mongo->getRepository('wiki');
                $wiki=$wikiRep->getWikiBySlug($keyWord);
                
                $wikitags=$wiki->getTags() ? implode(',', $wiki->getTags()):'';
                $directors=$wiki->getDirector()?implode(',', $wiki->getDirector()):'';
                $actors=$wiki->getStarring()?implode(',', $wiki->getStarring()):'';
                
                $mytags=$wikitags.','.$directors.','.$actors;
                $tags=explode(',',$mytags);
                $tags=array_filter($tags);  //去除空元素
                if (count($tags) > 1) {
                    shuffle($tags);                     //打乱标签
                    $tags = array_slice($tags, 0, 6);  //取两个相关的标签
                }  
            }
        }
        return $this->renderPartial('tags', array('tags'=>$tags));
    }    
}
