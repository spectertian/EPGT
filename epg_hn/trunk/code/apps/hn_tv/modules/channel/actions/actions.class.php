<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');
/**
 * channel actions.
 *
 * @package    epg2.0
 * @subpackage channel
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
    	$this->channel_code = $request->getParameter('channel_code',"cctv1");
        $this->date = $request->getParameter('date',date("Y-m-d"));
        $this->xingqi = date("N",strtotime($this->date));
        $channel = Doctrine::getTable('channel')->findOneByCode($this->channel_code);
        $this->channel_name=$channel->getName();
        $mongo = $this->getMondongo();
        $pro_repository = $mongo->getRepository('program');
        $this->programs = $pro_repository->getDayPrograms($this->channel_code, $this->date);
    }
    public function executeAll(sfWebRequest $request)
    {
        $this->channels = Doctrine::getTable('channel')->getWeiShiChannels();
    } 
    public function executeAllLive(sfWebRequest $request)
    {
        $this->channels = Doctrine::getTable('Channel')->getWeiShiChannels();
    }           
}
