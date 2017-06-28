<?php 

require 'core/initializer.php';

$id_user = $_SESSION['user']->getIdUser();
$id_ticket = $_POST['id-ticket'];

// Inserção dos anexos
if ($_FILES['attachments']['tmp_name'][0] != "") {
    $myFile = $_FILES['attachments'];
    $fileCount = count($myFile["name"]);

    for ($i = 0; $i < $fileCount; $i++) {
        $size = $_FILES['attachments']['size'][$i];
        if ($size > 3000000){
            echo "size";
            exit();
        }
    }

    $dao = new AttachmentDAO;

    $path = "attachments/" . $id_ticket;
    if (!is_dir($path)){ 
        mkdir($path);
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $name = $_FILES['attachments']['name'][$i];
        $new_path = $path . "/" . $name;

        move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $new_path);
        $dao->createAttachment($new_path, $id_ticket, $name);

    }
}