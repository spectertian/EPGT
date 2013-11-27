<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());
	
$browser->  get('/api/interface')->
	with('request')->begin()->
    isParameter('module', 'api')->
    isParameter('action', 'interface')->
	end()->

	with('response')->begin()->
	info(sprintf('sssssssssssss'))->
	//isStatusCode(200)->
	//checkElement('data', '!/data language="zh-CN" num="2"/')->
	end()
;