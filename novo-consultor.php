<?php 

// novo-consultor.php
//  Página para que o admin cadastre novos consultores

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
?>


	<div class="row">
		<div class="col-xs-12 title">
			<h1>Novo Consultor</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 subtitle">
			<h2 class="h3">Detalhes pessoais</h2>
		</div>
	</div>

	<form name="form-novo-consultor" id="form-novo-consultor" action="p-novo-consultor.php" method="post">
		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-nome">Nome *</label>
				<input type="text" name="txt-nome" id="txt-nome" maxlength="60" class="form-control" placeholder="Digite o nome" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-codigo">Código do funcionário</label>
				<input type="text" name="txt-codigo" id="txt-codigo" min="1" class="form-control" placeholder="Digite o código">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-cargo">Cargo *</label>
				<input type="text" name="txt-cargo" id="txt-cargo" maxlength="30" class="form-control" placeholder="Digite o cargo" required>
			</div>


			<div class="col-xs-12 col-sm-6 form-group">
				<label>Perfil</label>
				<div class="row">
					<select class="col-xs-12 selectpicker" name="slt-perfil" data-live-search="true">
						<?php
						$dao = new ProfileDAO;
						$profiles = $dao->getAllProfiles();

						foreach ($profiles as $profile) {
							?>
							<option value="<?=$profile->id_profile;?>"><?=$profile->desc_profile;?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-xs-12 col-sm-6 form-group">
				<label>Area</label>
				<div class="row">
					<select class="col-xs-12 selectpicker" name="slt-area" data-live-search="true">
						<?php
							$dao = new AreaDAO;
							$areas = $dao->getAllAreas();
							$selected = null;

							foreach ($areas as $area) {
								?>
								<option value="<?=$area->id_area;?>" <?=$selected?> ><?=$area->desc_area;?></option>
								<?php
								$selected = null;
							}
						?>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 form-group">
				<label>Tipo de Contratação</label>
				<?php 
					$dao = new ConsultantContractDAO;

					$contract_types = $dao->getAllConsultantContracts();

					foreach ($contract_types as $type) {

						?>
						<div class="radio">
						  <label>
						    <input type="radio" name="opt-contract" value="<?=$type->id_contract?>" >
						    <?=$type->desc_contract?>
						  </label>
						</div>
						<?php
					}
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<hr>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Informações para contato</h2>
			</div>
		</div>


		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-email">Email *</label>
				<input type="email" name="txt-email" id="txt-email" maxlength="40" class="form-control" placeholder="Digite o email" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-email-alt">Email Alternativo</label>
				<input type="email" name="txt-email-alt" id="txt-email-alt" maxlength="40" class="form-control" placeholder="Digite o email alternativo">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-celular">Celular</label>
				<input type="text" name="txt-celular" id="txt-celular" class="form-control" placeholder="Digite o celular" data-mask="(00) 00000-0000">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-telefone">Telefone</label>
				<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" placeholder="Digite o telefone" data-mask="(00) 0000-0000">
			</div>
		</div>


		<div class="row">
			<div class="col-xs-12">
				<hr>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Detalhes do custo</h2>
			</div>
		</div>


		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-custo">Custo por hora (R$)</label>
				<input type="text" name="txt-custo" id="txt-custo" maxlength="40" class="form-control" placeholder="Digite o custo por hora">
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
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">
	$btnSubmit = $("#btn-submit");
	$formNovoConsultor = $("#form-novo-consultor");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formNovoConsultor.ajaxSubmit({
			url : 'p-novo-consultor.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					location.href = "gerenciar-consultores.php";
				} else if(status == "empty"){
					$.notify({
						// options
						message: 'Preencha todos os campos obrigatórios!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else if(status == "email"){
					$.notify({
						// options
						message: 'Este email já foi cadastrado!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				}
			}
		});
	});

	$('#txt-nome').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
	$('#txt-cargo').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
	// $('#txt-codigo').filter_input({regex:'^(0|[1-9][0-9]*)$'}); 
	$('#txt-custo').mask('000,00', {reverse: true});
</script>

<?
require 'footers/main-footer.php';
?>