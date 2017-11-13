<?php 

// visualizar-chamado.php
//  Página para verificar dados do chamado

require 'headers/main-header.php';


$id = $_GET['id'];

$dao = new TicketDAO;
$ticket = $dao->getProductTicketById($id);

$profile = $user->getProfile();
$id_profile = $profile->getIdProfile();

//Verificação da view (1)
$id_user = $user->getIdUser();
$id_creator = $ticket[0]->id_creator;
$id_user_type = $user->getIdUserType();
$view = $user->getView();
$id_view = $view->getIdView();

if ($id_view == 1 && $id_user != $id_creator && $id_user_type == 2){
	Redirect::to("index.php");
	exit;
}

//Serializando o ticket
$subject = $ticket[0]->subject_ticket;
$id_ticket = $ticket[0]->id_ticket;
$created = $ticket[0]->created;
$name = $ticket[0]->name;
$company = $ticket[0]->name_company;
$status = $ticket[0]->desc_status;
// $category = $ticket[0]->desc_category;
$priority = $ticket[0]->desc_priority;
// $module = $ticket[0]->desc_module;
$desc_ticket = $ticket[0]->desc_ticket;
$color = $ticket[0]->color;
$cost_center = $ticket[0]->cost_center;
$comp_email = $ticket[0]->comp_email;
$email = $ticket[0]->email;
$proposal = $ticket[0]->id_proposal;
$new_id_status = $ticket[0]->id_status;
$external_number = $ticket[0]->external_number;
// $id_category = $ticket[0]->id_category;

//Calculo de tempo total
$total_time = $dao->getTotalHours($id_ticket);
$total_minutes = $dao->getTotalMinutes($id_ticket);

while ($total_minutes[0]->total_minutes >= 60) {
	$total_time[0]->total_hours = $total_time[0]->total_hours + 1;
	$total_minutes[0]->total_minutes = $total_minutes[0]->total_minutes - 60;
}

$total_hours = $total_time[0]->total_hours;
$total_minutes = $total_minutes[0]->total_minutes;

$dao = new ProposalDAO;
$proposal = $dao->getProposalById($proposal);
$proposal = $proposal[0]->name_proposal;

//Pegar todos os consultores para atribuição
$dao = new ConsultantDAO;
$consultants = $dao->getAllConsultants();

//Verificar consultores já atribuidos
$dao = new AssignDAO;
$assigned = $dao->getAssignedByTicketProduct($id_ticket);

//Verificar o consultor principal
$main_consultant = $dao->getMainConsultantProduct($id_ticket);

Token::generateToken();

$dao = new SolutionAttachmentDAO;
$solution_attachments = $dao->getSolutionAttachmentsByProductTicket($id_ticket);

$dao = new SolutionDAO;
$solutionText = $dao->getSolutionByProductTicket($id_ticket);

$dao = new TimekeepingDAO;
$timekeeping = $dao->getTimekeepingByProductTicketId($id_ticket);

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src='plugins/tinymce/js/tinymce/tinymce.min.js'></script>

<div class="row">
	<div class="col-xs-12 title">
		<h1>
			ID
			<?=$id_ticket?> -
				<?=$subject?>
		</h1>
		<p class="help-block">
			Aberto em:
			<?=$created?>
				<br> Por:
				<a data-toggle="modal" data-target="#modal-customer" class="info-toggle info-toggle-customer" href="#" data-company="<?=$company?>"
				    data-name="<?=$name?>" data-email="<?=$ticket[0]->email?>" data-alt-email="<?=$ticket[0]->alternative_email?>" data-phone="<?=$ticket[0]->phone?>"
				    data-role="<?=$ticket[0]->role?>">
					<?=$name?>
				</a>
		</p>
	</div>
</div>


