<?php

/**
 * Video document.
 */
class Video extends \BaseVideo
{
    public $wiki = null;

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
    public function  getRefererZhcn() {
        $referers = array(
                    'qiyi' => '奇艺',
                    'youku' => '优酷',
                    'sina' => '新浪',
                    'sohu' => '搜狐',
        			'tps'=>'tps',
                );

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
				if ($model == 'teleplay') 
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
				if ($model == 'teleplay') 
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
}