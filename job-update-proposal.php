<?php 

// require 'core/initializer.php';

// $dao = new ProposalDAO;
// $proposals = $dao->getProposalInfo();

// foreach ($proposals as $proposal) {
// 	$current_proposal_month = $proposal->current_month;

// 	if ($current_proposal_month == $proposal->months_proposal){
// 		$extra_hours = 0;
// 		$current_proposal_month = 1;
// 	} else {
// 		$extra_hours = ($proposal->percentage_proposal / 100) * ($proposal->hours_proposal - $proposal->hours_spent + $proposal->extra_hours);
// 		if ($extra_hours <= 0){
// 			$extra_hours = 0;
// 		}
// 		$current_proposal_month++;
// 	}

// 	$dao->updateProposalMonthly($current_proposal_month, $extra_hours, $proposal->id_proposal);
// }

// if (isset($_POST['is_job'])){
	 
// } else {
// 	header("HTTP/1.0 404 Not Found");
// }