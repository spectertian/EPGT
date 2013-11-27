<?php
/**
 * 获取当前正在播放的体育节目
 * @author wn
 */
class programSportTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'programSport';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [programSport|INFO] task does things.
Call it with:

  [php symfony programSport|INFO]
EOF;
  }

	protected function execute($arguments = array(), $options = array())
	{
    // initialize the database connection
		$mongo = $this->getMondongo();
		$setting_repository  = $mongo->getRepository('setting');
		$values = $setting_repository->getValueByKey('sportsearchkey',true);//数组形式
		if($values)
		{
			foreach($values as $value)
			{
				$v = trim($value);
				if($v != '')
					$arr[] = new MongoRegex("/.*$v.*/i");
			}
			//print_R($arr);///////////////////////////////////////////////
			$program_repository  = $mongo->getRepository('Program');
			$today = date("Y-m-d");
			//$today="2012-12-28";
			$sport_programs = $program_repository->find(array('query'=>
															array(
																'$or'=>array(
																	array('name'=>array('$in'=>$arr)),
																	array('tags'=>array('$in'=>$arr))
																	        ),
																'date'=>$today
																  ),
															  )
														);
			if($sport_programs)	
			{
				foreach($sport_programs as $sport_program)		
				{
					$psRepos = $mongo->getRepository('ProgramSport');
					$psalive = $psRepos->findOne(array('query'=>array('program_id'=>(string)$sport_program->getId())));
					if(!$psalive){
				    	$ps = new ProgramSport();
				    	$ps->setProgramId  ((string)$sport_program->getId());
				    	$ps->setName       ($sport_program->getName()       ?$sport_program->getName()       :'');
				    	$ps->setPublish    ($sport_program->getPublish()    ?$sport_program->getPublish()    :'');
				    	$ps->setChannelCode($sport_program->getChannelCode()?$sport_program->getChannelCode():'');
				    	$ps->setTags       ($sport_program->getTags()       ?$sport_program->getTags()       :'');
				    	$ps->setStartTime  ($sport_program->getStartTime()  ?$sport_program->getStartTime()  :'');
				    	$ps->setEndTime    ($sport_program->getEndTime()    ?$sport_program->getEndTime()    :'');
				    	$ps->setTime       ($sport_program->getTime()       ?$sport_program->getTime()       :'');
				    	$ps->setDate       ($sport_program->getDate()       ?$sport_program->getDate()       :'');
				    	$ps->setWikiId     ($sport_program->getWikiId()     ?$sport_program->getWikiId()     :'');
				    	$ps->setAdmin      ($sport_program->getAdmin()      ?$sport_program->getAdmin()      :'');
				    	$ps->setSort       ($sport_program->getSort()       ?$sport_program->getSort()       :'');
				    	$ps->setTvsouId    ($sport_program->getTvsouId()    ?$sport_program->getTvsouId()    :'');				
				    	$ps->save();	
						echo "\n";
					}
				}				
			}
		}												
	}
}