<ul class="nav nav-tabs">
	<li><a data-toggle="tab" href="#details">Detalhes</a></li>
	<?php
	if (!$user->checkProfile(array(5))){
		if (isset($_GET['timekeeping']) && $_GET['timekeeping'] == "true"){
			$active = "active";
			$in = "in";
		}
	?>
		<li class="<?=$active?>"><a data-toggle="tab" href="#timekeeping">Apontamentos</a></li>
		<?php
	}
	?>
		<li><a data-toggle="tab" href="#history">Histórico</a></li>
		<?php 
	if (isset($_GET['solution']) && $_GET['solution'] == "true"){
		$solution_active = "active";
		$solution_in = "in";
	}
	?>
		<li><a class="<?=$solution_active?>" data-toggle="tab" href="#solucao">Solução</a></li>
</ul>




<div class="tab-content">
	<?php
	if (isset($_GET['timekeeping']) || isset($_GET['solution'])){
		echo "<div id='details' class='tab-pane fade'>";
	} else {
		echo '<div id="details" class="tab-pane fade in active">';
	}
	?>
		<!-- <div id="details" class="tab-pane fade in active"> -->
		<?php 
			if (!$user->checkProfile(array(5))){
		?>
		<div class="row">
			<div class="col-xs-12">
				<div class="well well-lg">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="h4"><strong>Ações</strong></h2>
						</div>
						<div class="col-xs-12">
							<?php 
									$allowed_categories = array(1, 2, 4, 8);
									if (($solutionText == null || $solution_attachments == null) || $timekeeping == null){
										$no_close = "disabled";
										echo "<p class='help-block'>Para encerrar o chamado é necessário apontar, preencher a descrição da solução e anexar a documentação</p>";
									} else {
										$no_close = "";
									}
									$json_ticket = json_encode($ticket);
									$json_solution = json_encode($solutionText);

									?>
							<button class="btn btn-default <?=$ticket[0]->disabled?> <?=$no_close?>" <?=$ticket[0]->disabled?> <?=$no_close?> href="p-fechar-chamado-produto.php" id="btn-close-ticket">
								<i class="fa fa-lock"></i>
								&nbsp;Fechar
							</button>
							<form class="hidden" id="form-close-ticket">
								<textarea class="hidden" name="j_ticket"><?=$json_ticket?></textarea>
								<input type="hidden" name="status" value="2">
								<textarea type="hidden" name="j_solution"><?=$json_solution?></textarea>
								<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
								<input type="text" name="disabled" value="<?=$no_close?>">
							</form>
							<?php
										$dao = new ConsultantDAO;
										$consultants = $dao->getUsersByProductTicket($id_ticket);
										$no_consultant = "";
										$is_assigned = false;

										foreach ($consultants as $cons) {
											if ($cons->id_user == $id_user){
												$is_assigned = true;
												break;
											}
										}
										
										if (($consultants == null || $is_assigned != true) && !$user->checkProfile(array(2,3))){
											$no_consultant = "disabled";
										}

										$no_assign = null;
										// if ($ticket[0]->id_category == 8){
										// 	$no_assign = "disabled";
										// }

										?>
								<button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$ticket[0]->disabled?> <?=$no_consultant?> data-toggle="modal" data-target="#modal-timekeep">
									<i class="fa fa-clock-o"></i>
									&nbsp;Novo Apontamento
								</button>
								<?php 
								$new_dao = new UserDAO;
								$area_id = $new_dao->getUserAreaId($id_user);
								if ($user->checkProfile(array(3, 2)) || $area_id[0]->area_user == 5 || $user->getIdUser() == $id_creator){
									?>
									<a href="editar-chamado-produto.php?id=<?=$id_ticket?>&token=<?=$_SESSION['token']?>" class="btn btn-default <?=$ticket[0]->disabled?>">
										<i class="fa fa-pencil"></i>
										&nbsp;Editar
									</a>
									<!-- <button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$no_assign?> <?=$ticket[0]->disabled?> data-toggle="modal" data-target="#modal-atribute">
										<i class="fa fa-user-plus"></i>
										&nbsp;Atribuir
									</button> -->
									<?php 
									if ($ticket[0]->disabled == "disabled" && $user->checkProfile(array(2, 3))){
										?>
										<a class="btn btn-default" href="p-reabrir-chamado-produto.php?id=<?=$id_ticket?>&status=1&token=<?=$_SESSION['token']?>">
											<i class="fa fa-unlock"></i>
											&nbsp;Reabrir Chamado
										</a>
										<?php
									}
								}

								if ($user->checkProfile(array(3, 2)) || $area_id[0]->area_user == 5){
									?>
									<button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$no_assign?> <?=$ticket[0]->disabled?> data-toggle="modal" data-target="#modal-atribute">
										<i class="fa fa-user-plus"></i>
										&nbsp;Atribuir
									</button>
									<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
		}
		?>

		<div class="row">
			<div class="col-xs-12">
				<div class="well well-lg">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Status</strong></h2>
							<?php
								if ($user->checkProfile(array(1,2,3))){
									$status_dao = new StatusDAO;
									$new_status = $status_dao->getAllStatus();

									if ($new_id_status == 2){
										$already_closed = "disabled";
									}

									?>
								<form name="form-status" id="form-status">
									<input type="hidden" name="token" value="<?=$_SESSION['status']?>">
									<input type="hidden" name="id-ticket" value="<?=$id_ticket?>">
									<select id="slt-status" name="slt-status" class="form-control <?=$already_closed?>" <?=$already_closed?> <?=$no_consultant?>>
									<?php
									if ($new_id_status == 2){
										?>
										<option value="2" selected class="disabled" disabled >Closed</option>
										<?php
									} else {

										foreach ($new_status as $stat) {
											$selected = "";
											if($stat->id_status == $new_id_status){
												$selected = "selected";
											}
									?>
										<option value="<?=$stat->id_status?>" <?=$selected?> ><?=$stat->desc_status?></option>
									<?php
										}
									}
									?>
										</select>
								</form>
								<?php
								} else {
								?>
									<p>
										<?=$status?>
									</p>
									<?php
								}
							?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Prioridade</strong></h2>
							<p>
								<span class="<?=$color?>"><i class="fa fa-exclamation-circle"></i>&nbsp; <?=$priority?></span>
							</p>
						</div>

						<!-- <div class="clearfix"></div> -->

						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Aberto por</strong></h2>
							<p>
								<a data-toggle="modal" data-target="#modal-customer" class="info-toggle info-toggle-customer" href="#" data-company="<?=$company?>"
								    data-name="<?=$name?>" data-email="<?=$ticket[0]->email?>" data-alt-email="<?=$ticket[0]->alternative_email?>" data-phone="<?=$ticket[0]->phone?>"
								    data-role="<?=$ticket[0]->role?>">
									<?=$name?>
								</a>
							</p>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Atribuído a</strong></h2>
							<p id="txt-assigned">
								<?php 

								// if ($ticket[0]->id_category == 8){
								// 	echo "Todos";
								// } else {
									if ($main_consultant[0]->name == null){
										echo "Chamado não atribuído";
									} else {
										echo "<strong>" . $main_consultant[0]->name . "</strong><br>";
									}

									foreach ($assigned as $assign) {
										echo $assign->name . "<br>";
									}
								// }								

								?>
							</p>
						</div>

						<div class="clearfix"></div>						

						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Proposta</strong></h2>
							<p>
								<?=$proposal?>
							</p>
						</div>

						<!-- <div class="clearfix"></div> -->

						<!-- <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Email da Empresa</strong></h2>
							<p>
								<?=$comp_email?>
							</p>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Email do Cliente</strong></h2>
							<p>
								<?=$email?>
							</p>
						</div> -->
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Empresa</strong></h2>
							<p>
								<?=$company?>
							</p>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Produto</strong></h2>
							<p>
								<?=$ticket[0]->name_product?>
							</p>
						</div>
						<!-- <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
							<h2 class="h4"><strong>Total de Horas</strong></h2>
							<p>
								<?php
									echo $total_hours . 'h ' . $total_minutes . 'min';
								?>
							</p>
						</div> -->
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="well well-lg">
					<div class="row">
						<div class="col-xs-12 ticket-description">
							<h2 class="h4"><strong>Descrição</strong></h2>
							<div>
								<?=$desc_ticket?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="well well-lg">
					<div class="row">
						<div class="col-xs-12">
							<h2 class="h4"><strong>Anexos</strong></h2>

							<?php 
								$dao = new AttachmentDAO;
								$attachments = $dao->getAttachmentsByProductTicketId($id_ticket);

								if ($attachments == null){
									?>
							<p>
								Não existem anexos.
							</p>
							<?php 
								} else {
									echo "<div id='file-div'>";
									foreach ($attachments as $attachment) {
										?>
							<i class="fa fa-paperclip"></i> &nbsp;<a href="<?=$attachment->path_attachment?>" download><?=$attachment->name_attachment?></a>
							<br>
							<?php
									}
									echo "</div>";
								}

								if ($user->checkProfile(array(5))){
							?>
								<form name="form-editar-chamado" id="form-editar-chamado">
									<input type="file" name="attachments[]" id="file-input" multiple>
									<input type="hidden" name="id-ticket" value="<?=$id_ticket?>">
								</form>
								<!-- <p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p> -->
								<p class="help-block">Limite: 3MB</p>
								<button class="btn btn-sm btn-primary" id="btn-anexar">
									<i class="fa fa-plus"></i> &nbsp; Anexar
								</button>

								<?php 
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>

<div id="timekeeping" class="tab-pane fade <?=$active?> <?=$in?>">
	<div class="row">
		<div class="col-xs-12">
			<div class="well well-lg">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-sm-6">
								<h2 class="h4"><strong>Apontamentos</strong></h2>
							</div>
							<div class="col-sm-6 text-right">
								<?php
										$dao = new ConsultantDAO;
										$consultants = $dao->getUsersByProductTicket($id_ticket);
										if (($consultants == null || $is_assigned != true) && !$user->checkProfile(array(2,3))){
											$no_consultant = "disabled";
										}
										?>
									<button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$ticket[0]->disabled?> <?=$no_consultant?> data-toggle="modal" data-target="#modal-timekeep">
										<i class="fa fa-clock-o"></i>
										&nbsp;Novo Apontamento
									</button>
							</div>
						</div>
						<?php 
							$dao = new TimekeepingDAO;
							$timekeeping = $dao->getTimekeepingByProductTicketId($id_ticket);
						?>

						<div class="table-responsive">
							<table class="table table-condensed table-striped table-hover">
								<thead>
									<th>Data</th>
									<th>Representante</th>
									<th>Descrição</th>
									<?php 
										if (!$user->checkProfile(array(5))){
											echo "<th class='text-right'></th>";
										}
									?>
								</thead>
								<tbody>
									<?php 
										if ($timekeeping == null){
										?>
									<td colspan="5">Não existem Apontamentos.</td>
									<?php
										} else {
											foreach ($timekeeping as $time) {
												$timekeeping_time = explode(":", $time->hours)
												?>
										<tr>
											<td>
												<?=$time->new_date_timekeeping?>
											</td>
											<td>
												<?=$time->name?>
											</td>
											<td>
												<?=$time->desc_timekeeping?>
											</td>
											<td class="text-right">
												<?php 
																if ((!$user->checkProfile(array(5)) && $id_user == $time->id_user && $ticket[0]->id_status != 2) || $user->checkProfile(array(2, 3))){
																?>
												<button class="btn btn-sm btn-danger btn-delete-timekeeping" data-id="<?=$time->id_timekeeping?>" data-toggle="modal" data-target="#modal-delete-timekeep">
																	<i class="fa fa-trash"></i>
																</button>
												<button class="btn btn-sm btn-success btn-edit-timekeeping" data-desc="<?=$time->desc_timekeeping?>" data-cost="<?=$time->cost_timekeeping?>"
												    data-minutes="<?=$timekeeping_time[1]?>" data-hours="<?=$timekeeping_time[0]?>" data-id="<?=$time->id_timekeeping?>"
												    data-name="<?=$time->id_user?>" data-type="<?=$time->id_timekeeping_type?>" data-date="<?=$time->new_date_timekeeping?>"
												    data-toggle="modal" data-target="#modal-edit-timekeep">
																	<i class="fa fa-pencil"></i>
																</button>
												<?php
																}
															?>
											</td>
										</tr>
										<?php
											}
										}
										?>
										<!-- <tr style="background: #fff;">
											<td></td>
											<th colspan="5">
												Total:
												<?php 
													echo $total_hours . 'h ' . $total_minutes . 'min';
												?>
											</th>
										</tr> -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="history" class="tab-pane fade">
	<div class="row">
		<div class="col-xs-12">
			<div class="well well-lg">
				<div class="row">
					<div class="col-xs-12">
						<h2 class="h4"><strong>Histórico do Chamado</strong></h2>

						<?php 
								$dao = new HistoryDAO;
								$history = $dao->getHistoryByProductTicket($id_ticket);

								if ($history == null){
									?>
						<p>
							Nenhuma ação realizada.
						</p>
						<?php 
									} else {
										?>
						<table class="table table-hover table-condensed table-striped">
							<?php
											foreach ($history as $record) {
												?>
								<tr>
									<td>
										<?=$record->hist_date?> &nbsp;&nbsp;|&nbsp;&nbsp;
											<?=$record->desc_history?> <strong><?=$record->name?></strong>
									</td>
								</tr>
								<?php
											}
											?>
						</table>
						<?php
									}
							?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="solucao" class="tab-pane fade <?=$solution_active?> <?=$solution_in?>">
	<div class="row">
		<div class="col-xs-12">
			<div class="well well-lg">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<h2 class="h4"><strong>Solução</strong></h2>
					</div>

					<?php 
						if (!$user->checkProfile(array(5))){
						?>

					<div class="col-xs-12 col-sm-6 text-right">
						<button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$ticket[0]->disabled?> data-toggle="modal" data-target="#modal-solution">
								<i class="fa fa-pencil"></i>
								&nbsp;Adicionar Texto
							</button>
						<button class="btn btn-default <?=$ticket[0]->disabled?>" <?=$ticket[0]->disabled?> data-toggle="modal" data-target="#modal-solution-attachments">
								<i class="fa fa-paperclip"></i>
								&nbsp;Adicionar Anexos
							</button>
					</div>

					<?php 
						}
						?>

					<div class="col-xs-12 solution-block">
						<label>Descrição</label>
						<?php 
							// $dao = new SolutionDAO;

							// $solutionText = $dao->getSolutionByTicket($id_ticket);

							if ($solutionText == null){
								echo "<br>Nenhuma descrição disponível.";
							} else {
								?>
						<?=$solutionText[0]->desc_solution?>
							<p class="help-block">
								Por:
								<?=$solutionText[0]->name?>
							</p>
							<?php
							}
							?>
					</div>

					<div class="col-xs-12 solution-block">
						<label>Anexos</label>
						<br>

						<?php 
							// $dao = new SolutionAttachmentDAO;

							// $solution_attachments = $dao->getSolutionAttachmentsByTicket($id_ticket);

							if ($solution_attachments == null){
								echo "Nenhum anexo disponível";
							} else {
								foreach ($solution_attachments as $attachment) {
								?>
						<i class="fa fa-paperclip"></i> &nbsp;
						<a href="<?=$attachment->path_solution_attachment?>" download><?=$attachment->name_solution_attachment?></a>
						<?php
									if (!$user->checkProfile(array(5)) && $ticket[0]->disabled != "disabled"){
								?>
							-
							<a href="p-deletar-anexo-solucao-produto.php?id=<?=$attachment->id_solution_attachment?>&token=<?=$_SESSION['token']?>&ticket=<?=$id_ticket?>">Excluir&nbsp;<i class="fa fa-times"></i></a>
							<?php
									}
									echo "<br>";
								}
							}
							?>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- <input type="hidden" id="id-profile" value="<?=$id_profile?>"> -->


</div>
<!-- //content // -->

<?php
require 'modals/atribuir-chamado-modals-produto.php';
// require 'modals/apontamento-horas-modals.php';
require 'modals/produto-apontamento-modal.php';
require 'modals/solucao-modal.php';
require 'modals/customer-info-modal.php';
require 'modals/company-info-modal.php';
require 'scripts/main-script.php';
require 'scripts/bootstrap-select.php';
require 'scripts/ajax-form.php';
require 'scripts/jquery-mask.php';
require 'scripts/jquery-ui.php';
?>

	<script type="text/javascript">
		$toggleInfoCustomer = $(".info-toggle-customer");
		$toggleInfoCompany = $(".info-toggle-company");

		$toggleInfoCustomer.click(function (event) {
			$("#modal-title-customer").html($(this).data("name"));
			$("#modal-company-customer").html($(this).data("company"));
			$("#modal-email-customer").html($(this).data("email"));
			$("#modal-alt-email-customer").html($(this).data("alt-email"));
			$("#modal-phone-customer").html($(this).data("phone"));
			$("#modal-role-customer").html($(this).data("role"));
		});

		$toggleInfoCompany.click(function (event) {
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

		$btnAnexar = $("#btn-anexar");
		$btnAnexar.click(function (event) {
			event.preventDefault();
			$("#form-editar-chamado").ajaxSubmit({
				url: 'p-adicionar-anexo-visualizar.php',
				type: 'post',
				success: function (status) {
					// alert(status);
					if (status == "size") {
						alert("O limite de arquivos é 3MB");
					} else {
						location.reload();
					}
				}
			});
		});

		$("#btn-close-ticket").click(function (event) {
			event.preventDefault();
			$("#form-close-ticket").ajaxSubmit({
				url: 'p-fechar-chamado-produto.php',
				type: 'post',
				success: function () {
					location.reload();
				}
			});
		});

		$btnDeleteTimekeeping = $(".btn-delete-timekeeping");
		$deleteTimekeepingId = $("#delete-timekeeping-id");
		$btnConfirmDeleteTimekeep = $("#btn-confirm-delete-timekeep");
		$formDeleteTimekeep = $("#form-delete-timekeep");

		//Modal para deletar apontamento
		$btnDeleteTimekeeping.on("click", function (event) {
			event.preventDefault();
			$deleteTimekeepingId.val($(this).data("id"));
		});

		$btnConfirmDeleteTimekeep.click(function (event) {
			event.preventDefault();
			$formDeleteTimekeep.ajaxSubmit({
				url: 'p-deletar-apontamento-produto.php',
				type: 'POST',
				success: function () {
					location.reload();
				}
			});
		});


		//Modal para editar apontamento
		$btnEditTimekeeping = $(".btn-edit-timekeeping");
		$idEditTimekeeping = $("#id-edit-timekeeping");
		$btnEditTimekeepConfirm = $("#btn-edit-timekeep-confirm");
		$formEditTimekeep = $("#form-edit-timekeep");

		$btnEditTimekeeping.click(function (event) {
			event.preventDefault();
			$idEditTimekeeping.val($(this).data("id"));
			$('.edit-select').selectpicker('val', $(this).data('name'));
			$("#slt-type-edit").val($(this).data('type'));
			$("#datepicker-id").val($(this).data('date'));
			$("#txt-date-edit").val($(this).data('date'));
			$("#txt-hours-edit").val($(this).data('hours'));
			$("#txt-minutes-edit").val($(this).data('minutes'));
			$("#txt-cost-edit").val($(this).data('cost'));
			$("#txt-desc-edit").val($(this).data('desc'));
		});

		$btnEditTimekeepConfirm.click(function (event) {
			event.preventDefault();
			$formEditTimekeep.ajaxSubmit({
				url: 'p-editar-apontamento-produto.php',
				type: 'POST',
				success: function (status) {
					if (status == "success") {
						location.reload();
					} else {
						alert(status);
					}
				}
			});
		});


		// $("#btn-all").click(function(event){
		// 	event.preventDefault();
		// 	$("#slt-atribute").selectpicker("selectAll");
		// });

		$("#btn-remove-all").click(function (event) {
			event.preventDefault();
			$("#slt-atribute").selectpicker("deselectAll");
		});


		//Formatando a hora com 0 na frente
		$(".txt-time").change(function () {
			if ($(this).val() < 10) {
				$(this).val("0" + $(this).val());
			}
		});


		$btnAtribute = $("#btn-atribute");
		$formAtribute = $("#form-atribute");
		$txtAssigned = $("#txt-assigned");

		$btnTimekeep = $("#btn-timekeep");
		$formTimekeep = $("#form-timekeep");

		//Atribuir Chamado
		$btnAtribute.on("click", function (event) {
			event.preventDefault();
			$formAtribute.ajaxSubmit({
				url: 'p-atribuir-chamado-produto.php',
				type: 'POST',
				success: function (status) {
					if (status == "locked") {
						alert("Consultores com apontamento não podem ser removidos do chamado!");
					} else {
						location.href = "visualizar-chamado-produto.php?id=" + <?=$id_ticket?>;
					}
				}
			});
		});

		//Apontamento de horas
		$btnTimekeep.on("click", function (event) {
			event.preventDefault();
			var desc = $("#txt-desc").val();
			$("#txt-desc").val(desc.replace(/\//g, "&#47;"));
			$formTimekeep.ajaxSubmit({
				url: 'p-adicionar-apontamento-produto.php',
				data: {
					id_creator: <?=$id_creator?>
				,	subject: "<?=$subject?>"
				},
				type: 'POST',
				success: function (status) {
					if (status == "success") {
						location.href = "visualizar-chamado-produto.php?id=" + <?=$id_ticket?> + "&timekeeping=true";
					} else {
						alert(status);
						// console.log(status);
					}
				}
			});
		});

		//Descricao da Solução
		$btnSolution = $("#btn-solution");
		$formSolution = $("#form-solution");

		$btnSolution.on("click", function (event) {
			event.preventDefault();
			tinymce.activeEditor.uploadImages(function (success) {
				tinyMCE.triggerSave();
				$formSolution.ajaxSubmit({
					url: 'p-adicionar-solucao-produtos.php',
					type: 'POST',
					success: function (status) {
						if (status == "success") {
							location.href = "visualizar-chamado-produto.php?id=" + <?=$id_ticket?> + "&solution=true";
						} else {
							alert(status);
						}
					}
				});
			});
		});

		//Anexos da Solução
		$btnSolutionAttachments = $("#btn-solution-attachments");
		$formSolutionAttachments = $("#form-solution-attachments");

		$btnSolutionAttachments.on("click", function (event) {

			event.preventDefault();
			$formSolutionAttachments.ajaxSubmit({
				url: 'p-adicionar-anexo-solucao-produto.php',
				type: 'POST',
				success: function (status) {
					if (status == "success") {
						location.href = "visualizar-chamado-produto.php?id=" + <?=$id_ticket?> + "&solution=true";
					} else {
						alert(status);
					}
				}
			});
		});

		$("#slt-status").change(function (event) {
			$("#form-status").ajaxSubmit({
				url: 'p-alterar-status-produto.php',
				type: 'post'
			});
		});


		$(document).ready(function () {

			var id_profile = <?=$id_profile?>;
			console.log(id_profile);


			$(".datepicker").datepicker({
				yearRange: "-1:+0",
				changeMonth: true,
				changeYear: true
			});

			$.datepicker.regional['pt-BR'] = {
				closeText: 'Fechar',
				prevText: '&#x3c;Anterior',
				nextText: 'Pr&oacute;ximo&#x3e;',
				currentText: 'Hoje',
				monthNames: ['Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho',
					'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
				],
				monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
					'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
				],
				dayNames: ['Domingo', 'Segunda-feira', 'Ter&ccedil;a-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira',
					'Sabado'
				],
				dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
				dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
				weekHeader: 'Sm',
				dateFormat: 'dd/mm/yy',
				firstDay: 0,
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''
			};

			$.datepicker.setDefaults($.datepicker.regional['pt-BR']);

			tinymce.init({
				selector: '#text-mce',
				language_url: 'plugins/tinymce/js/tinymce/langs/pt_BR.js',
				plugins: [
					'anchor jbimages'
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages",
				relative_urls: false,
				height: 200,
				images_upload_url: 'p-tinymce-upload.php',
				images_upload_base_path: '/qas/liberty/',
				elementpath: false
			});
		});
	</script>

	<?php
require 'footers/main-footer.php';
?>