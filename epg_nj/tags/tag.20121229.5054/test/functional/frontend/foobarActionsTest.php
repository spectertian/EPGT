<?php
header("Content-Type: application/xml charset=utf-8"); 
$path = 'F:\xamp\php\PEAR';


function request(array $options)
{
    $defaultOptions = array(
        'url'     => null,
        'format'  => 's',
        'method'  => 'g',
        'post'    => array(),
        'get'     => array(),
        'timeout' => 2, 
    );
    foreach ($defaultOptions as $k => $v) {
        $options[$k] = array_key_exists($k, $options) ? $options[$k] : $v;
    }
    if (empty($options['url'])) {
        throw new Exception('Request URL is required');
    }
    if (!array_key_exists($options['format'], array('s' => 'string', 'a' => 'array'))) {
        throw new Exception('Illegal output format provided');
    }
    if (!array_key_exists($options['method'], array('g' => 'get', 'p' => 'post'))) {
        throw new Exception('Illegal request method provided');
    }
    include_once 'HTTP/Request2.php';
    $url = new Net_URL2($options['url']);
    $url->setQueryVariables($options['get']);
    $request = new Http_Request2($url);
    if ($options['method'] == 'g') {
        $request->setMethod(HTTP_Request2::METHOD_GET);
    } else if ($options['method'] == 'p') {
        $request->setMethod(HTTP_Request2::METHOD_POST)->addPostParameter($options['post']);
    }
    $request->setConfig(array(
        'connect_timeout' => 1,
        'timeout'         => $options['timeout'],
    ));
    $response = $request->send();
    if (200 !== ($status = $response->getStatus())) {
        throw new Exception('Request failed', $status);
    }
    // 转换编码
    $body = mb_convert_encoding($response->getBody(), 'GBK', 'UTF-8');
    if ($options['format'] == 's') {
        return $body;
    } else if ($options['format'] == 'a') {
        $return = json_decode($body, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception('Error occurred when parsing response body into array', $status);
        }
        return $return;
    }
    throw new Exception('Unknown output format', $status);
}
 $data = request(
array(
        'url'     => 'http://www.5i.test.cedock.net/api/interface',
        'format'  => 's',
        'method'  => 'p',
        'post'    => array('xmlString' => '<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetMediaCategory" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /></parameter></request>'),
        'timeout' => 2,
    ));
echo $data;
//include(dirname(__FILE__).'/../../bootstrap/functional.php');

/*$browser = new sfTestBrowser();
$browser->initialize();
$browser->
post('/api/Interface')->
isStatusCode(200)->
 isRequestParameter('module', 'api')->
isRequestParameter('action', 'Interface')->
checkResponseElement('body', '!/This is a temporary page/'); 

//include(dirname(__FILE__).'/../../bootstrap/functional.php');

//Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');*/

/*$app = 'frontend';
$debug = false;
if (!include(dirname(__FILE__).'/../../bootstrap/functional.php'))
{
  return;
}
class InterfaceBrowser extends sfTestBrowser
{
  public $events = array();
  public function listen(sfEvent $event)
  {
    $this->events[] = $event;
  }
}

/*header("Content-Type: application/xml charset=utf-8"); 


include(dirname(__FILE__).'/../../bootstrap/functional.php');
$browser = new sfTestFunctional(new sfBrowser());
 
 
 $browser->
get('/api/interface')->
isStatusCode(200)->
isRequestParameter('module', 'api')->
isRequestParameter('action', 'interface')
//checkResponseElement('body', '/api/')
;
include(dirname(__FILE__).'/../../bootstrap/functional.php');
 /* $abc=<<< xml
 <?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetMediaCategory" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /></parameter></request>
xml;
//$browser->post('/api/Interface',array('xmlString'=>'$xmla','commit'=>'调用'));

 
   with('request')->begin()->
    isParameter('module', 'api')->
    isParameter('action', 'Interface')->
   //isParameter('post', array('xmlString'=>$abc))->
end()->
//, array('xmlString'=>$abc)
  with('response')->begin()->
    isStatusCode()->
    //isHeader('content-type', 'text/html; charset=utf-8')->
    checkElement('body', '/api/')->
   // getResponse()->getContent()-> 
end()
  ;
 
  /*include_once 'HTTP/Request.php';

$postValue=<<<xml
<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetMediaCategory" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /></parameter></request>
xml;
$HTTP_Request =  new HTTP_Request('http://www.5itest.tv/api/interface');
$HTTP_Request->setMethod(HTTP_REQUEST_METHOD_POST); //HTTP_REQUEST_METHOD_GET
$HTTP_Request->addPostData('xmlString', $postValue);

if (PEAR::isError($HTTP_Request->sendRequest()) || $HTTP_Request->getResponseCode() != 200) 
{       
unset($HTTP_Request);
return false;
}
//取得返回
$res = $HTTP_Request->getResponseBody();
unset($HTTP_Request);
print_R($res);


postValue=<<<xml
<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetMediaCategory" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /></parameter></request>
xml;
$browser ->
post('/api/Interface',array('xmlString',$postValue))->

   with('request')->begin()->
    isParameter('module', 'api')->
    isParameter('action', 'Interface')->
   //isParameter('post', array('xmlString'=>$abc))->
end()->
//, array('xmlString'=>$abc)
  with('response')->begin()->
    isStatusCode(200)->
    //isHeader('content-type', 'text/html; charset=utf-8')->
    checkElement('body', '/This is a temporary page/')->
   // getResponse()->getContent()-> 
end()
  ;*/
  

?>