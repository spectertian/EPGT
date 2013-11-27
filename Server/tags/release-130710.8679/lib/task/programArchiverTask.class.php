<?php
/**
 * 将过期节目备份到ProgramArchiver
 * @author wn
 *
 */
class programArchiverTask extends sfMondongoTask
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
        $this->name             = 'programArchiver';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [programArchiver|INFO] task does things.
Call it with:
    [php symfony programArchiver|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        //一周前
        $per_week_sun = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-7, date("Y")));
        $date = "1970-01-01";
        $mongo = $this->getMondongo();
        $program_repository = $mongo->getRepository('Program');
        $programArchiver_repository = $mongo->getRepository('ProgramArchiver');
        
        while ($programs = $program_repository->getNeedPrograms($per_week_sun,20)){
            foreach ($programs as $key=>$program){
                $is_exit = $programArchiver_repository->getProframArchiver($program->getDate(),$program->getTime(),$program->getChannelCode());
                if(!$is_exit){
                    if($date != $program->getDate()){
                        $date = $program->getDate();
                        echo $date."\n";
                    }
                    $programArchiver = new ProgramArchiver();
                    $programArchiver->setName($program->getName());
                    $programArchiver->setChannelCode($program->getChannelCode());
                    $programArchiver->setStartTime($program->getStartTime());
                    if($program->getTags()){
                    	$programArchiver->setTags($program->getTags());
                    }
                    $programArchiver->setDate($program->getDate());
                    $programArchiver->setPublish($program->getPublish());
                    $programArchiver->setTime($program->getTime());
                    $programArchiver->setCreatedAt($program->getCreatedAt());
                    $programArchiver->save();
                
                }
                $program->delete();
            }
        }
        // 删除24小时前的数据
       $TransferStatistics = $mongo->getRepository('TransferStatistics');
       $Transfers = $TransferStatistics->find(array('query' => array("created_at" =>array('$lt'=> new MongoDate(mktime(0,0,0,date('m'),date('d')-1,date('Y')))))));
        foreach($Transfers as $Transfer=> $value)
        {
        	if (!is_null($value))
        		$value->delete();
        }
        // 删除24小时前的数据
        $ProgramTemp = $mongo->getRepository('ProgramTemp');
        $Programs = $ProgramTemp->find(array('query' => array("created_at" =>array('$lt'=> new MongoDate(mktime(0,0,0,date('m'),date('d')-1,date('Y')))))));
 
        foreach($Programs as $Program=> $value)
        {
        	if (!is_null($value))
        		$value->delete();
        }
 
        
    }
}
