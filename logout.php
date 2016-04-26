<?php
require_once('authenticate.php');
session_destroy();
header('Location: index.php');
?>