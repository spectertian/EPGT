<?php

require_once 'symfony/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
     set_include_path( $this->rootDir.DIRECTORY_SEPARATOR.'plugins' . DIRECTORY_SEPARATOR . 'PEAR'.  PATH_SEPARATOR . get_include_path());
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfMondongoPlugin');
  }
}
