<?php
require_once('authenticate.php');
include('rpcxml.php');


?>
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/tabs.css">
<!--
<script type="text/javascript" src="jquery-latest.js"></script> 
<script type="text/javascript" src="jquery-ui.js"></script> 
-->
<script>
$(function() {
    $( "#tabs" ).tabs({
//     heightStyle: "fill",
    collapsible: true,  active: false,
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible.");
        });
      }
    }).addClass( "ui-tabs-vertical ui-helper-clearfix" );;
$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
$( "#tabs a" ).click(function(event){
  event.preventDefault();
//console.log($( this ).attr('id'));
//$.cookies.set('vmtab', 1);
return false;
});
  });

function make_fullscreen(change=1){
cur=$( "#tabs" ).css('position');
if ( (( cur=='absolute') && (change==0)) ||
 ((cur!='absolute') && (change==1)) ){
$( "#tabs" ).css({
            position: 'absolute',
	    top:0,
	    left:0,
            width: $(window).width(),
//            height: $(window).height()
        });
$( ".ui-tabs-nav" ).css({width:'8em'});

//alert(full);
//if (change==0){
resize_frame(1);
$('html, body').animate({scrollTop:70}, 'slow');

}//if
if ( (( cur!='absolute') && (change==0)) ||
 ((cur=='absolute') && (change==1)) ){
$( "#tabs" ).css({position: 'relative', width:$('.acc_container:visible').width()-20});
resize_frame(1);
}
}
</script>
<?php
$auth=$_SESSION['oneauth'];
$request = xmlrpc_encode_request('one.vmpool.info',array($auth,-3,-1,-1,-1));
$r = do_call($request);
if(is_array($r['VM']['0'])){
$param['vm']=$r['VM'];
$param['vm']['count']=count($r['VM']);
}else{
$param['vm'][0]=$r['VM'];
$param['vm']['count']=count($r);
}
#print '<pre>';
#print_r($param);

print '<div id="tabs"> 
<div align=center><h4><a href="#" onclick="make_fullscreen();return false;">
  Сделать ПОБОЛЬШЕ<a/></h4></div>
   <ul>';
print "<li><a onclick='return false;' href='vminfo.php'>Info</a>";

for($i=0;$i<$param['vm']['count'];$i++){ 
print "        <li>
	<a  onclick='return false;' href='getvmtab.php?id=".$param['vm'][$i]['ID']."#vm'>".$param['vm'][$i]['ID'].' - '.$param['vm'][$i]['NAME'].'</a></li>';
 } 
print "<li><a onclick='return false;' href='newvm.php'>NEW VM</a>";
print "</ul>";
?>

</div>