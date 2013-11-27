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
 * Panel Web Debug for sfMondongo.
 *
 * @package sfMondongoPlugin
 * @author  Pablo Díez Pascual <pablodip@gmail.com>
 */
class sfMondongoWebDebugPanel extends sfWebDebugPanel
{
  /**
   * @see sfWebDebugPanel
   */
  public function getTitle()
  {
    if ($nb = count($this->getLogs()))
    {
      return '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oGAgsNOiXP4NwAAAG0SURBVDjLbZNNbhNBEIW/Kv8kdpyQSDFgViAhhISUFSyzyIIDcATW3AmJU3AGBBdgh4AVQgoRxvb0TNdj0eOxLbulp261uqtevXplL59cCgAzAJBg52jItL7aXQJfHza7gQxlOHt2H16cQzakfWDWBlgvUxenPzvh0btXVDdTdDHAATdwM9ysI+wHWEEKjt8+p+4HGvVZvHkIIcCQQSmowBFsCjRMIm4ekKdDVk0NEs3FkMX1JaiQdAN3x+wQg56xuLpHWtWkaJCE6qB6OiaO9p7vB4hRj2pkpMjUOZMFWSIfOxr3EEYIFIEEjm11oWWQeqKKTB0ZhUDlXgPvOi4MDPrsioAQTWSqCFIuGhDFGyr5ixatT3z7M4JeFVCV7ClyoS8RVWBV7tRv27HvA18GR9+WJAtWkck5EyFs3uD/goCCNsYBWY3px99EakgKmiajoTP5cofVseWAAt9REEOAN2L2/idpWTPjlMmnW8Zf52hdeIe1BlsymAm5GPyqmH74wevlY04+/yGGjlnrpC30d4Zp40eEyN/vaG4TJmHSoXnEunHuaLSep9CcnJ0y/zs/PM7Afw6S/7yvtG94AAAAAElFTkSuQmCC" alt="Mondongo Queries" /> '.$nb;
    }
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelTitle()
  {
    return 'Mondongo Queries';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelContent()
  {
    return '
      <div id="sfWebDebugDatabaseLogs">
        <h3>Mondongo Version: '.Mondongo\Mondongo::VERSION.'</h3>
        <ol>'.implode("\n", $this->getLogsList()).'</ol>
      </div>
    ';
  }

  /**
   * Returns the logs.
   *
   * @return array The logs.
   */
  protected function getLogs()
  {
    return sfContext::getInstance()->getConfiguration()->getPluginConfiguration('sfMondongoPlugin')->getLogs();
  }

  /**
   * Returns the logs list.
   *
   * @return array The logs list.
   */
  protected function getLogsList()
  {
    $logs = array();
    foreach ($this->getLogs() as $log)
    {
      $connection = $log['connection'];
      $database   = $log['database'];
      $collection = $log['collection'];
      unset($log['connection'], $log['database'], $log['collection']);

      $logs[] = sprintf(<<<EOF
<li>
  <p class="sfWebDebugDatabaseQuery"><pre>%s</pre></p>
  <div class="sfWebDebugDatabaseLogInfo">{ connection: %s, database: %s, collection: %s }</div>
</li>
EOF
        ,
        sfYaml::dump($log),
        $connection,
        $database,
        $collection
      );
    }

    return $logs;
  }
}
