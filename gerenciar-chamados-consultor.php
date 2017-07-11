<?php 

$dao = new TicketDAO;
$id_user = $user->getIdUser();

require 'scripts/main-script.php';
require 'scripts/datatable.php';

?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Gerenciar Chamado</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#my-tickets">Meus Chamados</a></li>
			<?php 
				$user_dao = new UserDAO;
				$id_area = $user_dao->getUserAreaId($id_user);
				if ($user->checkProfile(array(2, 3)) || $id_area[0]->area_user == 2){
			?>
			<li><a data-toggle="tab" href="#all-tickets">Todos os Chamados</a></li>
			<?php 
			}
			?>
		</ul>
	</div>
</div>

<?php 
	$tickets = $dao->getAllAssignedTickets($id_user);
?>


<div class="tab-content">
	<div id="my-tickets" class="tab-pane fade in active">

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Chamados de <?=$user->getName()?></h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<!-- <div class="table-responsive"> -->
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
							$idx = 0;
							foreach ($tickets as $ticket) {
								if ($ticket->id_category != 8){
									?>
									<tr>
										<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->id_ticket?></a></td>
										<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->subject_ticket?></a></td>
										<td>
											<a data-toggle="modal" data-target="#modal-customer" class="info-toggle info-toggle-customer" href="#" data-company="<?=$ticket->company?>" data-name="<?=$ticket->name?>" data-email="<?=$ticket->email?>" data-alt-email="<?=$ticket->alternative_email?>" data-phone="<?=$ticket->phone?>" data-role="<?=$ticket->role?>">
												<?=$ticket->name?>
											</a>
										</td>
										<td>
											<a data-toggle="modal" data-target="#modal-company" class="info-toggle info-toggle-company" href="#" data-name="<?=$ticket->company?>" data-address="<?=$ticket->comp_address?>" data-bairro="<?=$ticket->comp_bairro?>" data-city="<?=$ticket->comp_city?>" data-cep="<?=$ticket->comp_cep?>" data-main-contact="<?=$ticket->comp_main_contact?>" data-email="<?=$ticket->comp_email?>" data-phone="<?=$ticket->comp_phone?>" data-cellphone="<?=$ticket->comp_cellphone?>">
												<?=$ticket->company?>
											</a>
										</td>
										<td>
											<?php 
												//Verificar consultores já atribuidos
												$dao = new AssignDAO;
												$assigned = $dao->getAssignedByTicket($ticket->id_ticket);
												$main =$dao->getMainconsultant($ticket->id_ticket);

												if ($assigned != null){
													?>
													<a data-toggle="collapse" href="#collapse-<?=$idx?>">
														<strong><?=$main[0]->name?></strong>
														<span class="caret"></span>
													</a>
													<br>
													<?php
												} else {
													?>
													<strong><?=$main[0]->name?></strong>
													<?php
												}
												echo '<div class="collapse" id="collapse-' . $idx . '">';
												foreach ($assigned as $assign) {
													echo $assign->name . "<br>";
												}
												echo "</div>";
												$idx++;
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
							}
							?>
						</tbody>
					</table>
				<!-- </div> -->
			</div>
		</div>
	</div>

<?php 
	$dao = new UserDAO;
	$id_area = $dao->getUserAreaId($id_user);
	if ($user->checkProfile(array(2, 3)) || $id_area[0]->area_user == 2){

		$dao = new TicketDAO;
		$tickets = $dao->getAllOpenTickets();
?>

	<div id="all-tickets" class="tab-pane fade">

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Todos os chamados abertos</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="table-responsive">
					<table class="table table-striped table-hover table-condensed data-table" width="100%">
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
								$read = null;
								//Verificar consultores já atribuidos
								$dao = new AssignDAO;
								$assigned = $dao->getAssignedByTicket($ticket->id_ticket);
								$main = $dao->getMainconsultant($ticket->id_ticket);
								if ($main == null && $ticket->id_category != 8){
									$read = "ticket-read";
								} else {
									$read = "";
								}
								?>
								<tr class="<?=$read?>">
									<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->id_ticket?></a></td>
									<td><a href="visualizar-chamado.php?id=<?=$ticket->id_ticket?>&token=<?=$_SESSION['token']?>"><?=$ticket->subject_ticket?></a></td>
									<td>
										<a data-toggle="modal" data-target="#modal-customer" class="info-toggle info-toggle-customer" href="#" data-company="<?=$ticket->company?>" data-name="<?=$ticket->name?>" data-email="<?=$ticket->email?>" data-alt-email="<?=$ticket->alternative_email?>" data-phone="<?=$ticket->phone?>" data-role="<?=$ticket->role?>">
											<?=$ticket->name?> 
										</a>
									</td>
									<td>
										<a data-toggle="modal" data-target="#modal-company" class="info-toggle info-toggle-company" href="#" data-name="<?=$ticket->company?>" data-address="<?=$ticket->comp_address?>" data-bairro="<?=$ticket->comp_bairro?>" data-city="<?=$ticket->comp_city?>" data-cep="<?=$ticket->comp_cep?>" data-main-contact="<?=$ticket->comp_main_contact?>" data-email="<?=$ticket->comp_email?>" data-phone="<?=$ticket->comp_phone?>" data-cellphone="<?=$ticket->comp_cellphone?>">
											<?=$ticket->company?>
										</a>
									</td>
									<td>
										<?php

												if ($main == null){
													echo "<b><i>Não atribuído</i></b>";
												} else if($assigned != null) {
													?>
													<a data-toggle="collapse" href="#collapse-<?=$idx?>">
														<strong><?=$main[0]->name?></strong>
														<span class="caret"></span>
													</a>
													<br>
													<?php
												} else {
													?>
													<strong><?=$main[0]->name?></strong>
													<?php
												}

												echo '<div class="collapse" id="collapse-' . $idx . '">';
												foreach ($assigned as $assign) {
													echo $assign->name . "<br>";
												}
												echo "</div>";
												$idx++;
											
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
		</div>
	</div>
</div>

<?php 
}
require 'modals/company-info-modal.php';
require 'modals/customer-info-modal.php';
?>

</div> <!-- //content // -->


<script type="text/javascript">
	$('.data-table').DataTable({
		"colReorder" : true ,
		"order" : [[0, "desc"]] ,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 100 ,
        "responsive": true
    });

    $toggleInfoCustomer = $(".info-toggle-customer");
	$toggleInfoCompany = $(".info-toggle-company");

	$toggleInfoCustomer.click(function(event){
		$("#modal-title-customer").html($(this).data("name"));
		$("#modal-company-customer").html($(this).data("company"));
		$("#modal-email-customer").html($(this).data("email"));
		$("#modal-alt-email-customer").html($(this).data("alt-email"));
		$("#modal-phone-customer").html($(this).data("phone"));
		$("#modal-role-customer").html($(this).data("role"));
	});

	$toggleInfoCompany.click(function(event){
		$("#modal-title-company").html($(this).data("name"));
		$("#modal-address-company").html($(this).data("address"));
		$("#modal-bairro-company").html($(this).data("bairro"));
		$("#modal-city-company").html($(this).data("city"));
		$("#modal-cep-company").html($(this).data("cep"));
		$("#modal-main-contact-company").html($(this).data("main-contact"));
		$("#modal-email-company").html($(this).data("email"));
		$("#modal-phone-company").html($(this).data("phone"));
		$("#modal-cellphone-company").html($(this).data("cellphone"));
	});
</script>
<?
require 'footers/main-footer.php';
?>