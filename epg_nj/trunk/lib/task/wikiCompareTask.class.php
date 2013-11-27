<?php
/**
 *  @todo  : 南京wiki库和北京wiki库对比，没有的删除
 *  @author: lifucang
 */
class wikiCompareTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'wikiCompare';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [wikiCompare|INFO] task does things.
Call it with:

  [php symfony wikiCompare|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	    set_time_limit(0); 
        $arr_wikis=array(
            '4d007dfc2f2a241bd70003b2'=>'大侠沈胜衣',
            '4d007dff2f2a241bd7000406'=>'青红',
            '4d007e0f2f2a241bd70005a5'=>'野蛮师姐',
            '4d007e0f2f2a241bd70005b7'=>'重案组',
            '4d007e2b2f2a241bd70008f9'=>'洛城机密',
            '4d007e3a2f2a241bd7000abb'=>'杀死比尔',
            '4d007e722f2a241bd7001049'=>'方世玉与洪熙官',
            '4d007e912f2a241bd7001367'=>'这个杀手不太冷',
            '4d007e972f2a241bd700140f'=>'虎口脱险',
            '4d007ea82f2a241bd700162a'=>'美人鱼',
            '4d007ebb2f2a241bd70019a6'=>'魔法警察',
            '4d007f132f2a241bd7002a0f'=>'特工狂花',
            '4d007f2c2f2a241bd7002e16'=>'刺客战场',
            '4d007f812f2a241bd7003a87'=>'银翼杀手',
            '4d0080d22f2a241bd7005fa7'=>'巴斯克维尔猎犬',
            '4d00812b2f2a241bd70067f4'=>'穆斯林的葬礼',
            //'4d0082002f2a241bd7007977'=>'罗宾汉',
            '4d0082dc2f2a241bd70089bc'=>'花田错',
            '4d0083392f2a241bd7008fe9'=>'命运的捉弄',
            '4d00840b2f2a241bd7009dea'=>'暗夜危情',
            '4d0084502f2a241bd700a262'=>'二见钟情',
            //'4d0085822f2a241bd700b37e'=>'三角洲部队闹剧',
            '4d00859c2f2a241bd700b480'=>'亚洲警察之高压线',
            '4d0086a12f2a241bd700bee1'=>'鼠祸3-围攻巴黎',
            '4d0086ff2f2a241bd700c2ae'=>'女拳霸',
            '4d00874d2f2a241bd700c598'=>'马克思·佩恩',
            '4d00875c2f2a241bd700c616'=>'超速绯闻',
            '4d0087ad2f2a241bd700c8e0'=>'滑稽人物',
            //'4d0087dc2f2a241bd700ca96'=>'分手信',
            '4d0087eb2f2a241bd700cb0f'=>'记住我',
            '4d00882d2f2a241bd700cd89'=>'决杀令',
            '4d0088392f2a241bd700cdfc'=>'魔法保姆麦克菲2',        
        );
        /*
        require_once '/usr/local/xunsearch/sdk/php/lib/XS.php';
        $xs = new XS('epg_wiki');
        $index = $xs->index; 
        foreach($arr_wikis as $key=>$value){
            $index->del($key);
        }
        echo "已成功删除训搜索引!";
        exit;
        */
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
        //$wikiTmp_repo = $mongo->getRepository("WikiTemp");
        $video_repo = $mongo->getRepository("Video");  
        $videoPlaylist_repo = $mongo->getRepository("VideoPlaylist");  
        $import_repo = $mongo->getRepository("ContentImport");  
        
        $video_num=0;
        $playlist_num=0;
        $import_num=0;
        $num=0;
        foreach($arr_wikis as $wiki_id=>$title){
            $wikifind=$wiki_repo->findOne(array("query"=>array('title'=>$title,'_id'=>array('$ne'=>new MongoId($wiki_id)))));
            if($wikifind){
                //echo (string)$wikifind->getId(),'---',iconv('utf-8','gbk',$wikifind->getTitle()),'---',$wikifind->getModel(),"\n";
                $wikiId=(string)$wikifind->getId();
                //替换video中的相关wiki
                $videos=$video_repo->find(array('query'=>array('wiki_id'=>$wiki_id)));
                foreach($videos as $video){
                    $video->setWikiId($wikiId);
                    $video->save();
                    $video_num++;
                }
                //替换videoPlayList中的相关wiki
                $videoPlayLists=$videoPlaylist_repo->find(array('query'=>array('wiki_id'=>$wiki_id)));
                foreach($videoPlayLists as $videoPlayList){
                    $videoPlayList->setWikiId($wikiId);
                    $videoPlayList->save();
                    $playlist_num++;
                }
                //替换content_import中的相关wiki
                $imports=$import_repo->find(array('query'=>array('wiki_id'=>$wiki_id)));
                foreach($imports as $import){
                    $import->setWikiId($wikiId);
                    $import->save();
                    $import_num++;
                }
                //删除wiki
                $wiki_repo->remove(array("_id"=>new MongoId($wiki_id)));
                $num++;
            }
        }
        echo $video_num,"\n";
        echo $playlist_num,"\n";
        echo $import_num,"\n";
        echo $num,"\n";
        /*
        $query=array();
        $wiki_count = $wiki_repo->count();
        $i = 0;
        sleep(1);
        $arr_title=array();
        $arr_del=array();
        while ($i < $wiki_count) 
        {
            $wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 300));
            foreach ($wikis as $wiki) 
            {
                $id=$wiki->getId();
                $wiki_id=(string)$id;
                $title=$wiki->getTitle();
                $wikifind=$wikiTmp_repo->findOne(array("query"=>array('_id'=>$id)));
                if(!$wikifind){
                    if($wiki->getHasVideo()>0){
                        $arr_title[]="'".$wiki_id."'=>'".$title."',";
                    }else{
                        $arr_del[]=$title;
                        $wiki->delete();
                    }
                }
            }
            $i = $i + 300;
            //echo $i,"\n";
            sleep(1);
        }

        //$arr_title=array_unique($arr_title);
        echo "has video:",count($arr_title),"\n";
        sleep(2);
        foreach($arr_title as $value){
            echo iconv('utf-8','gbk',$value),"\n";
        }
        
        echo "del:",count($arr_del),"\n";
        sleep(2);
        foreach($arr_del as $value){
            echo iconv('utf-8','gbk',$value),"\n";
        }
        */
  }
}
