<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$module = $_POST['slt-module'];
$priority = $_POST['slt-priority'];
$cost_center = $_POST['txt-cost-center'];
$subject = $_POST['txt-subject'];
$desc = $_POST['txt-desc'];
$id_creator = $_POST['slt-user'];
$id_category = $_POST['slt-category'];
$id_company = $_POST['slt-company'];
$name_creator = $_POST['name-creator'];
$external_number = $_POST['txt-external'];
$id_proposal = $_POST['slt-proposal'];
$id_subcategory = $_POST['slt-subcategory'];

if (Validator::isEmpty(array($module, $id_company, $id_creator, $id_proposal, $subject, $desc))){
    echo json_encode(
        array(
           0 => "Verifique os campos obrigatórios!"
        )
    );
    exit();
}

$ticket = new Ticket(null, $id_creator, $priority, $module, $id_category, $subject, $desc, $cost_center);
$ticket->setProposal($id_proposal);
$ticket->setIdCompany($id_company);
$ticket->setNameCreator($name_creator);
$ticket->setExternalNumber($external_number);
$ticket->setIdSubcategory($id_subcategory);

$dao = new TicketDAO;
$dao->createNewTicketConsultant($ticket);

$lastInsertId = $db->getLastInsertId();

$id_user = $_POST['id-user'];
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

// if ($id_category == 8){
//     $dao = new ConsultantDAO;
//     $consultants = $dao->getAllConsultants();

//     $dao = new AssignDAO;
//     $assigned_users = array();

//     foreach ($consultants as $consultant) {
//         array_push($assigned_users, $consultant->id_user);
//     }
//     $dao->assignConsultant($lastInsertId, $assigned_users);
//     // exit();
// }

Token::generateToken();

$id_user = $_SESSION['user']->getIdUser();
$dao = new HistoryDAO;
$dao->insertHistory($lastInsertId, $id_user, "Chamado aberto por");

// Redirect::to("visualizar-chamado.php?id=" . $lastInsertId . "&token=" . $_SESSION['token']);
echo json_encode(
    array(
        0 => 'success' ,
        1 => $lastInsertId ,
        2 => $_SESSION['token']
    )
);