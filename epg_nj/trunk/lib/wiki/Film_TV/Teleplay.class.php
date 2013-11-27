<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 电视剧
 *
 * @author ward
 */
class Wiki_FilmTV_Teleplay extends Wiki_FilmTV_Abstract {
    protected $model_name = "teleplay";
    protected $model_show_name = "电视剧";
    protected $model_form = "WikiTeleplayForm";
    
    protected $wTeleplayFileds = array('drama' => 'raw', 'episodes' =>'string');

    protected $types = array('电视剧', '战争片', '喜剧片');

    public function  __construct() {
        //初始化动态字段
        $this->fieldsMerge($this->wTeleplayFileds);

        parent::__construct();
    }

    public function  fieldsToMongo($fields) {
        //数组属性类型转换php => mongo
        $fields = $this->arrayConversionPHPtoMongo($this->wTeleplayFileds, $fields);
        return parent::fieldsToMongo($fields);
    }

    public function  setDocumentData($data) {
        //数组属性类型转换 mongo => php
        $this->arrayConversionMongoToPHP($this->wTeleplayFileds, $data);
        parent::setDocumentData($data);
    }

    public function getTypes($join = "") {
        $tags = $this->getTags();

        $intersect = array_intersect($tags, $this->types);

        if($join) {
            $intersect = implode($intersect, $join);
        }

        return $intersect;
    }

    /**
     * 获取本周的电视剧相关节目单
     * @author luren
     */
    public function getWeekRelatedPrograms() {
        $relatedPrograms = array();
        $week_num = (0 == date('N')) ? 7 : date('N');
        $date_from = date('Y-m-d', strtotime('-' . ($week_num - 1) . ' day'));
        $date_end = date('Y-m-d', strtotime('+' . (7 - $week_num) . ' day'));
        $mongo = $this->getMondongo();
        $program_repository = $mongo->getRepository('Program');
        $programs = $program_repository->getCustomDateProgramByWikiId((string) $this->getId(), $date_from, $date_end);
        
        if($programs) {
          foreach($programs as $program) {
              $relatedPrograms[$program->getDate()][] = $program;
          }
        }
        return $relatedPrograms;
    }

    public function getVideoByMark($mark) {

    }
    /**
     * 获取播放列表
     * @return <type>
     * @author luren
     */
    public function getVideos($referer = '') {
        $mongo = $this->getMondongo();
        $playlist_repos = $mongo->getRepository('VideoPlayList');
        return $playlist_repos->getVideosByWikiId((string) $this->getId(),$referer);
    }

    /**
     * 根据 wiki_id && mark 获取所有视频
     * @return <type>
     * @author luren
     */
    public function getVideosByMark($mark) {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        return $video_repos->find(array(
                                   'query' => array(
                                        'wiki_id' => (string) $this->getId(),
                                        'mark' => $mark
                                    )
                                )
                            );
    }
    /**
     * 增加一个冗余方法获取视频播放列表
     * @return <type>
     * @author luren
     */
    public function getPlayList($referer = '') {
        return $this->getVideos($referer);
    }
    /**
     * 获取视频
     * @return <type>
     * @author lifucang 2013-06-30
     */
    public function getVideosByWikiId($referer=null,$hd=null) {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        return $video_repos->getVideosByWikiId((string) $this->getId(),$referer,$hd);
    }     
    /**
     * 统计视频数量
     * @return <type>
     * @author lifucang 2013-06-30
     */
    public function countVideosByWikiId($referer=null,$hd=null) {
        $mongo = $this->getMondongo();
        $video_repos = $mongo->getRepository('Video');
        return $video_repos->countVideosByWikiId((string) $this->getId(),$referer,$hd);
    }  
}
