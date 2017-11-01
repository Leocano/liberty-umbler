<?php 

// abrir-chamado.php

require 'headers/main-header.php';

// if($user->checkProfile(array(1, 5))){
// 	Redirect::to("index.php");
// 	exit();
// }

// Token::generateToken();
// $token = $_SESSION['token'];

$id_ticket = $_GET['id'];
$id_user = $user->getIdUser();

$dao = new TicketDAO;
$ticket = $dao->getProductTicketById($id_ticket);
// var_dump($ticket);
?>

<script src='plugins/tinymce/js/tinymce/tinymce.min.js'></script>
<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Chamado</h1>
	</div>
</div>

<form name="form-editar-chamado" id="form-editar-chamado" method="post" action="p-editar-chamado-produto.php" enctype="multipart/form-data">
	<div class="row">
        <div class="col-xs-12 col-sm-6 form-group">
            <label for="slt-product">Produto *</label>
            <select class="form-control" name="slt-product" id="slt-product" required>
                <option disabled selected value>Selecione o produto</option>
                <?php 
                $dao = new ProductsDAO;
                $products = $dao->getAllProducts();

                foreach ($products as $product) {
                    $selected = "";
                    if($product->id_product == $ticket[0]->id_product){
                        $selected = "selected";
                    }
                    ?>
                    <option <?=$selected?> value="<?=$product->id_product?>" ><?=$product->name_product?></option>
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
			<label>Empresa</label>
			<select required data-title="Selecione uma empresa" class="selectpicker form-control" id="slt-company" name="slt-company" data-live-search="true">
				<?php
				$dao = new CompanyDAO;
				$companies = $dao->getProductCompanies();

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
				$attachments = $dao->getAttachmentsByProductTicketId($id_ticket);

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
						 - <a href="p-deletar-anexo-produto.php?id=<?=$attachment->id_attachment?>&ticket=<?=$id_ticket?>&token=<?=$_SESSION['token']?>">(Excluir)</a>
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
			<a class="btn btn-lg btn-danger" href="visualizar-chamado-produto.php?id=<?=$ticket[0]->id_ticket?>">
				<i class="fa fa-times"></i>&nbsp;&nbsp;Voltar
			</a>
		</div>
	</div>
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


    $("#form-editar-chamado").submit();
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
