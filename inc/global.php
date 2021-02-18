<?php 
header("P3P: CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
session_start();
header('Content-Type:text/html;charset=utf-8');
!defined("FROOT") && define("FROOT",dirname(__FILE__));
include_once(FROOT.'/config.php');//公共配置文件
include_once(FROOT.'/mysql.php');
include_once(FROOT.'/function.php');
?>