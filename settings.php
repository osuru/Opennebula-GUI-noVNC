<?php
function cnf($name){
$conf= [
'nebula_host' => 'localhost',
'nebula_port' => '2633',
'novnc_port' => '29876',
'novnc_host' => ''

];

return $conf[$name];
}

?>