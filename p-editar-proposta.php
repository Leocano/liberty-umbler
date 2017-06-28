<?php 

// Arquivo de processamento

require 'core/initializer.php';

$token = $_POST['token'];
Token::validateToken($token);

$id_proposal = $_POST['id-proposal'];
$id_company = $_POST['slt-empresa'];
$id_user = $_POST['slt-user'];
$id_proposal_type = $_POST['slt-proposal-type'];
$name_proposal = $_POST['txt-proposal'];
$desc_proposal = $_POST['txt-desc-proposal'];
$id_status = $_POST['slt-status-proposal'];
$hours = $_POST['txt-hours'];
$start_proposal = $_POST['slt-start-proposal'];
$end_proposal = $_POST['slt-end-proposal'];
$percentage = $_POST['txt-percentage'];
$months = $_POST['txt-months-to-reset'];

if ($hours == ""){
	$hours = null;
}

$start_proposal = str_replace("/","-", $start_proposal);
$end_proposal = str_replace("/","-", $end_proposal);

$start_proposal = strtotime($start_proposal);
$start_proposal = date("Y-m-d", $start_proposal);

$end_proposal = strtotime($end_proposal);
$end_proposal = date("Y-m-d", $end_proposal);

$proposal = new Proposal($id_company, $id_user, $id_proposal_type, $name_proposal, $desc_proposal, $id_status);
$proposal->setHours($hours);
$proposal->setStart($start_proposal);
$proposal->setEnd($end_proposal);
$proposal->setPercentage($percentage);
$proposal->setMonths($months);



$dao = new ProposalDAO;

if ($id_status == 1){
	if ($id_proposal_type == 1 || $id_proposal_type == 5){
		$dao->disableInactiveProposals($id_company, $id_proposal_type);
	}
}

$dao->updateProposal($proposal, $id_proposal);

Redirect::to("gerenciar-propostas.php");