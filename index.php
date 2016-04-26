<?php
require_once('authenticate.php');
include('rpcxml.php');
$template_vm="";

$auth=$_SESSION['oneauth'];
//print $auth;
$request = xmlrpc_encode_request('one.user.info',array($auth,-1));
$r = do_call($request);

$param['user']['NAME']=$r['NAME'];

$request = xmlrpc_encode_request('one.vmpool.info',array($auth,-3,-1,-1,-1));
$r = do_call($request);

$param['vm']=$r['VM'];
$param['vm']['count']=count($r['VM']);

#print '<pre>';print_r($r);exit;
global $param;
include('template1.html');
?>
