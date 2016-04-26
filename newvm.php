<?php
require_once('authenticate.php');
include('rpcxml.php');

$auth=$_SESSION['oneauth'];
$id=$_REQUEST['id'];
$act=strtolower($_REQUEST['act']);
$id=($id>0)?$id:0;
$r=[];
if ($act=='info' )
{
$request = xmlrpc_encode_request('one.vm.info',array($auth,intval($id)));
$r = do_call($request);
}

print_r($r);


?>