<?php

class test1Task extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application','stb'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('need_examine', null, sfCommandOption::PARAMETER_OPTIONAL, 'Whether need to examine', 'no'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'test1';
    $this->briefDescription = '';
    $this->detailedDescription = '';
  }

  protected function execute($arguments = array(), $options = array())
  {
    echo $options["application"].$options["need_examine"]."\n";
    if($options["need_examine"] == "yes") {
        echo "This need examine!\n";
    }
    $config = sfConfig::get("app_photopre_config");
    var_dump($config["domain"]);
    exit;
  }
}
