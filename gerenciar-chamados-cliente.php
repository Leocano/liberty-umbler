<?php 

// gerenciar-chamados.php
//  Página para gerenciamento dos chamados abertos

// require 'headers/main-header.php';

// if (!$user->checkProfile(array(5))){
// 	Redirect::to("index.php");
// }

$dao = new TicketDAO;
$view = $user->getView();
$id_view = $view->getIdView();

$company = $user->getCompany();
$id_company = $company->getIdCompany();

if ($id_view == 1){
	$id = $user->getIdUser();
	$tickets = $dao->getTicketsByCustomerId($id);
	$closed_tickets = $dao->getMyRecentClosedTickets($id_company, $id);
} else {
	$tickets = $dao->getTicketsByCustomerCompany($id_company, $search);
	$closed_tickets = $dao->getAllRecentClosedTickets($id_company);
}


require 'scripts/main-script.php';
require 'scripts/datatable.php';

$running_tickets = $dao->getRunningTicketsByCompany($id_company);
$monthly_tickets = $dao->getMonthlyTickets($id_company);

$dao = new ProposalDAO;
$total_hours = $dao->getTotalHours($id_company);
$hours_spent = $dao->getHoursSpent($id_company);
$remaining_hours = $total_hours[0]->hours_proposal - $hours_spent[0]->hours_spent + $total_hours[0]->extra_hours;

?>


<div class="row">
	<div class="col-xs-12 title">
		<h1>Gerenciar Chamados</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Overview</h2>
	</div>
</div>

<div class="parent-flex dash-box-wrapper">
	<div class="well well-lg">
		<div class="row">
			<div class="col-xs-12 dash-box-title text-center">
				Chamados em andamento
			</div>
			<div class="col-xs-12 text-center">
				<div class="dash-box-count">
					<?=(int) $running_tickets[0]->qtd?>
				</div>
			</div>
		</div>
	</div>
	<div class="well well-lg">
		<div class="row">
			<div class="col-xs-12 dash-box-title text-center">
				Chamados abertos neste mês
			</div>
			<div class="col-xs-12 text-center">
				<div class="dash-box-count">
					<?=(int) $monthly_tickets[0]->qtd?>
				</div>
			</div>
		</div>
	</div>
	<div class="well well-lg">
		<div class="row">
			<div class="col-xs-12 dash-box-title text-center">
				Horas restantes na proposta de Suporte ativa
			</div>
			<div class="col-xs-12 text-center">
				<div class="dash-box-count">
					<?php 
						echo (int) $remaining_hours . " h";
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<hr>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Contrato</h2>
	</div>

	<div class="col-xs-12 col-sm-4">
		<div class="well well-lg">
			<canvas id="myChart" width="100" height="100"></canvas>
		</div>
	</div>

	<div class="col-xs-12 col-sm-4">
		<div class="well well-lg">
			<canvas id="myChart-2" width="100" height="100"></canvas>
		</div>
	</div>

	<div class="col-xs-12 col-sm-4">
		<div class="well well-lg">
			<canvas id="myChart-3" width="100" height="100"></canvas>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#open-tickets">Chamados Abertos</a></li>
			<li><a data-toggle="tab" href="#closed-tickets">Chamados Fechados</a></li>
		</ul>
	</div>
</div>

