<?php
 include 'settings.php';

function getArrCount ($arr, $depth=1) { 
      if (!is_array($arr) || !$depth) return 0; 
         
     $res=count($arr); 
         
      foreach ($arr as $in_ar) 
         $res+=getArrCount($in_ar, $depth-1); 
      
      return $res; 
  } 

function xml2array ( $xmlObject, $out = array () )
{
#$xmlObject = is_object($xmlObject) ? $xmlObject : simplexml_load_string($xmlObject);
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ||  is_array ( $node ) ) ? xml2array ( $node ) : $node;

        return $out;
}

function do_call($request,$debug=0,$host='', $port='') {
    $host=($host=='')? cnf('nebula_host'):$host;
    $port=($port=='')? cnf('nebula_port'):$port;
    $fp = fsockopen($host, $port, $errno, $errstr);
    $query = "POST /RPC2 HTTP/1.0\nUser_Agent: My Egg Client\nHost: ".$host."\nContent-Type: text/xml\nContent-Length: ".strlen($request)."\n\n".$request."\n";
    if (!fputs($fp, $query, strlen($query))) {
        $errstr = "Write error";
        return 0;
    } 

#$request = xmlrpc_encode_request($request0); 
#$context = stream_context_create(array(
#'http' => array(
#    'method' => "POST",
#    'header' => "Content-Type: text/xml\r\n".
#    "Content-Length: " . strlen($data) . "\r\n",
#    'content' => $request
#))); 

#$file = file_get_contents("http://$host:$port/RPC2", false, $context); 
 
/*декодируем ответ*/
#print $file;
#$response = xmlrpc_decode($file); 
 
/*если ошибка – выводим ее, иначе – выводим ответ сервера*/
#if (xmlrpc_is_fault($response)) {
#    trigger_error("xmlrpc: $response[faultString] ($response[faultCode])");
#    return 0;
#} else {
#    print_r( unserialize($response));
#} 

    $contents = '';
    while (!feof($fp)) {
        $contents .= fgets($fp);
#    $content = file_get_contents($fp);
    }

    fclose($fp);
//    return $contents;

//$response = xmlrpc_decode($contents); 
#$content = preg_replace("/\!\[CDATA\[(.*)]]/i","$1\]",$contents);
$contents=preg_replace("/\&lt;!\[CDATA\[(.*?)\]\]\&gt;/ies", "'$1'", $contents);
list($header, $body) = preg_split("/\R\R/", $contents, 2);

$p = xml_parser_create();
//$body="<USER><ID>4</ID><GID>100</GID><GROUPS><ID>100</ID></GROUPS><GNAME>OSU-STUDENTS</GNAME><NAME>13fiit</NAME><PASSWORD>b514c7b38922fa3e11220c1c578674e9fe214e6e</PASSWORD><AUTH_DRIVER>core</AUTH_DRIVER><ENABLED>1</ENABLED>";
xml_parse_into_struct($p, $body, $data);
xml_parser_free($p);
#$p = xml_parser_create();
#xml_parse_into_struct($p, $data[11]['value'], $data2,$tags);
#xml_parser_free($p);
//return $data;
//print $body;
#print "<pre>";
#print_r ($data[11]['value']);
if ($debug)print $contents;
#print '123';
try{
$d=new SimpleXMLElement($data[11]['value']);
return xml2array($d);
}
catch(Exception $e)
{
return 0;
}
#return simplexml_load_string($body);
#print_r($dd);
#$data=$dd['params']['param']['value']['array']['data']['value'][1]['string'];
//print $d->params->param->value->array->data->value[1];
#print preg_replace('/\</i','--',$dd);


}

function ToXml($array, $rootElement = null, $xml = null) {
  $_xml = $xml;
 
  if ($_xml === null) {
    $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
  }
 
  foreach ($array as $k => $v) {
    if (is_array($v)) { //nested array
      ToXml($v, $k, $_xml->addChild($k));
    } else {
      $_xml->addChild($k, $v);
    }
  }
 
  return $_xml->asXML();
}
?>
