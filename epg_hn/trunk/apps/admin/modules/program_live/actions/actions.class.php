<?php

/**
 * program_live actions.
 *
 * @package    epg2.0
 * @subpackage program_live
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class program_liveActions extends sfActions
{
    /**
     * 
     * @param sfRequest $request A request object
     * @todo by zhigang 记住一个规则，action 只做自已应该做的事情，视图层需要的其他数据使用局部模板或组件调用
     * @todo 将选择频道的功能剥离出去
     * @todo 列表页面调用 Wiki 达100多次， 这是不正常的，找到原因并修复
     */
    public function executeIndex(sfWebRequest $request) {
        //当前时间获取
    /*    $this->current_time = $request->getParameter('date', ( $this->getUser()->getAttribute('date') ? $this->getUser()->getAttribute('date') : date("Y-m-d", time()) ));
        $this->getUser()->setAttribute('date',$this->current_time);
        $this->channel=$request->getParameter('channel', '');
        $this->start_time=$request->getParameter('start_time', '');
        $this->end_time=$request->getParameter('end_time', '');
        $channel_codes = array();
        if($this->channel!=''){
            switch($this->channel){
                case 1:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv();  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;
                case 2:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;
                case 3:
                    $channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv');  
                    foreach ($channels as $channel) {
                        $channel_codes[] = $channel->getCode();
                    }                    
                    break;                                        
                default:
            }
        }
            
        $query_arr=array();
        $query_arr['date']=$this->current_time;
        if(count($channel_codes)!=0)
            $query_arr['channel_code']=array('$in'=>$channel_codes);
        if($this->start_time!=''&&$this->end_time!=''){
            $a=new MongoDate(strtotime($this->current_time.' '.$this->start_time));
            $b=new MongoDate(strtotime($this->current_time.' '.$this->end_time));
            $query_arr['start_time']=array('$gt' => $a,'$lt' => $b);
        }elseif($this->start_time!=''){
            $a=new MongoDate(strtotime($this->current_time.' '.$this->start_time));
            $query_arr['start_time']=array('$gt' => $a);
        }elseif($this->end_time!=''){
            $b=new MongoDate(strtotime($this->current_time.' '.$this->end_time));
            $query_arr['start_time']=array('$lt' => $b);     
        }     
        //获得program集合
        $this->pager = new sfMondongoPager('Program', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('time' => 1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();*/
    }
	public function executeCCTV(sfWebRequest $request) 
	{   
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 	
		$channels = Doctrine::getTable('Channel')->getAllChannelByTv('cctv');  
		$cctvstr = '';
		foreach ($channels as $channel) 
		{
        	$program = $repository->getLiveProgramByCode($channel->getCode(),false);
        	if($program)
				$cctvstr.='<tr><td>'.$program->getName().'</td><td>'.$program->getChannelName().'</td><td>'.$program->getTime().'</td></tr>';
		}

		return $this->renderText($cctvstr);                    
	}
	
	public function executeTV(sfWebRequest $request) 
	{   
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('program'); 	
		$channels = Doctrine::getTable('Channel')->getAllChannelByTv('tv'); 
		$tvstr = '';
		foreach ($channels as $key=>$channel) 
		{
        	$program = $repository->getLiveProgramByCode($channel->getCode(),false);
        	if($program)
				$tvstr='<tr><td>'.$program->getName().'</td><td>'.$program->getChannelName().'</td><td>'.$program->getTime().'</td></tr>';
		}
		return $this->renderText($tvstr);                 
	}	

}
