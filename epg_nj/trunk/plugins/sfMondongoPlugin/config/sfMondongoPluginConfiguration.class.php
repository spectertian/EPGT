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
 * sfMondongoPluginConfiguration.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoPluginConfiguration extends sfPluginConfiguration
{
  protected $logs = array();

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    require_once(dirname(__FILE__).'/../lib/vendor/mondongo/lib/vendor/symfony/src/Symfony/Component/HttpFoundation/UniversalClassLoader.php');

    $loader = new Symfony\Component\HttpFoundation\UniversalClassLoader();
    $loader->registerNamespaces(array(
      'Mondongo\\Extension\\Extra' => sfConfig::get('sf_mondongo-extensions_lib_dir', dirname(__FILE__).'/../lib/vendor/mondongo-extensions-extra/lib'),
      'Mondongo' => sfConfig::get('sf_mondongo_lib_dir', dirname(__FILE__).'/../lib/vendor/mondongo/lib')
    ));
    $loader->register();

    $this->dispatcher->connect('context.load_factories', array($this, 'listenToContextLoadFactories'));

    $this->dispatcher->connect('component.method_not_found', array($this, 'listenToComponentMethodNotFound'));
  }

  /**
   * Listen to context.load_factories event.
   *
   * Initialize the Mondongo.
   *
   * @param sfEvent $event The event object.
   *
   * @return void
   */
  public function listenToContextLoadFactories(sfEvent $event)
  {
    $context = $event->getSubject();

    $mondongo = new Mondongo\Mondongo();

    // databases
    $databaseManager = $context->getDatabaseManager();
    foreach ($databaseManager->getNames() as $name)
    {
      $database = $databaseManager->getDatabase($name);
      if ($database instanceof sfMondongoDatabase)
      {
        $mondongo->setConnection($name, $database->getMondongoConnection());
      }
    }

    // log
    if (sfConfig::get('sf_logging_enabled'))
    {
      $mondongo->setLoggerCallable(array($this, 'log'));

      if (sfConfig::get('sf_web_debug'))
      {
        $this->dispatcher->connect('debug.web.load_panels', array($this, 'listenToDebugWebLoadPanels'));
      }
    }

    // context
    $context->set('mondongo', $mondongo);

    // container
    Mondongo\Container::setDefault($mondongo);
  }

  /**
   * Listen to component.method_not_fount event.
   *
   * Returns the Mondongo in actions and components: $this->getMondongo()
   *
   * @param sfEvent $event The event.
   *
   * @return bool If it returns the Mondongo.
   */
  public function listenToComponentMethodNotFound(sfEvent $event)
  {
    if ('getMondongo' == $event['method'])
    {
      $event->setReturnValue($event->getSubject()->getContext()->get('mondongo'));

      return true;
    }

    return false;
  }

  /**
   * Returns the logs.
   *
   * @return array The logs.
   */
  public function getLogs()
  {
    return $this->logs;
  }

  /**
   * Save a mondongo log.
   *
   * @param array $log The log.
   *
   * @return void
   */
  public function log(array $log)
  {
    $this->dispatcher->notify(new sfEvent('sfMondongo', 'application.log', array('sfMondongo')));

    $this->logs[] = $log;
  }

  /**
   * Listen to debug.web_load_panels event.
   *
   * @param sfEvent $event The event.
   *
   * @return void
   */
  public function listenToDebugWebLoadPanels(sfEvent $event)
  {
    $event->getSubject()->setPanel('mondongo', new sfMondongoWebDebugPanel($event->getSubject()));
  }
}
