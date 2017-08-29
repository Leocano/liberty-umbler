<?php 

// home.php
//  Página principal do SC

require 'headers/cp-header.php';
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="plugins/clockpicker/src/clockpicker.css">

<div class="col-xs-12 well well-lg">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="h3">CP</h2>
		</div>
		<div class="col-xs-12">
			<button data-toggle="modal" data-target="#modal-cp-timekeeping" id="btn-cp-add" type="button" class="btn btn-default">
				Registrar Ponto
			</button>
			<button data-toggle="modal" data-target="#modal-cp-report" type="button" class="btn btn-default">
				Folha de Ponto
			</button>
			<!-- <button data-toggle="modal" data-target="#modal-cp-extra" type="button" class="btn btn-default">
				Registrar Hora Extra
			</button> -->
		</div>
	</div>
</div>

<?php 
	$db = Database::getInstance();
	$db->query(
		"
		SELECT
			time.id_cp,
			time.type_cp,
			DATE_FORMAT(time.date_cp_timekeeping, '%d/%m/%Y') as date,
			time.entry_time,
			time.exit_time,
			time.break_start,
			time.break_end,
			TIMEDIFF(time.extra_end, extra_start) as diff,
			user.name,
			time.justification,
			time.approved,
			user.id_user
		FROM
			tb_cp_timekeeping   time,
			tb_users            user
		WHERE
			user.id_user = time.id_user
		AND
			MONTH(time.date_cp_timekeeping) = MONTH(CURDATE())
		AND
			user.id_user = ?
		ORDER BY
			time.date_cp_timekeeping
		"
		,
		array(
			$user->getIdUser()
		)
	);

	$results = $db->getResults();
?>

