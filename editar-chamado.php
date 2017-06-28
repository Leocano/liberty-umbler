<?php 

// abrir-chamado.php

require 'headers/main-header.php';


if($user->checkProfile(array(1, 5))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();
$token = $_SESSION['token'];

$id_ticket = $_GET['id'];
$id_user = $user->getIdUser();

$dao = new TicketDAO;
$ticket = $dao->getTicketById($id_ticket);

$dao = new SubcategoryDAO();
$id_subcategory = $dao->getSubcategoryById($ticket[0]->id_subcategory);
?>

<script src='plugins/tinymce/js/tinymce/tinymce.min.js'></script>
<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Chamado</h1>
	</div>
</div>

<form name="form-editar-chamado" id="form-editar-chamado" method="post" action="p-editar-chamado.php" enctype="multipart/form-data">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label for="slt-module">Módulo</label>
			<select class="form-control" name="slt-module" id="slt-module">
				<?php 
				$dao = new ModuleDAO;
				$modules = $dao->getAllModules();

				foreach ($modules as $module) {
					$selected = "";
					if ($ticket[0]->id_module == $module->id_module){
						$selected = "selected";
					}

					?>
					<option value="<?=$module->id_module?>" <?=$selected?> ><?=$module->desc_module?></option>
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
				$selected = "";

				foreach ($priorities as $priority) {

					if ($ticket[0]->id_priority == $priority->id_priority){
						$selected = "selected";
					}

					?>
					<option value="<?=$priority->id_priority?>" <?=$selected?> ><?=$priority->desc_priority?></option>
					<?php
					$selected = "";
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
				$selected = "";

				foreach ($categories as $category) {

					if ($ticket[0]->id_category == $category->id_category){
						$selected = "selected";
					}

					?>
					<option value="<?=$category->id_category?>" <?=$selected?> ><?=$category->desc_category?></option>
					<?php
					$selected = "";
				}
				?>
			</select>
		</div>

		<div class="col-xs-12 col-sm-6 form-group">
			<label>Subcategoria (opcional)</label>
			<select class="selectpicker form-control" name="slt-subcategory" id="slt-subcategory" data-title="Selecione uma subcategoria (opcional)">
				
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Empresa</label>
			<select required data-title="Selecione uma empresa" class="selectpicker form-control" id="slt-company" name="slt-company" data-live-search="true">
				<?php
				$dao = new CompanyDAO;
				$companies = $dao->getAllCompanies();

				foreach ($companies as $company) {
					$checked = "";
					if ($ticket[0]->id_company == $company->id_company){
						$checked = "selected";
					}
					?>
					<option value="<?=$company->id_company;?>" <?=$checked?> ><?=$company->name_company;?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Usuário</label>
			<select required data-title="Selecione um usuário" class="selectpicker form-control" id="slt-user" name="slt-user" data-live-search="true">
				
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Tipo da Proposta</label>	
			<select required class="selectpicker form-control" id="slt-proposal-type" name="slt-proposal-type" data-live-search="true">
				<?php 

				$dao = new ProposalDAO;
				$new_proposal = $dao->getProposalById($ticket[0]->id_proposal);

				$dao = new ProposalTypeDAO;
				$proposal_types = $dao->getAllProposalTypes();

				foreach ($proposal_types as $proposal_type) {
					$checked = "";
					if ($new_proposal[0]->id_proposal_type == $proposal_type->id_proposal_type) {
						$checked = "selected";
					}
				?>
					<option value="<?=$proposal_type->id_proposal_type?>" <?=$checked?> ><?=$proposal_type->desc_proposal_type?></option>
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
			<input type="text" class="form-control" value="<?=$ticket[0]->cost_center?>" name="txt-cost-center" id="txt-cost-center" placeholder="Digite o centro de custo (opcional)">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Número Externo (opcional)</label>
			<input class="form-control" type="text" value="<?=$ticket[0]->external_number?>" name="txt-external" id="txt-external" placeholder="Digite o número externo (opcional)">
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Consultor Principal</label>
			<select class="selectpicker form-control" name="slt-main" id="slt-main" data-title="Selecione um consultor" data-live-search="true">
				<?php 

				$dao = new AssignDAO;
				$main_consultant = $dao->getMainConsultant($id_ticket);

				$dao = new ConsultantDAO;
				$consultants = $dao->getAllConsultants();

				$checked = "";

				foreach ($consultants as $consultant) {

					if ($main_consultant[0]->id_user == $consultant->id_user) {
						$checked = "selected";
					}

					?>
					<option value="<?=$consultant->id_user?>" <?=$checked?> ><?=$consultant->name?></option>
					<?php
					$checked = "";
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Participantes</label>
			<select class="selectpicker form-control" name="slt-users[]" id="slt-users" multiple data-live-search="true" title="Escolha os consultores participantes">
				<?php 

				$dao = new AssignDAO;
				$assigned = $dao->getAssignedByTicket($id_ticket);

				$checked = "";
				$i = 0;

				foreach ($consultants as $consultant) {

					foreach ($assigned as $assign) {
						if ($assign->id_user == $consultant->id_user){
							$checked = "selected";
							$i++;
						}
					}

					?>

					<option value="<?=$consultant->id_user?>" <?=$checked?> ><?=$consultant->name?></option>

					<?php
					$checked = "";
				}
				?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<label for="txt-subject">Assunto do Chamado *</label>
			<input type="text" class="form-control" value="<?=$ticket[0]->subject_ticket?>" name="txt-subject" id="txt-subject" placeholder="Digite o assunto do chamado" required>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<label for="txt-desc">Descrição *</label>
			<textarea name="txt-desc"><?=$ticket[0]->desc_ticket?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row">
		<!-- <div class="col-xs-12 form-group" id="file-div">
			<label>Adicionar anexos</label>
			<input type="file" name="attachments[]" multiple>
			<p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p>
		</div> -->

		<div class="col-xs-12 form-group" id="file-div">
			<label>Anexos</label>
			<input type="file" name="attachments[]" id="file-input" multiple>
			<!-- <p class="help-block">Insira todos os anexos de uma vez, segurando a tecla <kbd>Ctrl</kbd> e clicando sobre cada um</p> -->
			<p class="help-block">Limite: 3MB</p>
			<button class="btn btn-sm btn-primary" id="btn-anexar">
				<i class="fa fa-plus"></i> &nbsp; Anexar
			</button>
		</div>

		<div class="col-xs-12 form-group">
			<label>Anexos Existentes</label><br>
			<?php 
				$dao = new AttachmentDAO;
				$attachments = $dao->getAttachmentsByTicketId($id_ticket);

				if ($attachments == null){
					?>
					<p>
						Não existem anexos.
					</p>
					<?php 
				} else {
					foreach ($attachments as $attachment) {
						?>
						<i class="fa fa-paperclip"></i> &nbsp;<a href="<?=$attachment->path_attachment?>" download><?=$attachment->name_attachment?></a>
						 - <a href="p-deletar-anexo.php?id=<?=$attachment->id_attachment?>&ticket=<?=$id_ticket?>&token=<?=$_SESSION['token']?>">(Excluir)</a>
						<br>
						<?php
					}
				}
			?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<input type="hidden" name="token" value="<?=$token?>">
	<input type="hidden" name="id-ticket" value="<?=$id_ticket?>">

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Salvar Alterações
			</button>
			<a class="btn btn-lg btn-danger" href="visualizar-chamado.php?id=<?=$ticket[0]->id_ticket?>">
				<i class="fa fa-times"></i>&nbsp;&nbsp;Voltar
			</a>
		</div>
	</div>
</form>

<form id="form-change-proposal" name="form-change-proposal">
	<input type="hidden" name="id-company-proposal" id="id-company-proposal" value="<?=$ticket[0]->id_company?>">
	<input type="hidden" name="id-proposal-type" id="id-proposal-type">
</form>

<form id="form-change-users" name="form-change-users">
	<input type="hidden" name="id-change-users" id="id-change-users" value="<?=$ticket[0]->id_company?>">
</form>

</div> <!-- //content // -->

<?php 
	require 'scripts/main-script.php';
	require 'scripts/ajax-form.php';
	require 'scripts/bootstrap-select.php';
?>


<script>

<?php 
	if (isset($ticket[0]->id_proposal)){
		echo "var id_proposal = " . $ticket[0]->id_proposal . ";";
	} else {
		echo "var id_proposal = null;";
	}
?>
var checked = "";
var id_creator = <?=$ticket[0]->id_creator?>;

$("#btn-submit").click(function(event){
	event.preventDefault();

	tinymce.activeEditor.uploadImages(function(success) {
	  $.post('ajax/post.php', tinymce.activeEditor.getContent()).done(function() {
	    console.log("Upload realizado.");
	  });
	});
	
	$("#slt-priority").removeAttr("disabled");

	$("#form-editar-chamado").ajaxSubmit({
		url: 'p-check-locked-users.php' ,
		type: 'post',
		success : function(status){
			if (status != "success"){
				alert(status);
				return false;
			} else {
				$("#form-editar-chamado").submit();
			}
		}
	});
});

$("#form-change-users").ajaxSubmit({
	url : 'p-change-users.php' ,
	type : 'post' ,
	success : function(status){
		$("#slt-user > option").remove();
		var new_users = $.parseJSON(status);
		var checked = "";
		for (var i = new_users.length - 1; i >= 0; i--) {
			checked = "";
			if (id_creator == new_users[i].id_user){
				checked = "selected";
			}
			$("#slt-user").append("<option " + checked + " value=" + new_users[i].id_user + " data-name='" + new_users[i].name + "'>" + new_users[i].name + " (" + new_users[i].name_company + ")</option>");
		}
		$("#slt-user").selectpicker("refresh");
	}
});

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
			checked = "";
			<?php if ($id_subcategory != null){
			?>
				if (new_subcategories[i].id_subcategory == <?=$id_subcategory[0]->id_subcategory?>){
					checked = "selected";
				}
			<?php 
			}
			?>
			$("#slt-subcategory").append("<option " + checked + " value='" + new_subcategories[i].id_subcategory + "'>" + new_subcategories[i].desc_subcategory + "</option>");
		}
		$("#slt-subcategory").selectpicker("refresh");
	}
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

$("#id-proposal-type").val($("#slt-proposal-type").val());
	$("#form-change-proposal").ajaxSubmit({
		url : 'p-change-proposal.php' ,
		type : 'post' ,
		success : function(status){
			// alert(status);
			$("#slt-proposal > option").remove();
			var new_proposals = $.parseJSON(status);
			for (var i = new_proposals.length - 1; i >= 0; i--) {
				checked = ""
				if (id_proposal == new_proposals[i].id_proposal){
					checked = "selected";
				}
				$("#slt-proposal").append("<option " + checked + " value=" + new_proposals[i].id_proposal + ">" + new_proposals[i].name_proposal + "</option>");
			}
			$("#slt-proposal").selectpicker("refresh");
		}
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

$btnAnexar = $("#btn-anexar");
$btnAnexar.click(function(event){
	event.preventDefault();
	$("#form-editar-chamado").ajaxSubmit({
		url : 'p-adicionar-anexo-chamado.php' ,
		type : 'post' ,
		success : function(status){
			// alert(status);
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

</script>

<?php 
	require 'footers/main-footer.php';
?>
