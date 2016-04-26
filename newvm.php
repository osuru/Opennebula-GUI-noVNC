<?php
require_once('authenticate.php');
include('rpcxml.php');

$auth=$_SESSION['oneauth'];
$id=$_REQUEST['id'];
$act=strtolower($_REQUEST['act']);
$id=($id>0)?$id:0;
$r=[];



if ($act=='new' )
{
$request = xmlrpc_encode_request('one.template.info',array($auth,intval($_REQUEST['template'])));
$r = do_call($request,1);
//print_r($r);
$r['TEMPLATE']['NAME']=$_REQUEST['name'];
$r['TEMPLATE']['NIC']['NETWORK']=$_REQUEST['network'];
$template = toXml($r['TEMPLATE']);
print $template;
$request = xmlrpc_encode_request('one.vm.allocate',array($auth,$template,false));
$r = do_call($request,1);

exit;
}
//GET Templates

$request = xmlrpc_encode_request('one.templatepool.info',array($auth,-2,-1,-1));
$r = do_call($request);
$r=$r['VMTEMPLATE'];
$count=count($r);
//print '<pre>';print_r($r);

print "<div width=100%>Имя машины: <input id=name type=text></div><p>";
print "<input id=templateid type=hidden>";
print "<input id=networkid type=hidden>";
print "<div style='float:left;margin-right: 10px; width: 150px;
  '>Выберите шаблон <br><ol id=template class=selectable>";

for ($i=0;$i<$count;$i++){
print ' <li class="ui-widget-content" value='.$r[$i]['ID'].'>'.$r[$i]['NAME'].'</li>';

}//for i

print "</ol></div><div style='float:left;'>Выберите сеть<br><ol id='network' class=selectable2>";

$request = xmlrpc_encode_request('one.vnpool.info',array($auth,-2,-1,-1));
$r = do_call($request);
$r=$r['VNET'];
$count=count($r);

for ($i=0;$i<$count;$i++){
print ' <li class="ui-widget-content" value='.$r[$i]['ID'].'>'.$r[$i]['NAME'].'</li>';

}//for i

print "</div>";

print <<< EOF
<p>
<div style="clear:left;">
<ol id='create' class='selectable'>
<li  class="ui-widget-content">Создать
</li>
</ol>
</div>

<script>
var isedited=0;
$('#name').keyup(function(){isedited=1; });
    $( "#create" ).selectable({
selected: function(event, ui) {
$.get('newvm.php',{act:'new',
	    template:$('#templateid').val(),
	    network:$('#networkid').val(),
	    name:$('#name').val()
   },function(data){
  alert(data);
//$('.acc_container').hide();
$('.acc_trigger:first').addClass('active').trigger('click');
});
}
});

    $( "#template" ).selectable({
selected: function(event, ui) {
                  $( ".ui-selected", this ).each(function() {
//                     var index = $( "# li" ).index( this );
             $('#templateid').val(this.value);
	    var now = new Date();
	if(isedited==0)
	 $('#name').val(this.innerText+'-'+now.getMilliseconds());
	    return 0;
                  });
	    }
           });    

     $( "#network" ).selectable({
selected: function(event, ui) {
                  $( ".ui-selected", this ).each(function() {
//                     var index = $( "# li" ).index( this );
             $('#networkid').val(this.innerText);
	    return 0;
                  });
	    }
});

</script>

EOF

?>