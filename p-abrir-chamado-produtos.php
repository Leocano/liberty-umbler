<?php 

// Arquivo de processamento

require 'core/initializer.php';

// $token = $_POST['token'];
// Token::validateToken($token);

$product = $_POST['slt-product'];
$priority = $_POST['slt-priority'];
$subject = $_POST['txt-subject'];
$desc = $_POST['txt-desc'];
$id_company = $_POST['slt-company'];
$creator = $_SESSION['user']->getIdUser();

if (Validator::isEmpty(array($creator, $priority, $subject, $desc, $id_company, $product))){
    echo json_encode(
        array(
           0 => "Verifique os campos obrigatórios!"
        )
    );
    exit();
}

$dao = new TicketDAO;
$dao->createNewTicketProducts($priority, $subject, $desc, $id_company, $creator, $product);

$lastInsertId = $db->getLastInsertId();

$id_user = $creator;
$temp_attachemnts_path = ("temp/products/" . $id_user);

if (is_dir($temp_attachemnts_path)){
    $dao = new AttachmentDAO;
    $folder = new DirectoryIterator($temp_attachemnts_path);

    mkdir("attachments/products/" . $lastInsertId);

    foreach ($folder as $dir) {
        if(!$dir->isDot()){
            $filename = $dir->getFilename();
            $new_path = "attachments/products/" . $lastInsertId . "/" . $filename;

            rename($temp_attachemnts_path . "/" . $filename, $new_path);

            $dao->createProductAttachment($new_path, $lastInsertId, $filename);
        }
    }

    rmdir($temp_attachemnts_path);
}

Token::generateToken();

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($lastInsertId, $id_user, "Chamado aberto por");

echo json_encode(
    array(
        0 => 'success' ,
        1 => $lastInsertId ,
        2 => $_SESSION['token']
    )
);