<div class="col-xs-12 well well-lg">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="h3">CP</h2>
		</div>
		<div class="col-xs-12">
			<div class="table-responsive table-responsive-all">
				<table class="table table-striped table-hover">
					<thead>
						<th>Data</th>
						<th>Nome</th>
						<th>Tipo</th>
						<th>Entrada</th>
						<th>Início do almoço</th>
						<th>Fim do almoço</th>
						<th>Saída</th>
						<th>Horas extras</th>
						<th>Aprovada</th>
						<th>Justificativa</th>
						<th></th>
					</thead>
					<tbody>
					<?php
						foreach($results as $result) {
							?>
							<tr id="approve-<?=$result->id_cp?>">
								<td><?=$result->date?></td>
								<td><?=$result->name?></td>
								<td><?=$result->type_cp?></td>
								<td><?=$result->entry_time?></td>
								<td><?=$result->break_start?></td>
								<td><?=$result->break_end?></td>
								<td><?=$result->exit_time?></td>
								<td><?=$result->diff?></td>
								<td>
									<?php
										if ($result->approved == 1) {
											echo "<i class='fa fa-check'></i>";
										} else {
											echo "<i class='fa fa-exclamation-triangle'></i>";
										}
									?>
								</td>
								<td><?=$result->justification?></td>
								<td>
									<?php
										if (($result->is_extra == 1 && $result->approved == 0) || $result->is_extra == 0 ){
									?>
										<button data-info='<?=json_encode($result)?>' data-id="<?=$result->id_cp?>" class="btn btn-default btn-edit-cp">
											Editar
										</button>
										<button data-id="<?=$result->id_cp?>" class="btn btn-default">
											Excluir
										</button>
									<?php
										}
									?>
								</td>
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

<?php
	if ($user->checkProfile(array(2, 3))){
		$db->query(
			"
			SELECT
				time.id_cp,
				time.type_cp,
				DATE_FORMAT(time.date_cp_timekeeping, '%d/%m/%Y') as date,
				time.entry_time,
				time.exit_time,
				time.break_start,
				time.break_end,
				user.name,
				time.justification
			FROM
				tb_cp_timekeeping   time,
				tb_users            user
			WHERE
				approved = 0
			AND
				user.id_user = time.id_user
			"
		);
		$results = $db->getResults();

		?>
		<div class="col-xs-12 well well-lg">
			<div class="row">
				<div class="col-xs-12">
					<h2 class="h3">CP 2</h2>
				</div>
				<div class="col-xs-12">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
								<th>Data</th>
								<th>Nome</th>
								<th>Tipo</th>
								<th>Entrada</th>
								<th>Início do almoço</th>
								<th>Fim do almoço</th>
								<th>Saída</th>
								<th>Justificativa</th>
								<th></th>
							</thead>
							<tbody>
							<?php
							foreach($results as $result) {
								?>
								<tr id="adm-approve-<?=$result->id_cp?>">
									<td><?=$result->date?></td>
									<td><?=$result->name?></td>
									<td><?=$result->type_cp?></td>
									<td><?=$result->entry_time?></td>
									<td><?=$result->break_start?></td>
									<td><?=$result->break_end?></td>
									<td><?=$result->exit_time?></td>
									<td><?=$result->justification?></td>
									<td>
										<button data-id="<?=$result->id_cp?>" class="btn btn-default btn-approval">
											Aprovar
										</button>
									</td>
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
		<?php
	}
?>

<?php
require 'modals/cp-apontamento-modal.php';
require 'modals/cp-relatorio-modal.php';
require 'modals/cp-extra-modal.php';
require 'modals/cp-editar-ponto-modal.php';
require 'scripts/main-script.php';
require 'scripts/bootstrap-select.php';
require 'scripts/jquery-ui.php';
require 'scripts/clockpicker.php';
require 'scripts/ajax-form.php';
require 'scripts/animate.php';
require 'scripts/bootstrap-notify.php';
?>
<script>
	$btn_cp_timekeeping = $("#btn-cp-timekeeping");
	$btn_cp_extra = $("#btn-cp-extra");
	$btn_cp_report = $("#btn-cp-report");
	$btn_approve = $(".btn-approval");
	$btn_edit_cp = $(".btn-edit-cp");
	$btn_edit_cp_submit = $("#btn-cp-edit-submit");
	$check_extra = $("#check-extra");

	$btn_edit_cp.click(function(event){
		event.preventDefault();
		var info = $(this).data('info');
		$("#slt-type-edit").val(info['type_cp']);
		$("#txt-date-edit").val(info['date']);
		$("#txt-entry-edit").val(info['entry_time']);
		$("#txt-exit-edit").val(info['exit_time']);
		$("#txt-break-start-edit").val(info['break_start']);
		$("#txt-break-end-edit").val(info['break_end']);
		$("#slt-user-edit").selectpicker('val', info['id_user']);
		$("#txt-id-cp").val(info['id_cp']);

		$("#modal-cp-edit").modal("toggle");
	});

	$check_extra.on("click", function(event){
		$("#extra-fields").toggleClass("hidden");
	});

	$btn_edit_cp_submit.click(function(event) {
		event.preventDefault();
		$("#form-cp-edit").ajaxSubmit({
			type: 'post',
			url: 'p-cp-editar.php',
			success: function(response) {
				console.log(response);
			}
		});
	});

	$btn_approve.click(function(event){
		event.preventDefault();
		var id_cp = $(this).data('id');
		$.ajax({
			type: 'post',
			url: 'p-cp-aprovar.php',
			data: {
				id_cp: id_cp
			},
			success: function(response) {
				response = $.parseJSON(response);
				$("#adm-approve-" + id_cp).remove();
				$.notify({
					// options
					message: response['msg']
				},{
					// settings
					type: 'success' ,
					placement: {
						from: "bottom",
						align: "right"
					}
				});
			}
		});
	});

	$btn_cp_timekeeping.click(function(event){
		event.preventDefault();
		$("#form-cp-timekeeping").ajaxSubmit({
			url: 'p-cp-apontamento.php',
			type: 'post',
			success: function(status) {
				status = $.parseJSON(status);
				if(status['status'] == "failed") {
					$.notify({
						// options
						message: status['msg']
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else {
					$.notify({
						// options
						message: status['msg']
					},{
						// settings
						type: 'success' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
					$("#modal-cp-timekeeping").modal("toggle");
				}
			}
		});
	});

	$btn_cp_extra.click(function(event){
		event.preventDefault();
		$("#form-cp-extra").ajaxSubmit({
			url: 'p-cp-extra.php',
			type: 'post',
			success: function(status) {
				status = $.parseJSON(status);
				if(status['status'] == "failed") {
					$.notify({
						// options
						message: status['msg']
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else {
					$.notify({
						// options
						message: status['msg']
					},{
						// settings
						type: 'success' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
					$("#modal-cp-extra").modal("toggle");
				}
			}
		});
	});

	$( function() {
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	});

	$( function() {
		$( ".monthpicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'mm/yy',
			onClose: function(dateText, inst) { 
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});
	});

	$.datepicker.regional['pt-BR'] = {
		closeText: 'Fechar',
		prevText: '&#x3c;Anterior',
		nextText: 'Pr&oacute;ximo&#x3e;',
		currentText: 'Hoje',
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
		'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
		'Jul','Ago','Set','Out','Nov','Dez'],
		dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
		dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};

	$.datepicker.setDefaults($.datepicker.regional['pt-BR']);
	$('.clockpicker').clockpicker({
		donetext: 'Pronto!',
		autoclose: true
	});
</script>
<?php
require 'footers/main-footer.php';
?>