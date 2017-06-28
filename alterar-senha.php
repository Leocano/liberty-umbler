<?php 

require 'headers/main-header.php';

if(!$user->checkProfile(array(5))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
?>


	<div class="row">
		<div class="col-xs-12 title">
			<h1>Alterar Senha</h1>
		</div>
	</div>

	<form name="form-password" id="form-password">
		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-senha">Senha atual *</label>
				<input type="password" name="txt-senha-atual" id="txt-senha-atual" class="form-control" maxlength="20" placeholder="Digite a senha atual" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-senha">Nova senha *</label>
				<input type="password" name="txt-nova-senha" id="txt-nova-senha" class="form-control" minlength="10" maxlength="20" placeholder="Digite a nova senha" required>
			</div>
			<div class="col-xs-12 col-sm-6 form-group">
				<label for="txt-senha">Confirme a nova senha *</label>
				<input type="password" name="txt-confirmar-senha" id="txt-confirmar-senha" minlength="10" maxlength="20" class="form-control" placeholder="Digite a nova senha novamente" required>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 form-group">
				<hr>
			</div>
		</div>

		<input type="hidden" name="token" id="token" value="<?=$_SESSION['token']?>">
		<input type="hidden" name="id-user" id="id-user" value="<?=$user->getIdUser()?>">

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
require 'scripts/jquery-filter.php';
require 'scripts/bootstrap-select.php';
require 'scripts/ajax-form.php';
require 'scripts/bootstrap-notify.php';
?>

<script type="text/javascript">
	$btnSubmit = $("#btn-submit");
	$formPassword = $("#form-password");

	$btnSubmit.click(function(event){
		event.preventDefault();
		$formPassword.ajaxSubmit({
			url : 'p-alterar-senha.php',
			type : 'POST',
			success : function(status){
				if (status == "success"){
					location.href = "home.php";
				} else {
					$.notify({
						// options
						message: status 
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
</script>

<?
require 'footers/main-footer.php';
?>