<?php


include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());
$postValue=<<<xml
<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetRecommendMedia" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /><data type="1" page="1" size="10" /></parameter></request>
xml;
$browser->
	post('/api/interface',array('xmlString'=>$postValue))->

  with('request')->begin()->
    isParameter('module', 'api')->
    isParameter('action', 'interface')->
  end()->

  with('response')->begin()->
    isStatusCode()->
   //debug()->
 info('')->
  end()
;


/*$xmla=<<< abc
 <?xml version="1.0" encoding="utf-8"?>
<request website="http://iptv.cedock.com">
<parameter type="GetMediaCategory" language="zh-CN">
<device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" />
<user huanid="1234" token="x" ver="2" />
</parameter>
</request>
abc;
$browser->post('/api/Interface',array('xmlString' => '<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetMediaCategory" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /></parameter></request>','commit'=>'调用'));

$content=$browser->getResponse()->getContent();
//$browser->checkElement('body', $content)*/

