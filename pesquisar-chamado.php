<?php 

require 'headers/main-header.php';

$dao = new TicketDAO;

$search = $_GET['txt-search'];
$view = $user->getView();
$id_view = $view->getIdView();
$id_user = $user->getIdUser();
$company = $user->getCompany();
$id_company = $company->getIdCompany();

// var_dump($id_view);

if ($user->checkProfile(array(1, 2, 3))){
	$tickets = $dao->searchConsultantTickets($search);
} else if($user->checkProfile(array(5))){
	if ($id_view == 1){
		$tickets = $dao->searchTicketsByUserId($search, $id_user);
	} else {
		$tickets = $dao->searchTicketsByCompanyId($search, $id_company);
	}
}

require 'scripts/main-script.php';
require 'scripts/datatable.php';


?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Pesquisa de Chamados</h1>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-hover table-condensed data-table">
			<thead>
				<th>ID</th>
				<th>Assunto</th>
				<th>Criado por</th>
				<th>Empresa</th>
				<th>Atribuído a</th>
				<th>Prioridade</th>
				<th>Status</th>
				<th>Criado em</th>
			</thead>
			<tbody>
				<?php 
				foreach ($tickets as $ticket) {
					?>
					<tr>
						<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->id_ticket?></a></td>
						<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->subject_ticket?></a></td>
						<td><?=$ticket->name?></td>
						<td><?=$ticket->company?></td>
						<td>
							<?php 
								//Verificar consultores já atribuidos
								$dao = new AssignDAO;
								$assigned = $dao->getAssignedByTicket($ticket->id_ticket);
								$main =$dao->getMainconsultant($ticket->id_ticket);

								if ($ticket->id_category == 8){
									echo "<b><i>Todos</i></b>";
								} else {
									if ($main == null){
										echo "<b><i>Não atribuído</i></b>";
									} else {
										echo "<strong>" . $main[0]->name . "</strong><br>";
									}

									foreach ($assigned as $assign) {
										echo $assign->name . "<br>";
									}
								}
							?>
						</td>
						<td class="<?=$ticket->color?>">
							<i class="fa fa-exclamation-circle"></i>&nbsp; <?=$ticket->desc_priority?> 
						</td>
						<td><?=$ticket->desc_status?></td>
						<td><?=$ticket->created?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>


</div> <!-- //content // -->

<script type="text/javascript">
	$('.data-table').DataTable({
		"colReorder" : true ,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
        },
        "responsive": true
    });
</script>
<?php 
require 'footers/main-footer.php';
?>