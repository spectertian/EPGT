<?php

/*
 * Copyright 2010 Pablo Díez Pascual <pablodip@gmail.com>
 *
 * This file is part of sfMondongoPlugin.
 *
 * sfMondongoPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sfMondongoPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with sfMondongoPlugin. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Global task for run Mondongo builders.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoBuildTask extends sfMondongoTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('and-load', null, sfCommandOption::PARAMETER_OPTIONAL | sfCommandOption::IS_ARRAY, 'Load fixture data'),
    ));

    $this->namespace = 'mondongo';
    $this->name = 'build';
    $this->briefDescription = 'Build';

    $this->detailedDescription = <<<EOF
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection('mondongo', 'generating classes');

    $mondator = new Mondongo\Mondator\Mondator();
    $mondator->setConfigClasses($this->prepareConfigClasses());
    $mondator->setExtensions(array(
      new Mondongo\Extension\Core(array(
        'default_document_output'   => sfConfig::get('sf_lib_dir').'/model/mondongo',
        'default_repository_output' => sfConfig::get('sf_lib_dir').'/model/mondongo',
      )),
      new sfMondongoExtensionPluginClasses(),
      new Mondongo\Extension\DocumentDataCamelCaseMap(),
      new Mondongo\Extension\DocumentFromToArray(),
      new Mondongo\Extension\DocumentArrayAccess(),
      new Mondongo\Extension\DocumentPropertyOverloading(),
      new Mondongo\Extension\DocumentDataMap(),
      new sfMondongoExtensionForms(array(
        'output' => sfConfig::get('sf_lib_dir').'/form/mondongo',
      )),
    ));
    $mondator->process();

    // BaseFormMondongo
    if (!file_exists($file = sfConfig::get('sf_lib_dir').'/form/mondongo/BaseFormMondongo.class.php'))
    {
      file_put_contents($file, <<<EOF
<?php

/**
 * Mondongo Base Class.
 */
abstract class BaseFormMondongo extends sfMondongoForm
{
  public function setup()
  {
  }
}
EOF
      );
    }

    // data-load
    if ($options['and-load'])
    {
      $this->runTask('mondongo:data-load');
    }
  }

  protected function prepareConfigClasses()
  {
    $configClasses = array();

    $finder = sfFinder::type('file')->name('*.yml')->sort_by_name()->follow_link();

    // plugins
    foreach ($this->configuration->getPlugins() as $pluginName)
    {
      $plugin = $this->configuration->getPluginConfiguration($pluginName);

      foreach ($finder->in($plugin->getRootDir().'/config/mondongo') as $file)
      {
        foreach (sfYaml::load($file) as $className => $configClass)
        {
          if (array_key_exists($className, $configClasses))
          {
            $configClasses[$className] = sfToolkit::arrayDeepMerge($configClasses[$className], $configClass);
          }
          else
          {
            $configClasses[$className] = $configClass;
          }

          if (!array_key_exists('plugin_name', $configClasses[$className]))
          {
            $configClasses[$className]['plugin_name'] = $pluginName;
          }
          if (!array_key_exists('plugin_dir', $configClasses[$className]))
          {
            $configClasses[$className]['plugin_dir'] = $plugin->getRootDir();
          }
        }
      }
    }

    // project
    foreach ($finder->in(sfConfig::get('sf_config_dir').'/mondongo') as $file)
    {
      $configClasses = sfToolkit::arrayDeepMerge($configClasses, sfYaml::load($file));
    }

    return $configClasses;
  }
}
