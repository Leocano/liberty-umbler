<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_ticket = $_POST['id-ticket'];
$module = $_POST['slt-module'];
$priority = $_POST['slt-priority'];
$cost_center = $_POST['txt-cost-center'];
$subject = $_POST['txt-subject'];
$desc = $_POST['txt-desc'];
$id_category = $_POST['slt-category'];
$external_number = $_POST['txt-external'];
$id_proposal = $_POST['slt-proposal'];
$id_subcategory = $_POST['slt-subcategory'];

$id_company = $_POST['slt-company'];
$id_creator = $_POST['slt-user'];

$id_main = $_POST['slt-main'];
$participants = $_POST['slt-users'];


$ticket = new Ticket($id_ticket, $id_creator, $priority, $module, $id_category, $subject, $desc, $cost_center);
$ticket->setProposal($id_proposal);
$ticket->setExternalNumber($external_number);
$ticket->setIdCompany($id_company);
$ticket->setIdSubcategory($id_subcategory);

$dao = new TicketDAO;
$dao->updateTicket($ticket);

$lastInsertId = $id_ticket;

$id_user = $_SESSION['user']->getIdUser();
$temp_attachemnts_path = ("temp/" . $id_user);

if (is_dir($temp_attachemnts_path)){
    $dao = new AttachmentDAO;
    $folder = new DirectoryIterator($temp_attachemnts_path);

    mkdir("attachments/" . $lastInsertId);

    foreach ($folder as $dir) {
        if(!$dir->isDot()){
            $filename = $dir->getFilename();
            $new_path = "attachments/" . $lastInsertId . "/" . $filename;

            rename($temp_attachemnts_path . "/" . $filename, $new_path);

            $dao->createAttachment($new_path, $lastInsertId, $filename);
        }
    }

    rmdir($temp_attachemnts_path);
}

Token::generateToken();

$dao = new AssignDAO;


$dao->deleteMainConsultant($id_ticket, $id_main);

if (isset($participants)){
    $dao->deleteOldConsultants($id_ticket, $participants);
    $dao->assignConsultant($id_ticket, $participants);
} else {
    $dao->deleteAllParticipants($id_ticket);
}

$dao->assignMainConsultant($id_ticket, $id_main);

$mail_info = $dao->getMailInfo($id_ticket);

if (isset($participants)){
    array_push($participants, $id_main);
    $user_emails = $dao->getEmailsByUserId($participants);
    $dao->updateSent($participants);
} else {
    $main_array = array();
    array_push($main_array, $id_main);
    $user_emails = $dao->getEmailsByUserId($main_array);
    $dao->updateSent($main_array);
}


if ($id_category != 8){
    // require 'mail/mail-atribuir-chamado-editar.php';
}

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($id_ticket, $id_user, "Dados do chamado atualizados por");


Redirect::to("visualizar-chamado.php?id=" . $id_ticket . "&token=" . $_SESSION['token']);