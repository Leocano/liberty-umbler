<?php 

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

$proposal = json_decode($_GET['j_proposal']);

if ($proposal->start_proposal != null){
	$proposal->start_proposal = strtotime($proposal->start_proposal);
	$proposal->start_proposal = date("d/m/Y", $proposal->start_proposal);
}

if ($proposal->end_proposal != null){
	$proposal->end_proposal = strtotime($proposal->end_proposal);
	$proposal->end_proposal = date("d/m/Y", $proposal->end_proposal);
}

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Proposta</h1>
	</div>
</div>

<form name="form-nova-proposta" id="form-nova-proposta" action="p-editar-proposta.php" method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Empresa</label>
			<select data-title="Selecione a empresa" required class="form-control selectpicker" name="slt-empresa" data-live-search="true">
				<?php
				$dao = new CompanyDAO;
				$companies = $dao->getAllCompanies();

				foreach ($companies as $company) {
					$checked ="";
					if ($company->id_company == $proposal->id_company){
						$checked = "selected";
					}
					?>
					<option value="<?=$company->id_company;?>" <?=$checked?> ><?=$company->name_company;?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Responsável</label>
			<select class="form-control selectpicker" name="slt-user" data-live-search="true">
				<?php
					$dao = new ConsultantDAO;
					$managers = $dao->getAllManagers();

					foreach ($managers as $manager) {
						$checked = "";
						if ($manager->id_user == $proposal->id_user){
							$checked = "selected";
						}
						?>
						<option value="<?=$manager->id_user;?>" <?=$checked?> ><?=$manager->name;?></option>
						<?php
					}
					?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Tipo da Proposta</label>
			<select class="form-control selectpicker" name="slt-proposal-type" data-live-search="true">
				<?php
					$dao = new ProposalTypeDAO;
					$types = $dao->getAllProposalTypes();

					foreach ($types as $type) {
						$checked = "";
						if ($type->id_proposal_type == $proposal->id_proposal_type){
							$checked = "selected";
						}
						?>
						<option value="<?=$type->id_proposal_type;?>" <?=$checked?> ><?=$type->desc_proposal_type?></option>
						<?php
					}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Proposta</label>
			<input required class="form-control" type="text" name="txt-proposal" placeholder="Digite a proposta" value="<?=$proposal->name_proposal?>">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Descrição da Proposta</label>
			<input required class="form-control" type="text" name="txt-desc-proposal" placeholder="Digite a descrição da proposta" value="<?=$proposal->desc_proposal?>">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Status</label>
			<select class="form-control" name="slt-status-proposal">
				<?php 
					$proposal_status = array(
						0 => array(
							1
						,	"Ativa"
						),
						1 => array(
							0
						,	"Inativa"
						)
					);

					foreach ($proposal_status as $proposal_stat) {
						$checked = "";
						if ($proposal_stat[0] == $proposal->active_proposal){
							$checked = "selected";
						}
						?>
						<option value="<?=$proposal_stat[0]?>" <?=$checked?> ><?=$proposal_stat[1]?></option>
						<?php
					}
				?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Horas</label>
			<input type="text" class="form-control" placeholder="Digite a quantidade de horas da proposta" name="txt-hours" id="txt-hours" value="<?=$proposal->hours_proposal?>">
		</div>

		<div class="col-xs-12 col-sm-3 form-group">
			<label>De</label>
			<input class="datepicker date-input form-control" type="text" name="slt-start-proposal" placeholder="Clique para alterar" value="<?=$proposal->start_proposal?>" readonly>
		</div>	
		<div class="col-xs-12 col-sm-3 form-group">
			<label>Até</label>
			<input class="datepicker date-input form-control" type="text" name="slt-end-proposal" placeholder="Clique para alterar" value="<?=$proposal->end_proposal?>" readonly>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-3 form-group">
			<label>Porcentagem do Saldo</label>
			<input type="text" class="form-control" name="txt-percentage" id="txt-percentage" value="<?=$proposal->percentage_proposal?>" placeholder="Digite a porcentagem de horas para o mês seguinte">
		</div>

		<div class="col-xs-12 col-sm-3 form-group">
			<label>Meses para zerar saldo</label>
			<input type="text" class="form-control" name="txt-months-to-reset" id="txt-months-to-reset" value="<?=$proposal->months_proposal?>" placeholder="Digite a a quantidade de meses para zerar o saldo">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
	<input type="hidden" name="id-proposal" id="id-proposal" value="<?=$proposal->id_proposal?>">

	<div class="row">
		<div class="col-xs-12">
			<button id="btn-submit" type="submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar
			</button>
			<a href="gerenciar-propostas.php" class="btn btn-danger btn-lg">
				<i class="fa fa-times"></i> &nbsp;
				Voltar
			</a>
		</div>
	</div>
</form>

</div> <!-- //content // -->


<?php 
require 'scripts/animate.php';
require 'scripts/main-script.php';
require 'scripts/jquery-mask.php';
require 'scripts/jquery-filter.php';
require 'scripts/bootstrap-select.php';
require 'scripts/ajax-form.php';
require 'scripts/jquery-ui.php';
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">

	$('#txt-hours').filter_input({regex:'[0-9 ]'});
	$('#txt-months-to-reset').filter_input({regex:'[0-9 ]'});
    $('#txt-percentage').filter_input({regex:'[0-9 ]'});

	$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
	});

	$.datepicker.regional['pt-BR'] = {
        closeText: 'Fechar',
        prevText: '&#x3c;Anterior',
        nextText: 'Pr&oacute;ximo&#x3e;',
        currentText: 'Hoje',
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
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
</script>

<?
require 'footers/main-footer.php';
?>