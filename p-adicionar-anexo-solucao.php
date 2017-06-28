<?php

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_ticket = $_POST['id-ticket'];
$id_user = $_POST['id-user'];
$attachments = $_POST['attachments'];

if ($_FILES['attachments']['tmp_name'][0] != "") {
    $myFile = $_FILES['attachments'];
    $fileCount = count($myFile["name"]);

    for ($i = 0; $i < $fileCount; $i++) {
        $size = $_FILES['attachments']['size'][$i];
        if ($size > 80000000){
            echo "O limite de tamanho é de 80 MB";
            exit();
        }
    }

    $dao = new SolutionAttachmentDAO;

    $path = "solutions/" . $id_ticket;
    if (!is_dir($path)){ 
        mkdir($path);
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $name = $_FILES['attachments']['name'][$i];
        $new_path = $path . "/" . $name;

        move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $new_path);
        $dao->createSolutionAttachment($id_ticket, $name, $new_path);
    }
}

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id_ticket, $id_user, "Documentação anexada por");

echo "success";