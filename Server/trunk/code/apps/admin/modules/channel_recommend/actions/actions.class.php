<?php
/**
 * theme actions.
 *
 * @package    epg
 * @subpackage theme
 * @author     superwen
 * @version    
 */

class channel_recommendActions extends sfActions
{  
    public function executeIndex(sfWebRequest $request) 
	{
		$this->parentTvStations = Doctrine::getTable('TvStation')->getParentArray();
		$this ->channel_id = $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
		$tvStation = Doctrine::getTable('TvStation')->findOneById($this->channel->getTvStationId());
        $this->tvStation_id = ($tvStation->getParentId() == 0 ? $tvStation->getId() : $tvStation->getParentId());
		$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($this->tvStation_id);
		$this->channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
        
        $title=trim($request->getParameter('title'));
        if($title!=''){
            $this->recommends = Doctrine::getTable('ChannelRecommend')->createQuery('a')->where('a.channel_code = ?', $this->channel->getCode())->andWhere('a.title like ?',"%$title%")->orderBy('sort')->execute(); 
        }else{
            $this->recommends = Doctrine::getTable('ChannelRecommend')->createQuery('a')->where('a.channel_code = ?', $this->channel->getCode())->orderBy('sort')->execute(); 
        }
    }	

	public function executeAdd(sfWebRequest $request) 
	{
        if($request->isMethod("POST")) {
    		$this ->channel_id = $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
    		$this ->tongbu_channel_id = $tongbu_channel_id = $request->getParameter('tongbu_channel_id','');
    		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
    		
    		$this->tongbu_channel = '';
    		if($this ->channel_id != $this ->tongbu_channel_id)
    		{
    			$this->tongbu_channel = Doctrine::getTable('Channel')->findOneById($tongbu_channel_id);
    		}
    		
            $isadd=true;
            if($request->getParameter('wiki_id')==''||$request->getParameter('title')==''){
                $isadd=false;
            }
            if($isadd){
        		$channelrecommend = new ChannelRecommend();
        		$channelrecommend -> setChannelCode($this->channel->getCode());
        		$channelrecommend -> setWikiId($request->getParameter('wiki_id'));
        		$channelrecommend -> setTitle($request->getParameter('title'));
        		$channelrecommend -> setPic($request->getParameter('pic'));
        		$channelrecommend -> setPlaytime($request->getParameter('playtime'));
        		$channelrecommend -> setRemark($request->getParameter('remark'));
        		$channelrecommend -> setSort(intval($request->getParameter('sort')));
        		if($this ->tongbu_channel != '')
        		{
	        		$tongbu_channelrecommend = new ChannelRecommend();
	        		$tongbu_channelrecommend -> setChannelCode($this->tongbu_channel->getCode());
	        		$tongbu_channelrecommend -> setWikiId($request->getParameter('wiki_id'));
	        		$tongbu_channelrecommend -> setTitle($request->getParameter('title'));
	        		$tongbu_channelrecommend -> setPic($request->getParameter('pic'));
	        		$tongbu_channelrecommend -> setPlaytime($request->getParameter('playtime'));
	        		$tongbu_channelrecommend -> setRemark($request->getParameter('remark'));
	        		$tongbu_channelrecommend -> setSort(intval($request->getParameter('sort')));   
        		}
        		if($channelrecommend -> save()==null) {
        			if($this ->tongbu_channel != '')
        			{
        				$tongbu_channelrecommend -> setTongbuId($channelrecommend->getId());
        				$tongbu_channelrecommend -> save();
        				$channelrecommend -> setTongbuId($tongbu_channelrecommend->getId());
        				$channelrecommend -> save();
        			}
        			$this->getUser()->setFlash("notice",'操作完成!');
        		} else {
        			$this->getUser()->setFlash("error",'操作失败，请重试!');
        		}  
                $this->redirect("channel_recommend/index?channel_id=".$this ->channel_id);  
            }else{
                $this->getUser()->setFlash("notice",'请输入完整信息!');
                $this->redirect("channel_recommend/add?channel_id=".$this ->channel_id); 
            }
        }else{
    		$this->parentTvStations = Doctrine::getTable('TvStation')->getParentArray();
            $this ->channel_id = $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
    		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
    		$tvStation = Doctrine::getTable('TvStation')->findOneById($this->channel->getTvStationId());
            $this->tvStation_id = ($tvStation->getParentId() == 0 ? $tvStation->getId() : $tvStation->getParentId());
    		$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($this->tvStation_id);
    		$this->channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
        }
    }	

