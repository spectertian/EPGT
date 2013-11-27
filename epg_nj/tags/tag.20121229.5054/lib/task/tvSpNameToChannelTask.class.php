<?php

class tvSpNameToChannelTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'SpNameToChannel';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:SpNameToChannel|INFO] task does things.
Call it with:

  [php symfony tv:SpNameToChannel|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();                 
        $sps=$mongo->getRepository('SpService')->getServicesByTag();   
        foreach($sps as $sp){
            if($sp->getChannelCode()!=null){
                $channel = Doctrine::getTable("Channel")->findOneByCode($sp->getChannelCode());
                $channel->setMemo($sp->getName());
                $channel->save();
            }
        }
        echo "finished!\n";
    }
}
