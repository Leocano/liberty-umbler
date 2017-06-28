<?php 

// nova-empresa.php
//  Página para que o admin cadastre novas empresas

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Nova Empresa</h1>
	</div>
</div>

<form name="form-nova-empresa" id="form-nova-empresa" action="p-nova-empresa.php" method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-nome">Nome da empresa *</label>
			<input type="text" name="txt-nome" id="txt-nome" maxlength="60" class="form-control" placeholder="Digite o nome" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-endereco">Endereço</label>
			<input type="text" name="txt-endereco" id="txt-endereco" class="form-control" placeholder="Digite o endereço" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-bairro">Bairro</label>
			<input type="text" name="txt-bairro" id="txt-bairro" class="form-control" placeholder="Digite o bairro" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cidade">Cidade</label>
			<input type="text" name="txt-cidade" id="txt-cidade" class="form-control" placeholder="Digite a cidade" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cep">CEP</label>
			<input type="text" name="txt-cep" id="txt-cep" data-mask="00000-000" class="form-control" placeholder="Digite seu CEP" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-contato-principal">Nome do contato principal</label>
			<input type="text" name="txt-contato-principal" id="txt-contato-principal" class="form-control" placeholder="Digite o nome do contato principal" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-email">Email *</label>
			<input type="email" name="txt-email" id="txt-email" maxlength="40" class="form-control" placeholder="Digite o email" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-endereco">Telefone</label>
			<input type="text" name="txt-telefone" id="txt-telefone" class="form-control" placeholder="Digite o telefone" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-celular">Celular</label>
			<input type="text" name="txt-celular" id="txt-celular" class="form-control" placeholder="Digite o celular" required>
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
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">
	$btnSubmit = $("#btn-submit");
	$formNovaEmpresa = $("#form-nova-empresa");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formNovaEmpresa.ajaxSubmit({
			url : 'p-nova-empresa.php' ,
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
				// alert(status);
			}
		});
	});

	// $('#txt-nome').filter_input({regex:'^[a-zA-Zà-õÀ-Õ0-9 ]*$'});
	$('#txt-celular').filter_input({regex:'[0-9 ]'}); 
	$('#txt-telefone').filter_input({regex:'[0-9 ]'});
</script>

<?
require 'footers/main-footer.php';
?>