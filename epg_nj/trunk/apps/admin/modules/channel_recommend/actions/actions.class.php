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
        $this->types = array('cctv'=>'央视','tv'=>'卫视','local'=>'本地','hd'=>'高清','pay'=>'付费');
        $this->type = $request->getParameter('type','cctv');
        $this->channelCode = $request->getParameter('code','cctv1');
        $this->recommends = Doctrine::getTable('ChannelRecommend')->createQuery()->where('channel_code = ?', $this->channelCode)->orderBy('sort')->execute(); 
    }

	public function executeAdd(sfWebRequest $request) 
	{
        if($request->isMethod("POST")) {
            $channelCode = $request->getParameter('code','cctv1');
            $type = $request->getParameter('type','cctv');
            $id = $request->getParameter('id');
            $isadd=true;
            if($request->getParameter('wiki_id')==''||$request->getParameter('title')==''){
                $isadd=false;
            }
            if($isadd){
        		$channelrecommend = new ChannelRecommend();
        		$channelrecommend -> setChannelCode($channelCode);
        		$channelrecommend -> setWikiId($request->getParameter('wiki_id'));
        		$channelrecommend -> setTitle($request->getParameter('title'));
        		$channelrecommend -> setPic($request->getParameter('pic'));
        		$channelrecommend -> setPlaytime($request->getParameter('playtime'));
        		$channelrecommend -> setRemark($request->getParameter('remark'));
        		$channelrecommend -> setSort(intval($request->getParameter('sort')));
        		if($channelrecommend -> save()==null) {
        			$this->getUser()->setFlash("notice",'操作完成!');
        		} else {
        			$this->getUser()->setFlash("error",'操作失败，请重试!');
        		}  
                $this->redirect("channel_recommend/index?type=".$type."&code=".$channelCode);  
            }else{
                $this->getUser()->setFlash("notice",'请输入完整信息!');
                $this->redirect($request->getReferer()); 
            }
        }else{
            $this->types = array('cctv'=>'央视','tv'=>'卫视','local'=>'本地','hd'=>'高清','pay'=>'付费');
            $this->type = $request->getParameter('type','cctv');
            $this->channelCode = $request->getParameter('code','cctv1');
        }
    }	

	public function executeEdit(sfWebRequest $request) 
	{
        if($request->isMethod("POST")) {
            $channelCode = $request->getParameter('code','cctv1');
            $type = $request->getParameter('type','cctv');
            $id = $request->getParameter('id');
    		$channelrecommend = Doctrine::getTable('ChannelRecommend')->findOneById($id);
    		$channelrecommend -> setChannelCode($channelCode);
    		$channelrecommend -> setWikiId($request->getParameter('wiki_id'));
    		$channelrecommend -> setTitle($request->getParameter('title'));
    		$channelrecommend -> setPic($request->getParameter('pic'));
    		$channelrecommend -> setPlaytime($request->getParameter('playtime'));
    		$channelrecommend -> setRemark($request->getParameter('remark'));
    		$channelrecommend -> setSort(intval($request->getParameter('sort')));
    		if($channelrecommend -> save()==null) {
    			$this->getUser()->setFlash("notice",'操作完成!');
    		} else {
    			$this->getUser()->setFlash("error",'操作失败，请重试!');
    		}  
            $this->redirect("channel_recommend/index?type=".$type."&code=".$channelCode);  

        }else{
            $this->id = $request->getParameter('id');
            $this->types = array('cctv'=>'央视','tv'=>'卫视','local'=>'本地','hd'=>'高清','pay'=>'付费');
            $this->type = $request->getParameter('type','cctv');
            $this->channelCode = $request->getParameter('code','cctv1');
            $this->recommend = Doctrine::getTable('ChannelRecommend')->findOneById($this->id);
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
        $ok = Doctrine::getTable('ChannelRecommend')->findOneById($id)->delete();
        if($ok)
            $this->getUser()->setFlash("notice",'删除成功!');
        else
            $this->getUser()->setFlash("error",'删除失败!');    
        $this->redirect($request->getReferer());
    }    
}