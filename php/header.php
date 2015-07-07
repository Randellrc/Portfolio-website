<?php
// Hier laad ik de header.html in
$header = new TemplatePower("template/files/header.tpl");
$header->prepare();
if(!empty($_SESSION['accountid'])){
    $header->newBlock("LOGGEDIN");
    $header->assign("USERNAME", $_SESSION['username']);
//    if($_SESSION['roleid'] == 2){
//        $header->newBlock("ADMINMENU");
//    }
}else{
    $header->newBlock("LOGINTOP");
}