<?php

// gerenciar-consultores.php
//  Página para que o admin gerencie os consultores existentes

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

?>

<div class="row">
	<div class="col-xs-12 col-sm-8 title">
		<h1>Gerenciar Consultores</h1>
	</div>
	<div class="col-xs-12 col-sm-4 text-right">
		<a href="novo-consultor.php" class="btn btn-success">
			<i class="fa fa-plus"></i> &nbsp;
			Adicionar consultor
		</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-hover data-table table-condensed">
			<thead>
				<th>Nome</th>
				<th>Email</th>
				<th>Cargo</th>
				<th>Perfil</th>
				<th>Status</th>
				<th class="text-right">Opções</th>
			</thead>
			<tbody>

				<?php 
				$dao = new ConsultantDAO;

				$consultants = $dao->getAllConsultants();


				if ($consultants == null){
					?>
					<tr>
						<td colspan="100%" class="text-center">Não foram encontrados consultores.</td>
					</tr>
					<?php 
				} else {
					foreach ($consultants as $consultant) {
						?>
						<tr id="row-<?=$consultant->id_user?>">
							<td><?=$consultant->name?></td>
							<td><?=$consultant->email?></td>
							<td><?=$consultant->role?></td>
							<td><?=$consultant->desc_profile?></td>
							<td>
								<form class="form-status">
									<?php
									if ($consultant->active == 1){
										echo "<span class='text-success'>Ativo &nbsp;</span>";
										$checked = "checked";
									} else if($consultant->active == 0){
										echo "<span class='text-danger'>Inativo &nbsp;</span>";
										$checked = "";
									}
									?>
									<input type="checkbox" name="status" class="checkbox-status" <?=$checked?>>
									<input type="hidden" name="id" value="<?=$consultant->id_user?>">
									<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
								</form>
							</td>
							<td class="text-right">
								<a href="editar-consultor.php?id=<?=$consultant->id_user?>" class="btn btn-sm btn-success" data-id="<?=$consultant->id_user?>" data-name="<?=$consultant->name?>">
									<i class="fa fa-pencil"></i>&nbsp;&nbsp;
									Editar
								</a>
								<!-- <a class="btn btn-danger btn-sm btn-list-delete" data-id="<?=$consultant->id_user?>" data-name="<?=$consultant->name?>">
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
require 'modals/gerenciar-consultores-modals.php';
require 'scripts/animate.php';
require 'scripts/ajax-form.php';
require 'scripts/bootstrap-notify.php';
require 'scripts/datatable.php';
?>

<script type="text/javascript">
	$btnListDelete = $(".btn-list-delete");
	$modalDelete = $("#modal-delete");
	$deleteText = $("#delete-text");
	$txtDeleteId = $("#txt-delete-id");
	$btnDeleteCustomer = $("#btn-delete-customer");
	$formDelete = $("#form-delete");
	$formStatus = $(".form-status");
	$checkboxStatus = $(".checkbox-status");

	var id;
	var name;
	var row;

	//Passa os parametros do usuario para o modal de confirmação
	$btnListDelete.on("click", function(event){
		event.preventDefault();
		id = $(this).data("id");
		name = $(this).data("name");
		$modalDelete.modal();
		$deleteText.text(name);
		$txtDeleteId.val(id);
	});

	// //deleta o usuario quando o botao do modal é ativado
	$btnDeleteCustomer.on("click", function(event){
		event.preventDefault();
		$formDelete.ajaxSubmit({
			url : 'p-deletar-consultor.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					$.notify({
						// options
						message: 'Consultor "<strong>' + name + '</strong>" deletado com sucesso!' 
					},{
						// settings
						type: 'success' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
					row = "row-" + id;
					$("#" + row).fadeOut();
				}
			}
		});
	});


	// Alteração de status atraves do flag
	var check;
	$formStatus.on("click", $checkboxStatus, function(){
		check = $(this).children($checkboxStatus);
		$(this).ajaxSubmit({
			url : 'p-mudar-status-consultor.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == 1){
					check.html("Ativo&nbsp;&nbsp;");
					check.removeClass("text-danger");
					check.addClass("text-success");
				} else if(status == 0){
					check.html("Inativo&nbsp;&nbsp;");
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