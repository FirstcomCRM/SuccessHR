<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/ApplicantImport.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'class/PDF2Text.php';
    include_once 'vendor/autoload.php';
    include_once 'plugins/pdfparser-master/src/Smalot/PdfParser/Parser.php';
    
//    var_dump($_POST['file_name']);die;
    
    for($i=0;$i<sizeof($_POST['import_name']);$i++){
        $file_name = $_POST['file_name'][$i];
//        echo $file_name;die;
        //text1.txt
        //var_dump($_POST['import_name'][$file_name]);die;
    }
    $o = new ApplicantImport();
    $s = new SavehandlerApi();
    
    $o->save = $s;
    $o->files = $_FILES['files'];
    
    $o->import_name = escape($_POST['import_name']);
    $o->import_phone = escape($_POST['import_phone']);
    $o->import_gender = escape($_POST['import_gender']);
    $o->import_email = escape($_POST['import_email']);
    $o->import_assign = escape($_POST['import_assign']);
    $o->import_comments = escape($_POST['import_comments']);    
    $o->import_detail = escape($_POST['import_detail']);   

    $action = escape($_REQUEST['action']);

    
    switch ($action) {
        case "importResume":
//        $o->importResume();
            $o->saveImportFile();
            rediectUrl("applicantimport.php",getSystemMsg(1,'Upload File successfully'));
            exit();
            break;
        case "saveImportData":
            $o->saveImportData();
            rediectUrl("applicantimport.php",getSystemMsg(1,'Create Data successfully'));
            exit();
            break;
        default:     
            $o->showImportData();
    }    