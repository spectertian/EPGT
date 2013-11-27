<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('android_gap', 'prod', false);
sfContext::createInstance($configuration)->dispatch();

function print_rr($array) {
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}