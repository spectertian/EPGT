<?php

class tvXSIndexRebuildTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'XSIndexRebuild';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:XSIndexRebuild|INFO] task does things.
Call it with:

  [php symfony tv:XSIndexRebuild|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $wiki_repo = $mongo->getRepository("Wiki");
    $wiki_repo->rebuildXunSearchDocument();

  }
}

