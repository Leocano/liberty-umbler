<?php

// gerenciar-empresas.php

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

?>

<div class="row">
	<div class="col-xs-12 col-sm-8 title">
		<h1>Gerenciar Empresas</h1>
	</div>
	<div class="col-xs-12 col-sm-4 text-right">
		<a href="nova-empresa.php" class="btn btn-success">
			<i class="fa fa-plus"></i> &nbsp;
			Adicionar Empresas
		</a>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-hover data-table table-condensed">
			<thead>
				<th>Nome</th>
				<th>Email</th>
				<th>Status</th>
				<th class="text-right">Opções</th>
			</thead>
			<tbody>
				<?php 
				$dao = new CompanyDAO;

				$companies = $dao->getAllCompanies();

				if ($companies == null){
					?>
					<tr>
						<td colspan="100%" class="text-center">Não foram encontradas empresas.</td>
					</tr>
					<?php
				} else {
					foreach ($companies as $company) {
						?>
						<tr id="row-<?=$company->id_company?>">
							<td><?=$company->name_company?></td>
							<td><?=$company->email?></td>
							<td>
								<form class="form-status">
									<?php
									if ($company->active == 1){
										echo "<span class='text-success'>Ativa &nbsp;</span>";
										$checked = "checked";
									} else if($company->active == 0){
										echo "<span class='text-danger'>Inativa &nbsp;</span>";
										$checked = "";
									}
									?>
									<input type="checkbox" name="status" class="checkbox-status" <?=$checked?>>
									<input type="hidden" name="id" value="<?=$company->id_company?>">
									<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
								</form>
							</td>
							<td class="text-right">
								<a href="editar-empresa.php?id=<?=$company->id_company?>" class="btn btn-sm btn-success" data-id="<?=$company->id_company?>" data-name="<?=$customer->name_company?>">
									<i class="fa fa-pencil"></i>&nbsp;&nbsp;
									Editar
								</a>
								<!-- <a class="btn btn-danger btn-sm btn-list-delete" data-id="<?=$company->id_company?>" data-name="<?=$company->name?>">
									<i class="fa fa-trash-o"></i>&nbsp;&nbsp;
									Excluir
								</a> -->
							</td>
						</tr>
						<?
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
</div> <!-- //content // -->


<?php 
	
require 'scripts/main-script.php';
require 'modals/gerenciar-empresas-modals.php';
require 'scripts/animate.php';
require 'scripts/ajax-form.php';
require 'scripts/bootstrap-notify.php';
require 'scripts/datatable.php';
?>

<script>
	$formStatus = $(".form-status");
	$checkboxStatus = $(".checkbox-status");

	// Alteração de status atraves do flag
	var check;
	$formStatus.on("click", $checkboxStatus, function(){
		check = $(this).children($checkboxStatus);
		$(this).ajaxSubmit({
			url : 'p-mudar-status-empresa.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == 1){
					check.html("Ativa&nbsp;&nbsp;");
					check.removeClass("text-danger");
					check.addClass("text-success");
				} else if(status == 0){
					check.html("Inativa&nbsp;&nbsp;");
					check.removeClass("text-success");
					check.addClass("text-danger");
				}
			}
		});
	});

	$('.data-table').DataTable({
		"colReorder" : true ,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
        },
        "responsive": true
    });
</script>

<?
require 'footers/main-footer.php';
?>