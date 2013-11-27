<?php
mb_internal_encoding("UTF-8");
mb_http_output("HTML-ENTITIES");
ob_start('mb_output_handler');  

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('hn_tv', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
