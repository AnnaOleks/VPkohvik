<?php
$kasutaja = "d133845_vesipibuphp";
$parool = "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik";
$andmebaas = "d133845_vesipibudb";
$serverinimi = "d133845.mysql.zonevs.eu";

$yhendus = new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset("utf8");
