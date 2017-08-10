<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Permission.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $p = new Permission();
    $s = new SavehandlerApi();
    $p->save = $s;
    $action = escape($_REQUEST['action']);
    $p->employee_group = escape($_REQUEST['employee_group']);
    $p->modules = escape($_REQUEST['modules']);
    $p->modules_text = escape($_REQUEST['modules_text']);

    switch ($action) {
        case 'getResult':
        $p->getResult(); 
            exit();
            break;
        case 'saveResult':
        $p->saveResult(); 
            exit();
            break;
        default:
        $p->indexPage();

            break;
    }



?>
