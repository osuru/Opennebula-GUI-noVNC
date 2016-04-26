<?php
require_once('authenticate.php');
include ("rpcxml.php");

$auth=$_SESSION['oneauth'];
$id=$_REQUEST['id'];
$id=($id>0)?$id:0;
$request = xmlrpc_encode_request('one.vm.info',array($auth,intval($id)));
$r = do_call($request);
#print '<pre>';print_r($r);exit;
$id=$r['ID'];

$state=$r['STATE'];
$name=$r['NAME'];
$h=$r['HISTORY_RECORDS']['HISTORY'];
#print_r($h);

if( count($h[0])>1){
$h=$h[count($h)-1];
}
$vnc_host=$h['HOSTNAME'];
$vnc_port=$r['TEMPLATE']['GRAPHICS']['PORT'];

$ip=$r['TEMPLATE']['NIC']['IP'];

if ($state!=3){
print "<h2>STOPED</h2>";
//exit;
}
if ($state==3){
#print "IP_ADDRESS - $ip<br> ";
#Connecting vnc to $vnc_host:$vnc_port";
}

$dir = '/var/lib/one/sunstone_vnc_tokens/';
$fn=$dir.'one-'.$id;
$token='';
$host=$_SERVER['HTTP_HOST'];

if (!file_exists($fn)){
#file("http://$host/vm/$id/startvnc");
$token=substr(md5(time()),0,20);
file_put_contents($fn,"$token: $vnc_host:$vnc_port");
chmod($fn,0666);
}

$f=file_get_contents($fn);
$t=explode(':',$f);
$token=$t[0];
#}else
#B{
#$token=md5(time());
#file_put_contents($fn,"$token:$vnc_host:$vnc_port");
#}
#<p>
#<iframe height=300 width=300 src='vnc_auto.html?token=$token&port=443&title=$name&resize=downscale'>
#</iframe>
$URL="vnc_auto.html?token=$token&port=443&title=$name&resize=downscale";
print <<< EOF
<div align=center class=showhide style="background:lightgrey;float:right;color:white;width:20px">
<a href='#' onclick="return false;">X</a>
</div>
<div class=menu style="border:1px solid lightgrey;">
<a name="vm"></a> 
<form>
<div>
    <input class=vmbutton type="image" value="Resume" src='img/play.png'>
    <input class=vmbutton type="image" value="Suspend" src='img/pause.png'>
    <input class=vmbutton type="image" value="Poweroff" src='img/off.png'>
    <input class=vmbutton type="image" value="Poweroff-hard" src='img/offhard.png'>
________________
    <input class=vmbutton type="image" value="Snapshot" src='img/save.png'>
    <input class=vmbutton type="image" value="Revert" src='img/restore.png'>
    <input class=vmbutton type="image" value="Recover" src='img/recover.png'>
    </div>
</form>
<br>
<h4><A href="$URL" target=_blank>Open in NEW window</a></h4>
</div>
<iframe id="frame-$id"  src='$URL'>
</iframe>

<div id="dialog-confirm" title="" style="display:none;">
  <p ><span  class="ui-icon ui-icon-alert" 
    style="float:left; margin:0 7px 20px 0;"></span><p id="dialog-confirm-span"></p></p>
</div>
<!--
<script type="text/javascript" src="js/jquery-latest.js"></script>.
<script type="text/javascript" src="js/jquery-ui.js"></script>.
-->

<script>

$('.vmbutton').each(function(i,val){
$(val).attr('alt',val.value);
$(val).click(function(){
var vala=this.value;
//alert(1);
var text="Вы уверены, что хотите  сделать <p><p><h3> <b>"+vala+"</b> ?";
$('#dialog-confirm-span').html(text);
$("#dialog-confirm").dialog({
title: "Warning!!!",    //тайтл, заголовок окна
width:250,              //ширина
height: 150,            //высота
modal: true,            //true -  окно модальное, false - нет
buttons: {
"ДА! Делаем! ": function() {  
      $.get("actvm.php",{id:$id, act: vala},
	  function(data){
//	     alert(data);
	       });
$(this).dialog("close");
},
"Закрыть": function() { $(this).dialog("close"); }
}
});
  return false;
}) //click
})

function resize_frame(fullscreen=0){
full=0;left=90;
if (($('#tabs').css('position')=='absolute')){full=1;left=40};

var width = $(window).width()-$('.ui-tabs-nav:visible').width()-left; 
var height = width*3/4+40

//alert('full'+full);
$("#frame-$id").width(width);
$("#frame-$id").height(height);
$("#frame-$id").attr('src',$("#frame-$id").attr('src'));
$(".ui-tabs-panel:visible").width(width);
if (fullscreen==0 && full==1) {make_fullscreen(0)}
}
resize_frame();
$(window).resize(resize_frame);
$('.showhide').click(function(){
if ($('.menu').is(':visible')){
$('.menu').hide();
$(this).html('<a href="#" onclick="return false;">O</a>');
}else{
$('.menu').show(); 
$(this).html('<a href="#" onclick="return false;">X</a>');
}
});
if($('#tabs').css('position')=='absolute'){
    $('.showhide').trigger('click');
    $('.menu').hide();
    }

</script>

EOF

?>