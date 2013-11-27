<?php

//header("Content-Type: test/html charset=utf-8"); 
header("Content-type: text/xml  charset=utf-8");

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
    $body = mb_convert_encoding($response->getBody(), 'gbk', 'UTF-8');
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
        'post'    => array('xmlString' => '<?xml version="1.0" encoding="utf-8"?><request website="http://iptv.cedock.com"><parameter type="GetChannelList" language="zh-CN"><device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" /><user huanid="1234" token="x" ver="2" /><data province="四川"/></parameter></request>'),
        'timeout' => 2,
    ));
    echo $data;


?>