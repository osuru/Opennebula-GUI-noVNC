<?php
if ($_POST['act']=='go'){
include "vnc_auto.inc";
print "
<input type=hidden id='token' value='".$_POST['vms']."';
</body>";
?>

<?php
exit;
}

?>
<html>
<body>
List of VMs:
<form method=post>
<select name=vms>
<option>NONE
<?php
$dir    = '/var/lib/one/sunstone_vnc_tokens';
$files = scandir($dir);

 foreach ($files as $key => $value) 
   { 
if ($value[0]=='.')continue;
 $file = file_get_contents($dir.'/'.$value);
 $fields=explode(":", $file);
 print "<option value='{$fields[0]}'>$value";
}
?>
</select>
<input type=hidden name=act value=go>
<input type=submit>
</form>
<?php
?>