<?php

/**
 * Video document.
 */
class Video extends \BaseVideo
{
    public $wiki = null;
    //用于不自动插入创建时间
    protected $auto_insert_createtime = true;
    public function getWiki() {
        $wiki_id = $this->getWikiId();
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('wiki');
        $this->wiki = $repository->findOneById(new MongoId($wiki_id));
        return $this->wiki;
    }

    public function getPublishImgSrc() {
        sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');
        
        return ($this->getPublish()) ? image_path('icon/publish_g.png') : image_path('icon/publish_x.png');
    }

    /**
     * ???????? wikiMeta 
     * @return <type>
     * @author luren
     */
    public function getWikiMeta() {
        $metaId = $this->getWikiMataId();
        if ($metaId) {
            $mongo = $this->getMondongo();
            $wikiMetaRes = $mongo->getRepository('WikiMeta');
            return $wikiMetaRes->findOneById(new MongoId());
        }
        
        return false;
    }

    /**
     * 显示来源中文名称
     * @return string
     * @author luren
     */
    public function  getRefererZhcn($state = null) {
        $referers = array(
                    'qiyi'          =>  '奇艺',
                    'youku'         =>  '优酷',
                    'sina'          =>  '新浪',
                    'sohu'          =>  '搜狐',
                    'tps'           =>  'tps',
        			'baidu_qiyi'    =>  '百度-奇艺',
                    'baidu_youku'   =>  '百度-优酷',
                    'baidu_sina'    =>  '百度-新浪',
                    'baidu_sohu'    =>  '百度-搜狐',
                    'baidu_pptv'    =>  '百度-PPTV',
                    'baidu_pps'     =>  '百度-PPS',
                    'baidu_letv'    =>  '百度-乐视',
                    'baidu_tudou'   =>  '百度-土豆',
        			'baidu_tencent' =>  '百度-腾讯',
                    'cntv'          =>  'CNTV',
                    'wasu'          =>  'WASU',
                );
        if($state){
            return $referers;
        }
        return $referers[$this->getReferer()];
    }

    /**
     * 获取播放 url 地址
     * @return <type>
     * @author luren
     */
    public function getPlayUrl() {
        $config = $this->getConfig();
        return isset($config['url']) ? $config['url'] : $this->getUrl();
    }
    /**
     * 数据保存后事件，包括插入与新增
     * 视频新增事件： 一、做到新增完 视频数量加1 二、source字段值重置
     * @author wangnan
     */
    public function postSave() 
    {
        $wiki_id = $this->getWikiId();
        if (24 == strlen($wiki_id)) 
        {
            $mongo = $this->getMondongo();
            $wikiRepos = $mongo->getRepository('Wiki');
            $wiki = $wikiRepos->findOneById(new MongoId($wiki_id));
			unset($wiki_id);
            if ($wiki) 
			{
				$model = $wiki->getModel();
				if ($model == 'film') 
				{				
					$videos = $wiki->getVideos();
					if($videos != null)
					{
						$wiki->setHasVideo(count($videos));
						foreach($videos as $video)
						{
							$sources[] = $video->getReferer();
							$sources = array_unique($sources);
						}
						$wiki->setSource($sources);
					}
					else
					{
						$wiki->setHasVideo(0);
						$wiki->setSource(array());
					}
					unset($videos);
	                $wiki->save();
	                unset($wiki);
				}
				if ($model == 'teleplay'||$model == 'television') 
				{
					$num = 0;
					$playLists = $wiki->getPlayList();
					if ($playLists != NULL) 
					{
						foreach ($playLists as $playList) 
						{
                            $num += $playList->countVideo();
                            $sources[] = $playList->getReferer();
							$sources = array_unique($sources);
						}
						$wiki->setHasVideo($num);
						$wiki->setSource($sources);
					}
					unset($playLists);
					$wiki->save();
					unset($wiki);
				}				
            }
        }
        
        return true;
    }
    /**
     * 视频删除事件： 一、做到删除完 视频数量减1 二、source字段值重置
     * @return <type>
     * @author luren
     * @editor wangnan
     */
    public function postDelete() {
        $wiki_id = $this->getWikiId();
        if (24 == strlen($wiki_id)) 
        {
            $mongo = $this->getMondongo();
            $wikiRepos = $mongo->getRepository('Wiki');
            $wiki = $wikiRepos->findOneById(new MongoId($wiki_id));

            if ($wiki) 
			{
				$model = $wiki->getModel();
				if ($model == 'film') 
				{				
					$videos = $wiki->getVideos();
					if($videos != null)
					{
						$wiki->setHasVideo(count($videos));
						foreach($videos as $video)
						{
							$sources[] = $video->getReferer();
							$sources = array_unique($sources);
						}
						$wiki->setSource($sources);
					}
					else
					{
						$wiki->setHasVideo(0);
						$wiki->setSource(array());
					}
	                $wiki->save();
				}
				if ($model == 'teleplay'||$model == 'television') 
				{
					$num = 0;
					$playLists = $wiki->getPlayList();
					if ($playLists != NULL) 
					{
						foreach ($playLists as $playList) 
						{
                            $num += $playList->countVideo();
                            $sources[] = $playList->getReferer();
							$sources = array_unique($sources);
						}
						$wiki->setHasVideo($num);
						$wiki->setSource($sources);
					}
					$wiki->save();
				}				
            }
        }
        
        return true;
    }
    /**
     * 用于不自动插入创建时间
     * @param unknown_type $value
     * @author wn
     */
    public function setAutoCreate($value)
    {
    	if(isset($value)&&$value===false)
    		$this->auto_insert_createtime = false;
    }
    /**
     * 用于不自动插入更新时间
     * @param unknown_type $value
     * @author wn
     */    
    public function preInsertExtensions()
    {
    	if($this->auto_insert_createtime)
        	$this->updateTimestampableCreated();

    }     
}