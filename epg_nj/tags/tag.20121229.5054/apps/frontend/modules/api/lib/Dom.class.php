<?php
/**
 * @author Omer Hassan
 */

/*
 * example
 *
include("Dom.class.php");
$source['book'][0][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
$source['book'][0][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
$source['book'][0]['author'] = 'Author0';
$source['book'][0]['title'] = 'Title0';
$source['book'][0]['publisher'] = 'Publisher0';

$source['book'][1]['author'][0] = 'Author1';
$source['book'][1]['author'][1] = 'Author2';
$source['book'][1]['title'] = 'Title1';
$source['book'][1]['publisher'] = 'Publisher1';

$source['book'][2][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
$source['book'][2][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
$source['book'][2][DOM::CONTENT] = 'Title2';

$rootargs = array( 'website' => "http://www.5i.tv/RPC/interface");
echo $xml = DOM::arrayToXMLString($source,"response",$rootargs);
 example: xml to array
$xmltext = <<<xml
<?xml version="1.0" encoding="utf-8"?>
	<request website="http://iptv.cedock.com">
		<parameter type="test" language="zh-CN">
			<device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" />
			<user huanid="1234" token="x" ver="2" />
		</parameter>
	</request>
 xml;
 var_dump(DOM::xmlStringToArray($xmltext));


 */
class DOM
{
  const ATTRIBUTES = '__attributes__';
  const CONTENT = '__content__';

  /**
   * @param array $source
   * @param string $rootTagName
   * @return DOMDocument
   */
  public static function arrayToDOMDocument(array $source, $rootTagName = 'root',$attribute)
  {
    $document = new DOMDocument("1.0","utf-8");
    $document->formatOutput = true;
    $document->appendChild(self::createDOMElement($source, $rootTagName, $document,$attribute));
    return $document;
  }

  /**
   * @param array $source
   * @param string $rootTagName
   * @param bool $formatOutput
   * @return string
   */
  public static function arrayToXMLString(array $source, $rootTagName = 'root',array $attribute, $formatOutput = true)
  {
    $document = self::arrayToDOMDocument($source, $rootTagName,$attribute);
    $document->formatOutput = $formatOutput;

    return $document->saveXML();
  }

  /**
   * @param DOMDocument $document
   * @return array
   */
  public static function domDocumentToArray(DOMDocument $document)
  {
    return self::createArray($document->documentElement);
  }

  /**
   * @param string $xmlString
   * @return array
   */
  public static function xmlStringToArray($xmlString)
  {
    $document = new DOMDocument();
    
    return $document->loadXML($xmlString) ? self::domDocumentToArray($document) : array();
  }

  /**
   * @param mixed $source
   * @param string $tagName
   * @param DOMDocument $document
   * @return DOMNode
   */
  private static function createDOMElement($source, $tagName, DOMDocument $document,$attribute=null)
  {
    if (!is_array($source))
    {
      $element = $document->createElement($tagName);
      $element->appendChild($document->createCDATASection($source));
      if($attribute){
        foreach ($attribute as $attributeName => $attributeValue){
            $element->setAttribute($attributeName, $attributeValue);
        }
      }
      return $element;
    }

    $element = $document->createElement($tagName);
      if($attribute){
        foreach ($attribute as $attributeName => $attributeValue){
            $element->setAttribute($attributeName, $attributeValue);
        }
      }
    foreach ($source as $key => $value)
      if (is_string($key))
        if ($key == self::ATTRIBUTES)
          foreach ($value as $attributeName => $attributeValue)
            $element->setAttribute($attributeName, $attributeValue);
        else if ($key == self::CONTENT)
          $element->appendChild($document->createCDATASection($value));
        else
          foreach ((is_array($value) ? $value : array($value)) as $elementKey => $elementValue)
            $element->appendChild(self::createDOMElement($elementValue, $key, $document));
      else
        $element->appendChild(self::createDOMElement($value, $tagName, $document));

    return $element;
  }

  /**
   * @param DOMNode $domNode
   * @return array
   */
  private static function createArray(DOMNode $domNode)
  {
    $array = array();
    if($attribute = $domNode->attributes->item(0))
    {
	$website_name = $attribute->nodeName;
	$website_value = $attribute->value;
	$array[$website_name] = $website_value;
    }
    for ($i = 0; $i < $domNode->childNodes->length; $i++)
    {
      $item = $domNode->childNodes->item($i);

      if ($item->nodeType == XML_ELEMENT_NODE)
      {
        $arrayElement = array();

        for ($attributeIndex = 0; !is_null($attribute = $item->attributes->item($attributeIndex)); $attributeIndex++)
          if ($attribute->nodeType == XML_ATTRIBUTE_NODE)
            $arrayElement[self::ATTRIBUTES][$attribute->nodeName] = $attribute->nodeValue;

        $children = self::createArray($item);

        if (is_array($children))
          $arrayElement = array_merge($arrayElement, $children);
        else
          $arrayElement[self::CONTENT] = $children;

        $array[$item->nodeName][] = $arrayElement;
      }
      else if ($item->nodeType == XML_CDATA_SECTION_NODE || ($item->nodeType == XML_TEXT_NODE && trim($item->nodeValue) != ''))
        return $item->nodeValue;
    }

    return $array;
  }
}



//include("Dom.class.php");
//$source['book'][0][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
//$source['book'][0][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
//$source['book'][0]['author'] = 'Author0';
//$source['book'][0]['title'] = 'Title0';
//$source['book'][0]['publisher'] = 'Publisher0';
//
//$source['book'][1]['author'][0] = 'Author1';
//$source['book'][1]['author'][1] = 'Author2';
//$source['book'][1]['title'] = 'Title1';
//$source['book'][1]['publisher'] = 'Publisher1';
//
//$source['book'][2][DOM::ATTRIBUTES]['isbn'] = '978-3-16-148410-0';
//$source['book'][2][DOM::ATTRIBUTES]['publish-date'] = '2002-03-25';
//$source['book'][2][DOM::CONTENT] = 'Title2';
//
//$rootargs = array( 'website' => "http://www.5i.tv/RPC/interface");
//echo $xml = DOM::arrayToXMLString($source,"response",$rootargs);

/*
$xmltext = <<<xml
<?xml version="1.0" encoding="utf-8"?>
	<request website="http://iptv.cedock.com">
		<parameter type="test" language="zh-CN">
			<device devmodel="hs16" dnum="1234" didtoken="x" ver="12.3.4" />
			<user huanid="1234" token="x" ver="2" />
		</parameter>
	</request>
xml;
*/



