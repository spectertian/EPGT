<?php

/**
 * Program document.
 */
class Program extends \BaseProgram
{
    protected $wiki = null;
    protected $channel = null;
    
    public function getPublishImgSrc()
    {
        sfContext::getInstance()->getConfiguration()->loadHelpers('Asset');
        $publish = $this->getPublish();
        if($publish == 1)
        {
            return image_path('accept.png');
        }else{
            return image_path('delete.png');
        }
    }

    /**
     * 获取节目播放状态
     * @return <string>
     * @author pjl
     */
    public function getPlayStatus() {
        $now = new DateTime();

        $start_time = $this->getStartTime();
        $end_time = $this->getEndTime();

        if($start_time < $now && $end_time > $now) {
            return 'playing';
        } elseif($start_time > $now) {
            return 'unplay';
        } else {
            return 'played';
        }
    }

    /**
     * 获取第一个tag名称
     * @return <string>
     * @author pjl
     */
    public function getFirstTag() {
        $first_tag = '';
        $tags = $this->getTags();

        if($tags) {
            $first_tag = array_shift($tags);
        }

        return $first_tag;
    }

    /**
     * 获取关联的wiki对象
     * @return <obj>
     * @author pjl
     */
    public function getWiki() {
        if (!isset($this->wiki)) {
            $wiki_id = $this->getWikiId();
            if($wiki_id) {
                $mondongo = $this->getMondongo();
                $wiki_repository = $mondongo->getRepository('Wiki');
                $this->wiki = $wiki_repository->getWikiById($wiki_id);
            }
        }
        
        return $this->wiki;
    }

    /**
     * 获取wiki标题
     * @return <string>
     * @author pjl
     */
    public function getWikiTitle() {
        $wiki_title = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wiki_title = $wiki->getTitle();
        }

        return $wiki_title;
    }
    /**
     * 获取Slug标题
     * @return <string>
     * @author wn
     */
    public function getWikiSlug() {
        $wiki_slug = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wiki_slug = $wiki->getSlug();
        }

        return $wiki_slug;
    }
    /**
     * 获取wiki封面图片
     * @return <string>
     * @author pjl
     */
    public function getWikiCoverUrl() {
        $wiki_url = '';
        $wiki = $this->getWiki();
        if($wiki) {
            $wiki_url = $wiki->getCoverUrl();
        }

        return $wiki_url;
    }

    /**
     * 获取关联的频道
     * @return <obj>
     * @author pjl
     */
    public function getChannel() {   
        if (! $this->channel) {
            $channel_code = $this->getChannelCode();
            $this->channel = Doctrine::getTable('Channel')->findOneByCode($channel_code);
        }
        return $this->channel;
    }

    /**
     * 获取频道名称
     * @return <string>
     * @author pjl
     */
    public function getChannelName() {
        $channel = $this->getChannel();

        $channel_name = '';
        if($channel) {
            $channel_name = $channel->getName();
        }
        return $channel_name;
    }
    /**
     * 获取频道hot
     * @return <string>
     * @author pjl
     */
    public function getChannelHot() {
        $channel = $this->getChannel();

        $channel_hot = '';
        if($channel) {
            $channel_hot = $channel->getHot();
        }
        return $channel_hot;
    }

    /**
     * 获取频道图片
     * @return <string>
     * @author pjl
     */
    public function getChannelLogo() {
        $channel = $this->getChannel();

        $logo = '';
        if($channel) {
            $logo = $channel->getLogo();
        }
        return $logo;
    }

    public function getProgress() {
        $start_time = $this->getStartTime()->getTimestamp();
        $end_time = $this->getEndTime()->getTimestamp();

        $all_time = $end_time - $start_time;
        $played_time = $end_time - time();

        return number_format(($played_time/$all_time) * 100);
    }

    /**
     * 获取当前节目剩余播出时间，仅直播中节目可用
     * @return string
     * @author zhigang
     */
    public function getLiftTime() {
        $start_time = $this->getStartTime();
        $end_time = $this->getEndTime();

        $date_diff = $start_time->diff($end_time);
        $format = array();
        if ($date_diff->y !== 0) {
            $format[] = "%y年";
        }
        if ($date_diff->m !== 0) {
            $format[] = "%m月";
        }
        if ($date_diff->d !== 0) {
            $format[] = "%d日";
        }
        if ($date_diff->h !== 0) {
            $format[] = "%h小时";
        }
        if ($date_diff->i !== 0) {
            $format[] = "%i分钟";
        }

        return $date_diff->format(implode("", $format));
    }

    /**
     * 获取节目播出星期中文名
     * @param prefix string
     * @return string
     * @author zhigang
     * @author 2010-1-14 增加前缀参数 pjl
     */
    public function getWeekChineseName($prefix = '周') {
        $chinese_weeks = array('日','一','二','三','四','五','六');
        $date = $this->getDate();
        $week_index = date('w', strtotime($date));
        return $prefix . $chinese_weeks[$week_index];
    }

    /**
     * 更新或插入 Xapian 全文搜索引擎索引
     * @author luren
     */
    public function updateXapianDocument() {
        $xapian_database = SearchEngine::getWritableDatabase('program');
        $xapian_document = new XapianDocument();
        $id = $this->getId();
        $name = $this->getName();

        //应用 scws 分词库
        $scws = scws_new();
        $scws->set_charset('utf8');
        $scws->send_text($name);
        $name_words = $scws->get_words('~un');
        foreach ($name_words as $word) {
            $xapian_document->add_term("Z".$word['word']);
//            $xapian_document->add_term('Z'.$word['word']);
        }

        $data = array("id" => (string) $id, "name" => $name);
        $xapian_document->set_data(json_encode($data));
        $xapian_database->replace_document("Q".$id, $xapian_document);
    }
}
