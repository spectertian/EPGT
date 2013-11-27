<?php
/**
 *  @todo  : 查找现有wiki中没有海报的，然后从symfony tv:AttachmentsCopy重新获取
 *  @author: lifucang
 *  @update: 2013-01-25
 */
class tvGetWikisNoCoverTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date'),
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_OPTIONAL, 'type'),
      new sfCommandOption('screens', null, sfCommandOption::PARAMETER_OPTIONAL, 'screens',false),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetWikisNoCover';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetWikisNoCover|INFO] task does things.
Call it with:

  [php symfony tv:GetWikisNoCover|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetWikisNoCover');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $storage = StorageService::get('photo');
        $type = $options['type']?$options['type']:'wiki';  //取值：wiki 或者 program
        $getscreens = $options['screens']?$options['screens']:false;  //取值：wiki 或者 program
        $numall=0;
        if($type=='wiki'){
            $wiki_repo = $mongo->getRepository("Wiki"); 
            $query = array('cover'=>array('$exists'=>true));
            $wiki_count = $wiki_repo->count($query);
            $i = 0;
            echo "count:",$wiki_count,"\n";
            sleep(1);
            while ($i < $wiki_count) {
                $wikis = $wiki_repo->find(array('query'=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 50));
                foreach ($wikis as $wiki) {
                    $cover=$wiki->getCover();
                    $content = $storage->get($cover);
                    if(!$content){
                        echo iconv('utf-8','gbk',$wiki->getTitle()),"\n";
                        if($getscreens){
                            $screens = $wiki->getScreenshots();
                            if($screens){
                                $filekeys = $cover.','.implode(',',$screens);
                            }else{
                                $filekeys = $cover;
                            }
                        }else{
                            $filekeys = $cover;
                        }
                        echo $filekeys,"\n";
                        //开始加入队列
                        $filekeyArr = explode(',',$filekeys);
                        foreach($filekeyArr as $filekey){
                            $this->attachmentCopySqs($filekey);
                        }
                        //结束加入队列
                        //exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --file_key=".$filekeys." --need_examine=no");
                    }
                }
                $i = $i + 50;
                echo $i,'*************************************',"\n";
                sleep(1);
            }
            $numall=$wiki_count;
        }else{
            $programRes = $mongo->getRepository('program');
            $date = $options['date']?$options['date']:date("Y-m-d");
            $coverKeys=array();
                  
            $query = array('date'=>$date,'wiki_id'=>array('$exists'=>true));        
            $programs=$programRes->find(array('query'=>$query));
            echo $date,iconv('utf-8','gbk','开始统计没有海报的节目数量，请耐心等待！'),"\n";
            foreach($programs as $program){
                $wiki = $program->getWiki();
                if($wiki){
                    $wikiCover = $wiki->getCover();
                    $content = $storage->get($wikiCover);
                    if(!$content){
                        $key = $wiki->getTitle();
                        $coverKeys[$key]=$wikiCover;
                    }
                }
            }
            //去除重复的key值
            $coverKeys=array_unique($coverKeys);
            echo "count:",count($coverKeys),"\n";
            sleep(2);
            $i=0;
            foreach($coverKeys as $key=>$coverKey){
                echo iconv('utf-8','gbk',$key),'---',$coverKey,"\n";
                $this->attachmentCopySqs($coverKey);
                //exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:AttachmentsCopy --file_key=".$coverKey." --need_examine=no");
                $i++;
                if($i%5==0) sleep(1);
            } 
            $numall=count($coverKeys);
        }
        echo "------finished!\n";
        $content="Type:$type---num:".$numall;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save(); 
    }
    private function attachmentCopySqs($file_key) {
        $httpsqs = HttpsqsService::get();  
        $arr = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array(
                       "file_key" => $file_key,
                       "need_examine" => false
                   ));
        return $httpsqs->put("epg_queue",json_encode($arr));
    } 
}
