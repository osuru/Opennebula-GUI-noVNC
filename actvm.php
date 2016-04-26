<?php
require_once('authenticate.php');
include('rpcxml.php');

$auth=$_SESSION['oneauth'];
$id=$_REQUEST['id'];
$act=strtolower($_REQUEST['act']);
$id=($id>0)?$id:0;
$r=[];
if ($act=='resume' || $act=='poweroff' || $act=='suspend' )
{
$request = xmlrpc_encode_request('one.vm.action',array($auth,$act,intval($id)));
$r = do_call($request);
}

if ($act=='poweroff-hard'){
$request = xmlrpc_encode_request('one.vm.action',array($auth,'poweroff-hard',intval($id)));
$r = do_call($request);
}

if ($act=='snapshot'){
#$request = xmlrpc_encode_request('one.vm.snapshotcreate',array($auth,intval($id)));
#$r = do_call($request);
}
if ($act=='revert'){
#$request = xmlrpc_encode_request('one.vm.snapshotcreate',array($auth,intval($id)));
#$r = do_call($request);
}
if ($act=='recover'){
$request = xmlrpc_encode_request('one.vm.recover',array($auth,'recover',true));
$r = do_call($request);
}

print_r($r);


?>