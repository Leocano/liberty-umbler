<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_ticket = $_POST['id-ticket'];
$priority = $_POST['slt-priority'];
$subject = $_POST['txt-subject'];
$desc = $_POST['txt-desc'];
$product = $_POST['slt-product'];

$id_company = $_POST['slt-company'];
$id_creator = $_POST['slt-user'];

$db->query(
    "
    UPDATE
        tb_product_tickets
    SET
        desc_ticket = ?
    ,   id_product = ?
    ,   id_priority = ?
    ,   id_company = ?
    ,   subject_ticket = ?
    WHERE
        id_ticket = ?
    ",
    array(
        $desc
    ,   $product
    ,   $priority
    ,   $id_company
    ,   $subject
    ,   $id_ticket
    )
);

$lastInsertId = $id_ticket;

$id_user = $_SESSION['user']->getIdUser();
$temp_attachemnts_path = ("temp/" . $id_user);

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

// $dao = new AssignDAO;


// $dao->deleteMainConsultant($id_ticket, $id_main);

// if (isset($participants)){
//     $dao->deleteOldConsultants($id_ticket, $participants);
//     $dao->assignConsultant($id_ticket, $participants);
// } else {
//     $dao->deleteAllParticipants($id_ticket);
// }

// $dao->assignMainConsultant($id_ticket, $id_main);

// $mail_info = $dao->getMailInfo($id_ticket);

// if (isset($participants)){
//     array_push($participants, $id_main);
//     $user_emails = $dao->getEmailsByUserId($participants);
//     $dao->updateSent($participants);
// } else {
//     $main_array = array();
//     array_push($main_array, $id_main);
//     $user_emails = $dao->getEmailsByUserId($main_array);
//     $dao->updateSent($main_array);
// }


// if ($id_category != 8){
//     // require 'mail/mail-atribuir-chamado-editar.php';
// }

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistoryProduct($id_ticket, $id_user, "Dados do chamado atualizados por");


Redirect::to("visualizar-chamado-produto.php?id=" . $id_ticket . "&token=" . $_SESSION['token']);