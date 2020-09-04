<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "bootstrap.php";
require "model/User.php";


// Set Variables
$currentDir           = dirname(__FILE__);
$uploaddir            = $currentDir . "/public/uploads/";

$data                 = [];
$data['fileToImport'] = $fileToImport = isset($_FILES['fileToImport']) ? $_FILES['fileToImport'] : false;

//Create public directory
if (!is_dir($uploaddir)) {
    mkdir($uploaddir, 0777, true);
}

if(!$fileToImport){
    throw new Exception("O arquivo de importação não foi enviado", 1);
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


$user = User::create([
    'auth_provider' => 1,
    'auth_uid' => 1,
    'email' => 'wiusmarques.dev@outlook.com',
    'last_login_timestamp' => date('now'),
    'create_timestamp'  => date('now'),
    'status' => 1
]);

?>