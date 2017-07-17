<?php 

// abrir-chamado.php

require 'headers/main-header.php';


if($user->checkProfile(array(1, 5))){
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

<form name="form-abrir-chamado" id="form-abrir-chamado" method="post" action="p-abrir-chamado-consultor.php" enctype="multipart/form-data">
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
					<option value="<?=$module->id_module?>" ><?=$module->desc_module?></option>
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
			<label>Categoria</label>
			<select class="form-control" name="slt-category" id="slt-category">
				<?php 
				$dao = new CategoryDAO;
				$categories = $dao->getAllCategories();

				foreach ($categories as $category) {
					?>
					<option value="<?=$category->id_category?>"><?=$category->desc_category?></option>
					<?php
				}
				?>
			</select>
		</div>

		<div class="col-xs-12 col-sm-6 form-group">
			<label>Subcategoria (opcional)</label>
			<select class="selectpicker form-control" name="slt-subcategory" id="slt-subcategory" data-title="Selecione uma subcategoria (opcional)">
				<!-- <option disabled selected value>Selecione uma subcatergoria (opcional)</option> -->
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Empresa *</label>
			<select required data-title="Selecione uma empresa" class="selectpicker form-control" id="slt-company" name="slt-company" data-live-search="true">
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
			<label>Usuário *</label>
			<select required data-title="Selecione um usuário" class="selectpicker form-control" id="slt-user" name="slt-user" data-live-search="true">
				
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Tipo da Proposta</label>	
			<select required class="selectpicker form-control" id="slt-proposal-type" name="slt-proposal-type" data-live-search="true">
				<?php 
				$dao = new ProposalTypeDAO;
				$proposal_types = $dao->getAllProposalTypes();

				foreach ($proposal_types as $proposal_type) {
				?>
					<option value="<?=$proposal_type->id_proposal_type?>"><?=$proposal_type->desc_proposal_type?></option>
				<?php
				}
				?>
			</select>
		</div>

		<div class="col-xs-12 col-sm-6 form-group">
			<label>Proposta *</label>	
			<select required data-title="Selecione a proposta" class="selectpicker form-control" id="slt-proposal" name="slt-proposal" data-live-search="true">
				
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

	<div class="row">
		<div class="col-xs-12 form-group">
			<label for="txt-desc">Descrição *</label>
			<textarea name="txt-desc"></textarea>
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
			<input type="file" name="attachments[]" id="file-input" multiple>
			<!-- <p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p> -->
			<p class="help-block">Limite: 3MB</p>
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
	<input type="hidden" name="id-user" id="id-user" value="<?=$id_user?>">
	<!-- <input type="hidden" name="id-creator" value="<?=$id_user?>">
	<input type="hidden" name="id-company" value="<?=$id_company?>"> -->
	<input type="hidden" name="name-creator" id="name-creator" value="<?=$name_creator?>">

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Abrir Chamado
			</button>
		</div>
	</div>
</form>
</div> <!-- //content // -->

<form id="form-change-users" name="form-change-users">
	<input type="hidden" name="id-change-users" id="id-change-users">
</form>

<form id="form-change-proposal" name="form-change-proposal">
	<input type="hidden" name="id-company-proposal" id="id-company-proposal">
	<input type="hidden" name="id-proposal-type" id="id-proposal-type">
</form>

<?php 
	require 'scripts/main-script.php';
	require 'scripts/ajax-form.php';
	require 'scripts/bootstrap-select.php';
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
		$("#slt-priority").removeAttr("disabled");
	  	$("#form-abrir-chamado").ajaxSubmit({
	  		url : 'p-abrir-chamado-consultor.php' ,
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

$("#slt-category").change(function(){
	var category_val = $(this).val();
	$.ajax({
		url : 'p-mudar-subcategoria.php',
		type : 'POST',
		data : {
			id_category : $("#slt-category").val()
		},
		success : function(subcategories){
			$("#slt-subcategory > option").remove();
			var new_subcategories = $.parseJSON(subcategories);
			for (var i = new_subcategories.length - 1; i >= 0; i--) {
				$("#slt-subcategory").append("<option value='" + new_subcategories[i].id_subcategory + "'>" + new_subcategories[i].desc_subcategory + "</option>")
			}
			$("#slt-subcategory").selectpicker("refresh");
		}
	});

	if (category_val == 3){
		$("#slt-priority").val(2);
		$("#slt-priority").attr("disabled", "true");
	} else {
		$("#slt-priority").removeAttr("disabled");
	}
});

$("#id-proposal-type").val($("#slt-proposal-type").val());

$("#slt-company").change(function(){
	$("#id-change-users").val($(this).val());
	$("#form-change-users").ajaxSubmit({
		url : 'p-change-users.php' ,
		type : 'post' ,
		success : function(status){
			$("#slt-user > option").remove();
			var new_users = $.parseJSON(status);
			for (var i = new_users.length - 1; i >= 0; i--) {
				$("#slt-user").append("<option value=" + new_users[i].id_user + " data-name='" + new_users[i].name + "'>" + new_users[i].name + " (" + new_users[i].name_company + ")</option>");
			}
			$("#slt-user").selectpicker("refresh");
		}
	});

	$("#id-company-proposal").val($("#slt-company").val());
	$("#form-change-proposal").ajaxSubmit({
		url : 'p-change-proposal.php' ,
		type : 'post' ,
		success : function(status){
			$("#slt-proposal > option").remove();
			var new_proposals = $.parseJSON(status);
			for (var i = new_proposals.length - 1; i >= 0; i--) {
				$("#slt-proposal").append("<option value=" + new_proposals[i].id_proposal + ">" + new_proposals[i].name_proposal + "</option>");
			}
			$("#slt-proposal").selectpicker("refresh");
		}
	});
});

$("#slt-proposal-type").change(function(){

	$("#id-proposal-type").val($("#slt-proposal-type").val());
	$("#form-change-proposal").ajaxSubmit({
		url : 'p-change-proposal.php' ,
		type : 'post' ,
		success : function(status){
			// alert(status);
			$("#slt-proposal > option").remove();
			var new_proposals = $.parseJSON(status);
			for (var i = new_proposals.length - 1; i >= 0; i--) {
				$("#slt-proposal").append("<option value=" + new_proposals[i].id_proposal + ">" + new_proposals[i].name_proposal + "</option>");
			}
			$("#slt-proposal").selectpicker("refresh");
		}
	});
});

$("#slt-user").change(function(){
	$current = $(this).val();
	$new_val = $(this).find("option[value=" + $current + "]").data("name");
	$("#name-creator").val($new_val);
});

$btnAnexar = $("#btn-anexar");
$btnAnexar.click(function(event){
	event.preventDefault();
	$("#form-abrir-chamado").ajaxSubmit({
		url : 'p-adicionar-anexo-chamado.php' ,
		type : 'post' ,
		success : function(status){
			if (status == "size"){
				alert("O limite de arquivos é 3MB");
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
	$id = $("#id-user").val();
	$name = $(this).data("file");

	// console.log($id);
	// console.log($name);

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
