<?php

class tvUpdateSpServiceLogoTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'UpdateSpServiceLogo';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:UpdateSpServiceLogo|INFO] task does things.
Call it with:

  [php symfony tv:UpdateSpServiceLogo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $spRep = $mongo->getRepository("SpService");
    //查找出spservice里所有有channelcode的频道
    $spChannels = $spRep->find(
    	array(
    		'query'=>array(
				'channel_code' =>  array('$exists' => true,'$ne'=>null)   
    	))
    );
    $i=0;
    foreach ($spChannels as $spchannel) {
    	$channelCode = $spchannel->getChannelCode();
    	$channel = Doctrine::getTable("Channel")->findOneByCode($channelCode);
    	if ($channel){
	    	$channelLogo = $channel->getLogo();
	    	if($channelLogo != ($spchannel->getChannelLogo())){
	    		$spchannel -> setChannelLogo($channelLogo);
	    		if($spchannel->save()){
	    			$i++;
	    		}
	    	}
    	}
    }
    
    // add your code here
    echo ($i.' changed finished!');
  }
}
