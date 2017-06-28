<?php 

// novo-cliente.php
//  Página para que o admin cadastre novos clientes

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
?>


	<div class="row">
		<div class="col-xs-12 title">
			<h1>Novo Cliente</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 subtitle">
			<h2 class="h3">Detalhes do contato</h2>
		</div>
	</div>

	<form name="form-novo-cliente" id="form-novo-cliente" action="p-novo-cliente.php" method="post">
		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-nome">Nome *</label>
				<input type="text" name="txt-nome" id="txt-nome" maxlength="200" class="form-control" placeholder="Digite o nome" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-email">Email *</label>
				<input type="email" name="txt-email" id="txt-email" maxlength="200" class="form-control" placeholder="Digite o email" required>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-email-alt">Email alternativo</label>
				<input type="email" name="txt-email-alt" id="txt-email-alt" maxlength="200" class="form-control" placeholder="Digite o email alternativo">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-telefone">Telefone</label>
				<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" placeholder="Digite o telefone" data-mask="(00) 0000-0000">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-celular">Celular</label>
				<input type="text" name="txt-celular" id="txt-celular" class="form-control" placeholder="Digite o celular" data-mask="(00) 00000-0000">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-cargo">Cargo *</label>
				<input type="text" name="txt-cargo" id="txt-cargo" maxlength="100" class="form-control" placeholder="Digite o cargo">
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

						foreach ($companies as $company) {
							?>
							<option value="<?=$company->id_company;?>"><?=$company->name_company;?></option>
							<?php
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
					$checked = "checked";
					foreach ($views as $view) {
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
				<input type="text" name="txt-nome-login" id="txt-nome-login" class="form-control" placeholder="Digite o nome de login">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-senha">Senha</label>
				<input type="password" name="txt-senha" id="txt-senha" class="<?php if(isset($_GET['error']) && $_GET['error'] == 1){echo "input-error";} ?>  form-control" maxlength="20" placeholder="Digite a senha">
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-confirmar-senha">Confirmar a senha</label>
				<input type="password" name="txt-confirmar-senha" id="txt-confirmar-senha" maxlength="20" class="<?php if(isset($_GET['error']) && $_GET['error'] == 1){echo "input-error";} ?> form-control" placeholder="Digite a senha novamente">
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
				<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
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
	$formNovoCliente = $("#form-novo-cliente");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formNovoCliente.ajaxSubmit({
			url : 'p-novo-cliente.php' ,
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
				} else if(status == "size"){
					$.notify({
						// options
						message: 'A senha deve ter no mínimo 8 caracteres!' 
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
	// $('#txt-cargo').filter_input({regex:'^[a-zA-Z á-õÁ-Õ]*$'}); 
</script>

<?
require 'footers/main-footer.php';
?>