	public function executeEdit(sfWebRequest $request) 
	{
        if($request->isMethod("POST")) {
    		$channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
    		
    		$this ->tongbu_channel_id = $tongbu_channel_id = $request->getParameter('tongbu_channel_id','');
            $this->tongbu_channel = '';
    		if($channel_id != $this ->tongbu_channel_id)
    		{
    			$this->tongbu_channel = Doctrine::getTable('Channel')->findOneById($tongbu_channel_id);
    		}
    		
    		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
            $id = $request->getParameter('id');
    		$channelrecommend = Doctrine::getTable('ChannelRecommend')->findOneById($id);
    		$channelrecommend -> setChannelCode($this->channel->getCode());
    		$channelrecommend -> setWikiId($request->getParameter('wiki_id'));
    		$channelrecommend -> setTitle($request->getParameter('title'));
    		$channelrecommend -> setPic($request->getParameter('pic'));
    		$channelrecommend -> setPlaytime($request->getParameter('playtime'));
    		$channelrecommend -> setRemark($request->getParameter('remark'));
    		$channelrecommend -> setSort(intval($request->getParameter('sort')));
    		$tongbu_id = $channelrecommend->getTongbuId();
    		if($this->tongbu_channel != '')//选择了要同步的频道
    		{
    			if($tongbu_id == 0)//判断主频道还没有关联同步频道的情况  生成一条新的关联频道
    			{
		    		$tongbu_channelrecommend = new ChannelRecommend();
		    		$tongbu_channelrecommend -> setChannelCode($this->tongbu_channel->getCode());
		    		$tongbu_channelrecommend -> setWikiId($request->getParameter('wiki_id'));
		    		$tongbu_channelrecommend -> setTitle($request->getParameter('title'));
		    		$tongbu_channelrecommend -> setPic($request->getParameter('pic'));
		    		$tongbu_channelrecommend -> setPlaytime($request->getParameter('playtime'));
		    		$tongbu_channelrecommend -> setRemark($request->getParameter('remark'));
		    		$tongbu_channelrecommend -> setSort(intval($request->getParameter('sort'))); 
		    		$tongbu_channelrecommend -> setTongbuId($id); //关联主频道
		    		$tongbu_channelrecommend -> save();
		    		
		    		$channelrecommend -> setTongbuId($tongbu_channelrecommend->getId()); //关联同步频道  				
    			}
    			else//关联了同步频道的情况
    			{
    				$tongbu_channelrecommend = Doctrine::getTable('ChannelRecommend')->findOneById($tongbu_id);
		    		$tongbu_channelrecommend -> setChannelCode($this->tongbu_channel->getCode());
		    		$tongbu_channelrecommend -> setWikiId($request->getParameter('wiki_id'));
		    		$tongbu_channelrecommend -> setTitle($request->getParameter('title'));
		    		$tongbu_channelrecommend -> setPic($request->getParameter('pic'));
		    		$tongbu_channelrecommend -> setPlaytime($request->getParameter('playtime'));
		    		$tongbu_channelrecommend -> setRemark($request->getParameter('remark'));
		    		$tongbu_channelrecommend -> setSort(intval($request->getParameter('sort')));
		    		$tongbu_channelrecommend -> save();     				
    			}
    		}
    		else//没有选择同步频道的情况
    		{
    		    if($tongbu_id == 0)//并且主频道里面也没关联过同步的频道  什么都不干
    			{
    			}
    			else//关联了同步频道的情况 所以要删除关联的同步频道 并且主频道的同步id字段清为0
    			{
    				$tongbu_channelrecommend = Doctrine::getTable('ChannelRecommend')->findOneById($tongbu_id);
    				$tongbu_channelrecommend ->delete();
    				
					$channelrecommend -> setTongbuId(0);    				
    			}    			
    		}
    		if($channelrecommend -> save()==null) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    		} else {
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    		}  
            $this->redirect("channel_recommend/index?channel_id=".$channel_id);  

        }else{
            $this->id = $request->getParameter('id');
            $this->recommend = Doctrine::getTable('ChannelRecommend')->findOneById($this->id);
    		$this->parentTvStations = Doctrine::getTable('TvStation')->getParentArray();
            $this ->channel_id = $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
    		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);
    		$tongbu_id = $this->recommend->getTongbuId();
    		$tongbu_channel_recommend = '';
    		$this->tongbu_channel_code = '';
    		$tongbu_channel_recommend = Doctrine::getTable('ChannelRecommend')->findOneById($tongbu_id);
    		if($tongbu_channel_recommend!='')
    			$this->tongbu_channel_code = $tongbu_channel_recommend->getChannelCode();
    		$tvStation = Doctrine::getTable('TvStation')->findOneById($this->channel->getTvStationId());
            $this->tvStation_id = ($tvStation->getParentId() == 0 ? $tvStation->getId() : $tvStation->getParentId());
    		$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($this->tvStation_id);
    		$this->channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
            
        }
    }
	public function executeUpdate(sfWebRequest $request)
	{
		$this ->channel_id = $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
		$this->channel = Doctrine::getTable('Channel')->findOneById($channel_id);

		$recommends  = $request->getParameter('recommend');
		if(count($recommends) > 0) {
			foreach($recommends as $key => $recommend) {
				$channelrecommend = Doctrine::getTable('ChannelRecommend')->findOneByID($key);
				$channelrecommend -> setWikiId($recommend['wiki_id']);
				$channelrecommend -> setTitle($recommend['title']);
				$channelrecommend -> setPic($recommend['pic']);
				$channelrecommend -> setPlaytime($recommend['palytime']);
				$channelrecommend -> setRemark($recommend['remark']);
				$channelrecommend -> setSort($recommend['sort']);
				$channelrecommend -> save();
			}
		}
		$this->redirect("channel_recommend/index?channel_id=".$this ->channel_id);
	}
    
	public function executeDelete(sfWebRequest $request) 
	{
        $id = $request->getParameter('id');
        $channel_id = $request->getParameter('channel_id', ( $this->getUser()->getAttribute('channel_id') ?  $this->getUser()->getAttribute('channel_id') : 1 ));
        $ok = Doctrine::getTable('ChannelRecommend')->findOneById($id)->delete();
        if($ok)
            $this->getUser()->setFlash("notice",'删除成功!');
        else
            $this->getUser()->setFlash("error",'删除失败!');    
        $this->redirect("channel_recommend/index?channel_id=".$channel_id);  
    }    
}