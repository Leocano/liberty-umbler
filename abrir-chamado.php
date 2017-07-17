<?php 

// abrir-chamado.php

require 'headers/main-header.php';


if($user->checkProfile(array(1))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
$token = $_SESSION['token'];

$id_user = $user->getIdUser();
$company = $user->getCompany();
$name_creator = $user->getName();
$id_company = $company->getIdCompany();
?>

<script src='plugins/tinymce/js/tinymce/tinymce.min.js'></script>
<div class="row">
	<div class="col-xs-12 title">
		<h1>Abrir Chamado</h1>
	</div>
</div>

<form name="form-abrir-chamado" id="form-abrir-chamado" method="post" action="p-abrir-chamado.php" enctype="multipart/form-data">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="slt-module">Módulo *</label>
			<select class="form-control" name="slt-module" id="slt-module" required>
				<option disabled selected value>Selecione o módulo</option>
				<?php 
				$dao = new ModuleDAO;
				$modules = $dao->getAllModules();

				foreach ($modules as $module) {
					?>
					<option value="<?=$module->id_module?>"><?=$module->desc_module?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="slt-priority">Prioridade</label>
			<select class="form-control" name="slt-priority" id="slt-priority">
				<?php 
				$dao = new PriorityDAO;
				$priorities = $dao->getAllPriorities();

				foreach ($priorities as $priority) {
					?>
					<option value="<?=$priority->id_priority?>"><?=$priority->desc_priority?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="txt-cost-center">Centro de Custo (opcional)</label>
			<input type="text" class="form-control" name="txt-cost-center" id="txt-cost-center" placeholder="Digite o centro de custo (opcional)">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Número Externo (opcional)</label>
			<input class="form-control" type="text" name="txt-external" id="txt-external" placeholder="Digite o número externo (opcional)">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<label for="txt-subject">Assunto do Chamado *</label>
			<input type="text" class="form-control" name="txt-subject" id="txt-subject" placeholder="Digite o assunto do chamado" required>
		</div>
	</div>

	<!-- <div class="row">
		<div class="col-xs-12 form-group">
			<div class="alert alert-danger">
				<strong>Atenção: </strong>
				Existe um limite para imagens copiadas e coladas diretamente na descrição (CTRL C + CTRL V).<br>
				Estamos trabalhando para melhorar esta funcionalidade.<br>
				Se deseja incluir imagens em seu chamado, você pode inserí-las clicando no botão "inserir" da caixa de texto abaixo ou como um anexo.
			</div>
		</div>
	</div> -->

	<div class="row">
		<div class="col-xs-12 form-group">
			<label for="txt-desc">Descrição *</label>
			<textarea name="txt-desc">1. Descritivo da solicitação – Qual o problema?<br><br><br>2. Quais os passos para reproduzir o problema?<br><br><br>3. Evidências do erro (se possível com imagens)<br><br><br>4. Outras informações que julgar necessário</textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group" id="file-div">
			<label>Anexos</label>
			<input type="file" id="file-input" name="attachments[]" multiple>
			<!-- <p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p> -->
			<p class="help-block">Limite: 3 MB</p>
			<button class="btn btn-sm btn-primary" id="btn-anexar">
				<i class="fa fa-plus"></i> &nbsp; Anexar
			</button>
		</div>

		<?php 
		if (isset($_GET['error']) && $_GET['error'] == 'attachment'){
		?>
			<div class="col-xs-12">
				<div class="alert alert-danger">
				  	<strong>Atenção!</strong> O limite de tamanho para cada anexo é de 3 MB.
				</div>
			</div>
		<?php 
		}
		?>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<input type="hidden" name="token" value="<?=$token?>">
	<input type="hidden" name="id-creator" id="id-creator" value="<?=$id_user?>">
	<input type="hidden" name="id-company" value="<?=$id_company?>">
	<input type="hidden" name="name-creator" value="<?=$name_creator?>">

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Abrir Chamado
			</button>
		</div>
	</div>
</form>
</div> <!-- //content // -->

<?php 
	require 'scripts/main-script.php';
	require 'scripts/ajax-form.php';
	require 'scripts/animate.php';
	require 'scripts/bootstrap-notify.php';
?>


<script>
tinymce.init({
	selector: 'textarea' ,
	language_url : 'plugins/tinymce/js/tinymce/langs/pt_BR.js',
	plugins: [
	    'anchor jbimages'
	] ,
	toolbar: 
		"insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link jbimages",
	relative_urls: false ,
	height: 300 ,
	images_upload_url: 'p-tinymce-upload.php',
    images_upload_base_path: '/qas/liberty/',
	elementpath : false
});

$("#btn-submit").click(function(event){
	event.preventDefault();
	$(this).attr("disabled", "disabled");
	tinymce.activeEditor.uploadImages(function(success) {
		tinyMCE.get("txt-desc").save();
	  	$("#form-abrir-chamado").ajaxSubmit({
	  		url : 'p-abrir-chamado.php' ,
	  		type : 'POST',
	  		success : function(json_status){
	  			var status = $.parseJSON(json_status);
	  			// alert(json_status);
	  			if (status[0] == "success"){
	  				location.href = "visualizar-chamado.php?id=" + status[1] + "&token=" + status[2];
					$("#btn-submit").removeAttr("disabled");
	  			} else {
	  				$.notify({
						// options
						message: status[0] 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
					$("#btn-submit").removeAttr("disabled");
	  			}
	  		}
	  	});
	});
});

$btnAnexar = $("#btn-anexar");
$btnAnexar.click(function(event){
	event.preventDefault();
	$("#form-abrir-chamado").ajaxSubmit({
		url : 'p-adicionar-anexo-chamado-cliente.php' ,
		type : 'post' ,
		success : function(status){
			if (status == "size"){
				alert("O limite para arquivos é de 3MB");
			} else {
				$files = $("#file-input")[0].files;

				for (var i = 0; i < $files.length; i++){
					$("#file-div").append("<p class='attachment-name'>" + $files[i].name + " - <a class='delete-attachment' href='#' data-file='" + $files[i].name + "'>(Excluir)</a></p>");
				}

				$("#file-input").val("");
			}
		}
	});
});

$("#file-div").on("click", ".delete-attachment", function(){
	event.preventDefault();
	$current = $(this);
	$id = $("#id-creator").val();
	$name = $(this).data("file");

	console.log($id);
	console.log($name);

	$("#form-abrir-chamado").ajaxSubmit({
		url : 'p-deletar-anexo-temp.php?id=' + $id + '&attachment=' + $name ,
		type : 'get' ,
		success : function(status){
			$current.parent().remove();
		}
	});
});

</script>

<?php 
	require 'footers/main-footer.php';
?>
