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
$name_creator = $_POST['name-creator'];
$external_number = $_POST['txt-external'];

$id_creator = $_POST['id-creator'];
$id_category = 5; //Suporte
$id_company = $_POST['id-company'];

$proposal_dao = new ProposalDAO;
$verify_proposal = $proposal_dao->getProposalByCompanyAndType($id_company, 1);

if ($verify_proposal == null){
    echo json_encode(
        array(
           0 => "Você não possui uma proposta de suporte ativa!"
        )
    );
    exit();
}

if (Validator::isEmpty(array($module, $subject, $desc))){
    echo json_encode(
        array(
           0 => "Verifique os campos obrigatórios!"
        )
    );
    exit();
}

$dao = new ProposalDAO;
$proposal = $dao->getActiveSupportProposal($id_company);
$proposal = $proposal[0]->id_proposal;

$ticket = new Ticket(null, $id_creator, $priority, $module, $id_category, $subject, $desc, $cost_center);
$ticket->setProposal($proposal);
$ticket->setIdCompany($id_company);
$ticket->setNameCreator($name_creator);
$ticket->setExternalNumber($external_number);

$dao = new TicketDAO;
$dao->createNewTicket($ticket);

$lastInsertId = $db->getLastInsertId();

$id_user = $id_creator;
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