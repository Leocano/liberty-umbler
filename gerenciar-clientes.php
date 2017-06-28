<?php

// gerenciar-clientes.php
//  Página para que o admin gerencie os clientes existentes

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

?>

<div class="row">
	<div class="col-xs-12 col-sm-8 title">
		<h1>Gerenciar Clientes</h1>
	</div>
	<div class="col-xs-12 col-sm-4 text-right">
		<a href="novo-cliente.php" class="btn btn-success">
			<i class="fa fa-plus"></i> &nbsp;
			Adicionar Cliente
		</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<!-- <div class="table-responsive"> -->
			<table class="table table-striped table-hover table-condensed data-table">
				<thead>
					<th>Nome</th>
					<th>Email</th>
					<th>Telefone</th>
					<th>Empresa</th>
					<th>Status</th>
					<th class="text-right">Opções</th>
				</thead>
				<tbody>
					<?php 
					$dao = new CustomerDAO;
					$customers = $dao->getAllCustomers();

					if ($customers == null){
						?>
						<tr>
							<td colspan="3" class="text-center">Não foram encontrados clientes.</td>
						</tr>
						<?php
					} else {
						foreach ($customers as $customer) {
							?>
							<tr id="row-<?=$customer->id_user?>">
								<td><?=$customer->name?></td>
								<td><?=$customer->email?></td>
								<td><div data-mask="(00) 0000-000000000"><?=$customer->phone?></div></td>
								<td><?=$customer->company?></td>
								<td>
									<form class="form-status">
										<?php
										if ($customer->active == 1){
											echo "<span class='text-success'>Ativo &nbsp;</span>";
											$checked = "checked";
										} else if($customer->active == 0){
											echo "<span class='text-danger'>Inativo &nbsp;</span>";
											$checked = "";
										}
										?>
										<input type="checkbox" name="status" class="checkbox-status" <?=$checked?>>
										<input type="hidden" name="id" value="<?=$customer->id_user?>">
										<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
									</form>
								</td>
								<td class="text-right">
									<a href="editar-cliente.php?id=<?=$customer->id_user?>" class="btn btn-sm btn-success" data-id="<?=$customer->id_user?>" data-name="<?=$customer->name?>">
										<i class="fa fa-pencil"></i>&nbsp;&nbsp;
										Editar
									</a>
									<!-- <a class="btn btn-sm btn-danger btn-list-delete" data-id="<?=$customer->id_user?>" data-name="<?=$customer->name?>">
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
		<!-- </div> -->
	</div>
</div>
</div> <!-- //content // -->

<?php 
	
require 'scripts/main-script.php';
require 'scripts/datatable.php';
require 'modals/gerenciar-clientes-modals.php';
require 'scripts/animate.php';
require 'scripts/jquery-mask.php';
require 'scripts/ajax-form.php';
require 'scripts/bootstrap-notify.php';
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
			url : 'p-deletar-cliente.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "success"){
					$.notify({
						// options
						message: 'Cliente "<strong>' + name + '</strong>" deletado com sucesso!' 
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
			url : 'p-mudar-status-cliente.php' ,
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
</script>.

<?
require 'footers/main-footer.php';
?>