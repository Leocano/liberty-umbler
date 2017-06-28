<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id = $_POST['id'];
$id_company = $_POST['id-company'];
$id_proposal_type = $_POST['id-proposal-type'];
$dao = new ProposalDAO;

if (isset($_POST['status'])){
	if ($id_proposal_type == 1 || $id_proposal_type == 5){
		$dao->disableInactiveProposals($id_company, $id_proposal_type);
	}
	$status = 1;
} else {
	$status = 0;
}


$dao->updateStatus($id, $status);

echo $status;