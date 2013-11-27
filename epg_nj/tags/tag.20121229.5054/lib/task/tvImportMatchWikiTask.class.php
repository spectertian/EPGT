<?php
/**
 *  @todo  : 将content_import中匹配好wiki的进行排查，看是否存在，不存在重新匹配（该计划任务用于重新导入wiki后执行）
 *  @author: lifucang
 */
class tvImportMatchWikiTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ImportMatchWiki';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ImportMatchWiki|INFO] task does things.
Call it with:

  [php symfony tv:ImportMatchWiki|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentImport");
        $wiki_repo = $mongo->getRepository("Wiki");
        
        $query=array('wiki_id'=>array('$exists'=>true));
        $import_count = $import_repo->count($query);
        $i = 0;
        $errnum=0;
        $matchnum=0;
        $notmatchnum=0;
        echo "count:",$import_count,"\n";
        sleep(1);
        while ($i < $import_count) 
        {
            $imports = $import_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($imports as $import) 
            {
                $wiki_id=$import->getWikiId();
                $wiki=$wiki_repo->findOneById(new MongoId($wiki_id));
                if(!$wiki){
                    //重新匹配wiki
                    $title = $this->getSubTitle($import->getFromTitle());
                    $wiki1 = $wiki_repo->getWikiByTitle($title);
                    if($wiki1){
                        $import->setWikiId((string)$wiki1->getId());
                        $import->save();
                        $matchnum++;
                    }else{
                        $import->setWikiId(null);
                        $import->save();
                        $notmatchnum++;
                    }
                    $errnum++;
                }
            }
            $i = $i + 200;
            echo $i,"\n";
            sleep(1);
        }
        echo "finished; ------errnum:",$errnum,"------matchnum:",$matchnum,"------notmatchnum:",$notmatchnum,"\n";
    }
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str){
        //替换
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/剧场/',
                          '/第.*集/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
        $str = preg_replace($patterns, "", $str);
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/','/舌尖上的中国.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行','舌尖上的中国');
        $str = preg_replace($patterns, $repatt, $str);
        return $str;
    }    
}
