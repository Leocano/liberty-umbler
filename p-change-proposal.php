<?php 

require 'core/initializer.php';

$id_company = $_POST['id-company-proposal'];
$id_proposal_type = $_POST['id-proposal-type'];

$dao = new ProposalDAO;
$proposals = $dao->getProposalByCompanyAndType($id_company, $id_proposal_type);

echo json_encode($proposals);