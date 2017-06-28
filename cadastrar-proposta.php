<?php 

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="row">
	<div class="col-xs-12 title">
		<h1>Nova Proposta</h1>
	</div>
</div>

<form name="form-nova-proposta" id="form-nova-proposta" action="p-cadastrar-proposta.php" method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Empresa</label>
			<select data-title="Selecione a empresa" required class="form-control selectpicker" name="slt-empresa" data-live-search="true">
				<?php
				$dao = new CompanyDAO;
				$companies = $dao->getAllCompanies();

				foreach ($companies as $company) {
					?>
					<option value="<?=$company->id_company;?>"><?=$company->name_company;?></option>
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
						?>
						<option value="<?=$manager->id_user;?>"><?=$manager->name;?></option>
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
						?>
						<option value="<?=$type->id_proposal_type;?>"><?=$type->desc_proposal_type?></option>
						<?php
					}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Proposta</label>
			<input required class="form-control" type="text" name="txt-proposal" placeholder="Digite a proposta">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Descrição da Proposta</label>
			<input required class="form-control" type="text" name="txt-desc-proposal" placeholder="Digite a descrição da proposta">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Status</label>
			<select class="form-control" name="slt-status-proposal">
				<option value="1">Ativa</option>
				<option value="0">Inativa</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Horas</label>
			<input type="text" class="form-control" placeholder="Digite a quantidade de horas da proposta" name="txt-hours" id="txt-hours">
		</div>

		<div class="col-xs-12 col-sm-3 form-group">
			<label>De</label>
			<input class="datepicker date-input form-control" type="text" name="slt-start-proposal" placeholder="Clique para alterar" readonly>
		</div>	
		<div class="col-xs-12 col-sm-3 form-group">
			<label>Até</label>
			<input class="datepicker date-input form-control" type="text" name="slt-end-proposal" placeholder="Clique para alterar" readonly>
		</div>	
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-3 form-group">
			<label>Porcentagem do Saldo</label>
			<input type="text" class="form-control" name="txt-percentage" id="txt-percentage" placeholder="Digite a porcentagem de horas para o mês seguinte">
		</div>

		<div class="col-xs-12 col-sm-3 form-group">
			<label>Meses para zerar saldo</label>
			<input type="text" class="form-control" name="txt-months-to-reset" id="txt-months-to-reset" placeholder="Digite a a quantidade de meses para zerar o saldo">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">

	<div class="row">
		<div class="col-xs-12">
			<button id="btn-submit" type="submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar
			</button>
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
require 'scripts/jquery-mask.php';
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