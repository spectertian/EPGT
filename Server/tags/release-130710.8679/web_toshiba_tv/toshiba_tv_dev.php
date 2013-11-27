<?php
mb_internal_encoding("UTF-8");
mb_http_output("HTML-ENTITIES");
ob_start('mb_output_handler');

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) && substr(@$_SERVER['REMOTE_ADDR'], 0, 9) != "192.168.1")
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('toshiba_tv', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
