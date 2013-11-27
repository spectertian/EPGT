<?php
/**
 *  @todo  : 根据video表更新Wiki中的has_video值
 *  @author: lifucang
 */
class tvVideoWikiUpdateTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'VideoWikiUpdate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:VideoWikiUpdate|INFO] task does things.
Call it with:

  [php symfony tv:VideoWikiUpdate|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo(); 
        $wiki_repo = $mongo->getRepository("Wiki");  
        $wiki_count = $wiki_repo->count();
        echo $wiki_count,"\n";
        sleep(1);
        $video_num=0;   
        $i=0;     
        while ($i < $wiki_count) 
        {
            $wikis=$wiki_repo->find(array("query"=>array(),"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach($wikis as $wiki){
                $has_video=$wiki->getVideoCount();
                $wiki->setHasVideo($has_video);
                $wiki->save();
                if($has_video>0)
                    $video_num++;
            } 
            $i = $i + 200;
            echo $i,'*****',"\n";
            sleep(1); 
        }        
        echo "has_video > 0:$video_num, finished!\n";
    }
}
