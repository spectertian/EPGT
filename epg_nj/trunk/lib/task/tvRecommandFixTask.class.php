<?php
/**
 *  @todo  :  定期获取运营中心推荐接口数据并存在固定推荐里
 *  @author:  lifucang 2013-08-15
 */
class tvRecommandFixTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'RecommandFix';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:RecommandFix|INFO] task does things.
Call it with:

  [php symfony tv:RecommandFix|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('RecommandFix');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $types = array("vod","Series","Movie","Sports","Entertainment","Cartoon","Culture","News");
        $user_id = "99666611230068607";
        $recommand_repo = $mongo->getRepository("RecommandFix");                  
        foreach($types as $type){
            $wikis = Recommand::getCenterVodPrograms($user_id,10,$type,'');
            if($wikis){
                $recommand_repo->remove(array('type'=>$type));
                foreach($wikis as $wiki){
                    //有海报的存储在固定点播推荐里
                    if(!strpos($wiki['poster'],'morenhaibao.gif')){
                        $recommandFix=new RecommandFix();
                        $recommandFix -> setType($type);
                        $recommandFix -> setTitle($wiki['Title']);
                        $recommandFix -> setPoster($wiki['poster']);
                        $recommandFix -> setUrl($wiki['url']);
                        $recommandFix -> save();
                    }
                }
            }
        } 
        echo date('Y-m-d H:i:s'),"\n";
        $content="finished";
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();  
    }
}