<div class="tab-content">
	<div id="open-tickets" class="tab-pane fade in active">
		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Chamados Abertos</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover table-condensed data-table">
					<thead>
						<th>ID</th>
						<th>Assunto</th>
						<th>Atribuído a</th>
						<th>Status</th>
						<th>Prioridade</th>
						<th>Criado por</th>
						<th>Criado em</th>
					</thead>
					<tbody>
						<?php 
						foreach ($tickets as $ticket) {
							$read = null;
							//Verificar consultores já atribuidos
							$dao = new AssignDAO;
							$assigned = $dao->getAssignedByTicket($ticket->id_ticket);
							$main = $dao->getMainconsultant($ticket->id_ticket);
							if ($main == null){
								$read = "ticket-read";
							} else {
								$read = "";
							}
							?>
							<tr class="<?=$read?>">
								<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->id_ticket?></a></td>
								<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->subject_ticket?></a></td>
								<td>
									<?php 
										//Verificar consultores já atribuidos
										// $dao = new AssignDAO;
										// $assigned = $dao->getAssignedByTicket($ticket->id_ticket);
										// $main =$dao->getMainconsultant($ticket->id_ticket);

										if ($main == null){
											echo "<b><i>Não atribuído</i></b>";
										} else {
											echo "<strong>" . $main[0]->name . "</strong><br>";
										}

										// foreach ($assigned as $assign) {
										// 	echo $assign->name . "<br>";
										// }
									?>
								</td>
								<td><?=$ticket->desc_status?></td>
								<td class="<?=$ticket->color?>"><i class="fa fa-exclamation-circle"></i> &nbsp;<?=$ticket->desc_priority?></td>
								<td><?=$ticket->name?></td>
								<td><?=$ticket->created?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<div id="closed-tickets" class="tab-pane fade">
		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Chamados Fechados</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table width="100%" class="table table-striped table-hover table-condensed data-table">
					<thead>
						<th>ID</th>
						<th>Assunto</th>
						<th>Atribuído a</th>
						<th>Status</th>
						<th>Prioridade</th>
						<th>Criado por</th>
						<th>Criado em</th>
					</thead>
					<tbody>
						<?php 
						foreach ($closed_tickets as $ticket) {
							$read = null;
							//Verificar consultores já atribuidos
							$dao = new AssignDAO;
							$assigned = $dao->getAssignedByTicket($ticket->id_ticket);
							$main = $dao->getMainconsultant($ticket->id_ticket);
							if ($main == null){
								$read = "ticket-read";
							} else {
								$read = "";
							}
							?>
							<tr class="<?=$read?>">
								<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->id_ticket?></a></td>
								<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->subject_ticket?></a></td>
								<td>
									<?php 
										//Verificar consultores já atribuidos
										// $dao = new AssignDAO;
										// $assigned = $dao->getAssignedByTicket($ticket->id_ticket);
										// $main =$dao->getMainconsultant($ticket->id_ticket);

										if ($main == null){
											echo "<b><i>Não atribuído</i></b>";
										} else {
											echo "<strong>" . $main[0]->name . "</strong><br>";
										}

										foreach ($assigned as $assign) {
											echo $assign->name . "<br>";
										}
									?>
								</td>
								<td><?=$ticket->desc_status?></td>
								<td class="<?=$ticket->color?>"><i class="fa fa-exclamation-circle"></i> &nbsp;<?=$ticket->desc_priority?></td>
								<td><?=$ticket->name?></td>
								<td><?=$ticket->created?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

</div> <!-- //content // -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>

<script type="text/javascript">
	$('.data-table').DataTable({
		"colReorder" : true ,
		"order" : [[0, "desc"]] ,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
        },
        "responsive": true
    });

    var ctx = $("#myChart");
    var ctx2 = $("#myChart-2");
    var ctx3 = $("#myChart-3");

    var data = {
	   	labels: [
	   		'Horas Utilizadas' ,
	   		'Horas Restantes'
	   	] ,
	   	datasets: [
	   		{
	   			data: [
	   				60 ,
	   				20
	   			] ,
	   			backgroundColor: [
	   				"#80DEEA" ,
	   				"#FFE082"
	   			]
	   		}
	   	]
	};

	var options = {
		cutoutPercentage: 80
	};

   	var myDoughnutChart = new Chart(ctx, {
	    type: 'doughnut',
	    data: data,
	    options: options
	});

   	var myDoughnutChart2 = new Chart(ctx2, {
	    type: 'doughnut',
	    data: data,
	    options: options
	});

   	var myDoughnutChart3 = new Chart(ctx3, {
	    type: 'doughnut',
	    data: data,
	    options: options
	});
</script>

<?php 
require 'footers/main-footer.php';
?>