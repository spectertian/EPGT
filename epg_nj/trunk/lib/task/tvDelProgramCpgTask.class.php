<?php
/**
 *  @todo  : 删除一个月之前的节目数据和cpg数据
 *  @author: lifucang  2013-05-28
 */
class tvDelProgramCpgTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'DelProgramCpg';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:DelProgramCpg|INFO] task does things.
Call it with:

  [php symfony tv:DelProgramCpg|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        set_time_limit(0);
        $this->connectMaster($options);
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('DelProgramCpg');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
        $program_repository = $mongo->getRepository('program');
        $programWeekRepository = $mongo->getRepository('programWeek');
        $cpg_repository = $mongo->getRepository('cpg');
        $query=array(
            'date' => array('$lt'=>date("Y-m-d",strtotime("-31 days")))
        );
        $program_repository->remove($query);
        echo "program id delete!\r\n";  
        sleep(2);
        $programWeekRepository->remove($query);
        echo "programWeek id delete!\r\n";  
        sleep(2);
        $cpg_repository->remove($query);
        echo "cpg id delete!\r\n";  
        echo date("Y-m-d H:i:s"),"------finished!\r\n";  
        
        $content="finished";
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
  }
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }    
}
