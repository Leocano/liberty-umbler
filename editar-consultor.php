<?php

// editar-consultor.php
//  Página para que o admin edite os dados de um consultor

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

$id = $_GET['id'];

$dao = new ConsultantDAO;

$consultant = $dao->getConsultantById($id);
?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Consultor</h1>
	</div>
</div>


<div class="row">
		<div class="col-xs-12 subtitle">
			<h2 class="h3">Detalhes pessoais</h2>
		</div>
	</div>

	<form name="form-editar-consultor" id="form-editar-consultor" action="p-editar-consultor.php" method="post">
		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-nome">Nome *</label>
				<input type="text" name="txt-nome" id="txt-nome" maxlength="60" class="form-control" placeholder="Digite o nome" value="<?=$consultant[0]->name?>" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-codigo">Código do funcionário</label>
				<input type="text" name="txt-codigo" id="txt-codigo" min="1" class="form-control" value="<?=$consultant[0]->code?>" placeholder="Digite o código">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-cargo">Cargo *</label>
				<input type="text" name="txt-cargo" id="txt-cargo" maxlength="30" class="form-control" placeholder="Digite o cargo" value="<?=$consultant[0]->role?>" required>
			</div>


			<div class="col-xs-12 col-sm-6 form-group">
				<label>Perfil</label>
				<div class="row">
					<select class="col-xs-12 selectpicker" name="slt-perfil" data-live-search="true">
						<?php
						$dao = new ProfileDAO;
						$profiles = $dao->getAllProfiles();
						$selected = null;

						foreach ($profiles as $profile) {

							if ($profile->id_profile == $consultant[0]->id_profile){
								$selected = "selected='selected'";
							}

							?>
							<option value="<?=$profile->id_profile;?>" <?=$selected?>><?=$profile->desc_profile;?></option>
							<?php
							$selected = null;
						}
						?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label>Área</label>
				<div class="row">
					<select class="col-xs-12 selectpicker" name="slt-area" data-live-search="true">
						<?php
						$dao = new AreaDAO;
						$areas = $dao->getAllAreas();
						$selected = null;

						foreach ($areas as $area) {

							if ($area->id_area == $consultant[0]->area_user){
								$selected = "selected='selected'";
							}

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
						$checked = "";
						if ($type->id_contract == $consultant[0]->id_contract){
							$checked = "checked";
						}

						?>
						<div class="radio">
						  <label>
						    <input type="radio" name="opt-contract" value="<?=$type->id_contract?>" <?=$checked?> >
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
				<input type="email" name="txt-email" id="txt-email" maxlength="40" class="form-control" placeholder="Digite o email" value="<?=$consultant[0]->email?>" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-email-alt">Email Alternativo</label>
				<input type="email" name="txt-email-alt" id="txt-email-alt" maxlength="40" class="form-control" value="<?=$consultant[0]->alternative_email?>" placeholder="Digite o email alternativo">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-celular">Celular</label>
				<input type="text" name="txt-celular" id="txt-celular" class="form-control" placeholder="Digite o celular" value="<?=$consultant[0]->cellphone?>" data-mask="(00) 00000-0000">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-telefone">Telefone</label>
				<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" placeholder="Digite o telefone" value="<?=$consultant[0]->phone?>" data-mask="(00) 0000-0000">
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
				<input type="text" name="txt-custo" id="txt-custo" maxlength="40" class="form-control" value="<?=$consultant[0]->cost?>" placeholder="Digite o custo por hora">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 form-group">
				<hr>
			</div>
		</div>

		<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
		<input type="hidden" name="id" id="id" value="<?=$id?>">

		<div class="row">
			<div class="col-xs-12">
				<button id="btn-submit" type="submit" class="btn btn-lg btn-success">
					<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar
				</button>
				<a class="btn btn-lg btn-danger" href="gerenciar-consultores.php">
					<i class="fa fa-times"></i> &nbsp;
					Cancelar
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
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">
	$btnSubmit = $("#btn-submit");
	$formEditarConsultor = $("#form-editar-consultor");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formEditarConsultor.ajaxSubmit({
			url : 'p-editar-consultor.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					// $.notify({
					// 	// options
					// 	message: 'Alterações salvas com sucesso!' 
					// },{
					// 	// settings
					// 	type: 'success' ,
					// 	placement: {
					// 		from: "bottom",
					// 		align: "right"
					// 	}
					// });
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
				}
			}
		});
	});

	$('#txt-nome').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
	$('#txt-cargo').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
	// $('#txt-codigo').filter_input({regex:'^(0|[1-9][0-9]*)$'}); 
	$('#txt-custo').mask('000,00', {reverse: true});
</script>

<?php
require 'footers/main-footer.php';
?>