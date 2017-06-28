<?php

// editar-clientes.php
//  Página para que o admin edite os dados de um cliente

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

$id = $_GET['id'];

$dao = new CustomerDAO;

$customer = $dao->getCustomerById($id);

$customer_login = $dao->getCustomerLogin($id);

?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Cliente</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Detalhes do contato</h2>
	</div>
</div>

<form name="form-editar-cliente" id="form-editar-cliente" action="p-editar-cliente.php" method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-nome">Nome *</label>
			<input type="text" name="txt-nome" id="txt-nome" maxlength="200" class="form-control" placeholder="Digite o nome" value="<?=$customer[0]->name?>" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-email">Email *</label>
			<input type="email" name="txt-email" id="txt-email" maxlength="100" class="form-control" placeholder="Digite o email" value="<?=$customer[0]->email?>" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-email-alt">Email alternativo</label>
			<input type="email" name="txt-email-alt" id="txt-email-alt" maxlength="100" class="form-control" value="<?=$customer[0]->alternative_email?>" placeholder="Digite o email alternativo">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-telefone">Telefone</label>
			<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" placeholder="Digite o telefone" value="<?=$customer[0]->phone?>" data-mask="(00) 0000-0000">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-celular">Celular</label>
			<input type="text" name="txt-celular" id="txt-celular" class="form-control" placeholder="Digite o celular" value="<?=$customer[0]->cellphone?>" data-mask="(00) 00000-0000">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cargo">Cargo *</label>
			<input type="text" name="txt-cargo" id="txt-cargo" maxlength="100" class="form-control" value="<?=$customer[0]->role?>" placeholder="Digite o cargo">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Empresa</label>
			<div class="row">
				<select class="col-xs-12 selectpicker" name="slt-empresa" data-live-search="true">
					<?php
					$dao = new CompanyDAO;
					$companies = $dao->getAllCompanies();
					$selected = null;

					foreach ($companies as $company) {

						if ($company->id_company == $customer[0]->id_company){
							$selected = "selected='selected'";
						}

						?>
						<option value="<?=$company->id_company;?>" <?=$selected?>><?=$company->name_company;?></option>
						<?php
						$selected = null;
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Esse cliente pode visualizar:</label>
			<?php 
				$dao = new ViewDAO;
				$views = $dao->getAllViews();
				$checked = null;
				foreach ($views as $view) {

					if($view->id_view == $customer[0]->id_view){
						$checked = "checked";
					}

					?>
					<div class="radio">
						<label>
					    	<input type="radio" name="opt-visualizar" id="opt-visualizar-<?=$view->id_view?>" value="<?=$view->id_view?>" <?=$checked?>>
					    	<?=htmlentities($view->desc_view)?>
					  	</label>
					</div>
					<?php
					$checked = "";
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
			<h2 class="h3">Detalhes de login</h2>
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-nome-login">Nome de login</label>
			<input type="text" name="txt-nome-login" id="txt-nome-login" class="form-control" placeholder="Digite o nome de login" value="<?=$customer_login[0]->login?>" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-senha">Senha</label>
			<input type="password" name="txt-senha" id="txt-senha" class="form-control" maxlength="20" placeholder="Digite a senha" value="<?=$customer_login[0]->password?>">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-confirmar-senha">Confirmar a senha</label>
			<input type="password" name="txt-confirmar-senha" id="txt-confirmar-senha" maxlength="20" class="form-control" placeholder="Digite a senha novamente" value="<?=$customer_login[0]->password?>" required>
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
			<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar Alterações
			</button>
			<a class="btn btn-lg btn-danger" href="gerenciar-clientes.php">
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

<script>
	$btnSubmit = $("#btn-submit");
	$formEditarCliente = $("#form-editar-cliente");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formEditarCliente.ajaxSubmit({
			url : 'p-editar-cliente.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					location.href = "gerenciar-clientes.php";
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
				} else if(status == "password"){
					$.notify({
						// options
						message: 'Confirme a senha corretamente!' 
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

	// $('#txt-nome').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
	// $('#txt-cargo').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
</script>

<?
require 'footers/main-footer.php';
?>