<?php

// Show error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Files

require dirname(__FILE__) . '/vendor/autoload.php';
require dirname(__FILE__) . '/model/Agent.php';

// Set Variables
$currentDir           = dirname(__FILE__);
$uploaddir            = $currentDir . "/public/uploads/";

$data                 = [];

$data['host']         = $host         = isset($_POST['host']) ? $_POST['host'] : false;
$data['publicKey']    = $publicKey    = isset($_POST['public-key']) ? $_POST['public-key'] : false;
$data['privateKey']   = $privateKey   = isset($_POST['private-key']) ? $_POST['private-key'] : false;

$data['fileToImport'] = $fileToImport = isset($_FILES['fileToImport']) ? $_FILES['fileToImport'] : false;

//Create public directory
if (!is_dir($uploaddir)) {
    mkdir($uploaddir, 0777, true);
}

// Validations
if(!$host){
    throw new Exception("O campo Host é obrigatório", 1);
}

if(!$publicKey){
    throw new Exception("O campo public key é obrigatório", 1);
}

if(!$privateKey){
    throw new Exception("O campo private key é obrigatório", 1);
}

if(!$fileToImport){
    throw new Exception("É necessário informar pelo menos um arquivo no formato JSON ou CSV", 1);
}



if($fileToImport){
    $uploadfilePath = $uploaddir . basename($_FILES['fileToImport']['name']);

    if (move_uploaded_file($_FILES['fileToImport']['tmp_name'], $uploadfilePath)) {
        
        if($_FILES['fileToImport']['type'] == 'application/json'){
            
            $mapasApiConfig = $data;

            $agent = new Agent($mapasApiConfig);
            $agent->generateAgentesWithJson($uploadfilePath);
        }
        
    
    } else {
        throw new Exception("Possível ataque de upload de arquivo!\n", 1);
    }

}
