<?php 

require 'core/initializer.php';

$id_user = $_POST['id-creator'];

// Inserção dos anexos
if ($_FILES['attachments']['tmp_name'][0] != "") {
    $myFile = $_FILES['attachments'];
    $fileCount = count($myFile["name"]);

    for ($i = 0; $i < $fileCount; $i++) {
        $size = $_FILES['attachments']['size'][$i];
        if ($size > 3000000){
            // $dao->deleteTicket($lastInsertId);
            // Redirect::to("abrir-chamado.php?error=attachment");
            echo "size";
            exit();
        }
    }

    $dao = new AttachmentDAO;

    $path = "temp/" . $id_user;
    if (!is_dir($path)){
    	mkdir($path);
	}

    for ($i = 0; $i < $fileCount; $i++) {
        $name = $_FILES['attachments']['name'][$i];
        $new_path = $path . "/" . $name;

        move_uploaded_file($_FILES['attachments']['tmp_name'][$i], $new_path);
    }
}