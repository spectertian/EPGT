<?php
/**
 *  @todo  : 将program表的cpg_content_id从cpg表里匹配
 *  @author: lifucang
 */
class tvCpgToProgramTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date')  
    ));

    $this->namespace        = 'tv';
    $this->name             = 'CpgToProgram';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:CpgToProgram|INFO] task does things.
Call it with:

  [php symfony tv:CpgToProgram|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo     = $this->getMondongo();
        $program_rep   = $mongo->getRepository("Program");
        $cpg_rep   = $mongo->getRepository('Cpg');
        if (isset($options['date'])) {
            $date=$options['date'];
        }else{
            $date=date("Y-m-d");
        }
        $cpgs=$cpg_rep->find(array('query'=>array('date'=>$date)));
        $contentNum=0;
        $contentNum1=0;
        echo count($cpgs),"\n";
        //$i=0;
        foreach($cpgs as $cpg){
            $startTime=new MongoDate($cpg->getStartTime()->getTimestamp());
            $endTime=new MongoDate($cpg->getEndTime()->getTimestamp());;
            $program=$program_rep->findOne(array('query'=>array('channel_code'=>$cpg->getChannelCode(),'start_time'=>$startTime)));
            if($program){
                $program->setCpgContentId($cpg->getContentId());
                $program->save();
                $contentNum++;
            }else{
                $program1=$program_rep->findOne(array('query'=>array('channel_code'=>$cpg->getChannelCode(),'end_time'=>$endTime)));
                if($program1){
                    $program1->setCpgContentId($cpg->getContentId());
                    $program1->save();
                    $contentNum1++;
                }
            }
            /*
            if($i%100==0){
                echo $i,"\n";
            }
            $i++;
            */
        }
        echo date("Y-m-d H:i:s"),'------',"contentNum:",$contentNum,"contentNum1:",$contentNum1,"\n";
    }
}
