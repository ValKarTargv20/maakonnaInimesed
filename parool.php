<?php
//страница, которая показывает пароли
$parool='kasutaja2';
$sool='tavalinetext';
$krypt=crypt($parool,$sool);
echo $krypt;
?>
