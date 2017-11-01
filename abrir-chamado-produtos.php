<?php 

require 'headers/main-header.php';

if($user->checkProfile(array(5))){
	Redirect::to("index.php");
	exit();
}
?>

<script src='plugins/tinymce/js/tinymce/tinymce.min.js'></script>
<div class="row">
	<div class="col-xs-12 title">
		<h1>Abrir Chamado - Produtos</h1>
	</div>
</div>

<form id="form-abrir-chamado" method="post" action="p-abrir-chamado-produtos.php">
    <!-- <input type="submit"> -->
    <div class="row">
        <div class="col-xs-12 col-sm-6 form-group">
            <label for="slt-product">Produto *</label>
            <select class="form-control" name="slt-product" id="slt-product" required>
                <option disabled selected value>Selecione o produto</option>
                <?php 
                $dao = new ProductsDAO;
                $products = $dao->getAllProducts();

                foreach ($products as $product) {
                    ?>
                    <option value="<?=$product->id_product?>" ><?=$product->name_product?></option>
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
			<label>Empresa *</label>
			<select required data-title="Selecione uma empresa" class="selectpicker form-control" id="slt-company" name="slt-company" data-live-search="true">
				<?php
				$dao = new CompanyDAO;
				$companies = $dao->getProductCompanies();

				foreach ($companies as $company) {
					?>
					<option value="<?=$company->id_company;?>"><?=$company->name_company;?></option>
					<?php
				}
				?>
			</select>
		</div>
		<!-- <div class="col-xs-12 col-sm-6 form-group">
			<label>Usuário *</label>
			<select required data-title="Selecione um usuário" class="selectpicker form-control" id="slt-user" name="slt-user" data-live-search="true">
				
			</select>
		</div> -->
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

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" id="btn-submit" class="btn btn-lg btn-success">
				<i class="fa fa-check"></i>&nbsp;&nbsp;Abrir Chamado
			</button>
		</div>
	</div>
</form>

<form id="form-change-users" name="form-change-users">
	<input type="hidden" name="id-change-users" id="id-change-users">
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
    
    $btnAnexar = $("#btn-anexar");
    $btnAnexar.click(function(event){
        event.preventDefault();
        $("#form-abrir-chamado").ajaxSubmit({
            url : 'p-adicionar-anexo-produtos.php' ,
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

    $("#btn-submit").click(function(event){
	event.preventDefault();
	$(this).attr("disabled", "disabled");
	tinymce.activeEditor.uploadImages(function(success) {
		tinyMCE.get("txt-desc").save();
		$("#slt-priority").removeAttr("disabled");
	  	$("#form-abrir-chamado").ajaxSubmit({
	  		url : 'p-abrir-chamado-produtos.php' ,
	  		type : 'POST',
	  		success : function(json_status){
                console.log(json_status);
	  			var status = $.parseJSON(json_status);
	  			// alert(json_status);
	  			if (status[0] == "success"){
	  				location.href = "visualizar-chamado-produto.php?id=" + status[1] + "&token=" + status[2];
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
</script>
