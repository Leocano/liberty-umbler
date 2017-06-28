<?php 

// editar-empresa.php
//  Página para que o admin edite empresas

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

$id = $_GET['id'];

$dao = new CompanyDAO;
$company = $dao->getCompanyById($id);

Token::generateToken();
?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Empresa</h1>
	</div>
</div>

<form name="form-editar-empresa" id="form-editar-empresa" action="p-editar-empresa.php" method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-nome">Nome da empresa *</label>
			<input type="text" name="txt-nome" id="txt-nome" maxlength="60" class="form-control" placeholder="Digite o nome" required value="<?=$company[0]->name_company?>">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-endereco">Endereço</label>
			<input type="text" name="txt-endereco" id="txt-endereco" class="form-control" placeholder="Digite o endereço" required value="<?=$company[0]->address?>">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-bairro">Bairro</label>
			<input type="text" name="txt-bairro" id="txt-bairro" class="form-control" placeholder="Digite o bairro" required value="<?=$company[0]->bairro_company?>">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cidade">Cidade</label>
			<input type="text" name="txt-cidade" id="txt-cidade" class="form-control" placeholder="Digite a cidade" required value="<?=$company[0]->city_company?>">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cep">CEP</label>
			<input type="text" name="txt-cep" id="txt-cep" data-mask="00000-000" class="form-control" placeholder="Digite seu CEP" required value="<?=$company[0]->cep_company?>">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-contato-principal">Nome do contato principal</label>
			<input type="text" name="txt-contato-principal" id="txt-contato-principal" class="form-control" placeholder="Digite o nome do contato principal" required value="<?=$company[0]->main_contact_company?>">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-email">Email *</label>
			<input type="email" name="txt-email" id="txt-email" maxlength="40" class="form-control" placeholder="Digite o email" value="<?=$company[0]->email?>" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-endereco">Telefone</label>
			<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" value="<?=$company[0]->phone_company?>" placeholder="Digite o telefone" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-celular">Celular</label>
			<input type="text" name="txt-celular" id="txt-celular" class="form-control" value="<?=$company[0]->cellphone_company?>" placeholder="Digite o celular" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
	<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>">

	<div class="row">
		<div class="col-xs-12">
			<button id="btn-submit" type="submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar
			</button>
			<a class="btn btn-lg btn-danger" href="gerenciar-empresas.php">
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
require 'scripts/jquery-mask.php';
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">
	$btnSubmit = $("#btn-submit");
	$formEditarEmpresa = $("#form-editar-empresa");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formEditarEmpresa.ajaxSubmit({
			url : 'p-editar-empresa.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					location.href = "gerenciar-empresas.php";
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

	$('#txt-celular').filter_input({regex:'[0-9 ]'}); 
	$('#txt-telefone').filter_input({regex:'[0-9 ]'}); 
	// $('#txt-cargo').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
</script>

<?
require 'footers/main-footer.php';
?>