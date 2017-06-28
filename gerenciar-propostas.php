<?php

// gerenciar-empresas.php

require 'headers/main-header.php';

if(!$user->checkProfile(array(3, 2))){
	Redirect::to("index.php");
	exit();
}

Token::generateToken();

require 'scripts/main-script.php';
require 'scripts/datatable.php';

?>

<div class="row">
	<div class="col-xs-12 col-sm-8 title">
		<h1>Gerenciar Propostas</h1>
	</div>
	<div class="col-xs-12 col-sm-4 text-right">
		<a href="cadastrar-proposta.php" class="btn btn-success">
			<i class="fa fa-plus"></i> &nbsp;
			Adicionar Propostas
		</a>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-hover data-table table-condensed">
			<thead>
				<th>Proposta</th>
				<th>Descrição</th>
				<th>Empresa</th>
				<th>Responsável</th>
				<th>Tipo</th>
				<th>Status</th>
				<th class="text-right">Ações</th>
			</thead>
			<tbody id="proposal-table">
				<?php 
				$dao = new ProposalDAO;

				$proposals = $dao->getAllProposals();

				if ($proposals == null){
					?>
					<tr>
						<td colspan="100%" class="text-center">Não foram encontradas propostas.</td>
					</tr>
					<?php
				} else {
					foreach ($proposals as $proposal) {
						$json_proposal = urlencode(json_encode($proposal));
						$delete_check = $dao->getTicketsByProposal($proposal->id_proposal);

						?>
						<tr id="row-<?=$company->id_company?>">
							<td><?=$proposal->name_proposal?></td>
							<td><?=$proposal->desc_proposal?></td>
							<td><?=$proposal->name_company?></td>
							<td><?=$proposal->name?></td>
							<td><?=$proposal->desc_proposal_type?></td>
							<td>
								<form class="form-status">
									<?php
									if ($proposal->active_proposal == 1){
										echo "<span class='text-success'>Ativa &nbsp;</span>";
										$checked = "checked";
									} else if($proposal->active_proposal == 0){
										echo "<span class='text-danger'>Inativa &nbsp;</span>";
										$checked = "";
									}
									?>
									<input type="checkbox" name="status" class="checkbox-status" <?=$checked?>>
									<input type="hidden" name="id-company" value="<?=$proposal->id_company?>">
									<input type="hidden" name="id-proposal-type" value="<?=$proposal->id_proposal_type?>">
									<input type="hidden" name="id" value="<?=$proposal->id_proposal?>">
									<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
								</form>
							</td>
							<td class="text-right">
								<a href="editar-proposta.php?j_proposal=<?=$json_proposal?>" class="btn btn-sm btn-success" data-id="<?=$proposal->id_proposal?>" data-name="<?=$proposal->name_proposal?>">
									<i class="fa fa-pencil"></i>&nbsp;&nbsp;
									Editar
								</a>
								<?php 
								if ($delete_check == null){
								?>
									<button data-toggle="modal" data-target="#modal-delete" data-name="<?=$proposal->name_proposal?>" class="btn-proposal-delete btn btn-sm btn-danger" data-id="<?=$proposal->id_proposal?>">
										<i class="fa fa-trash"></i> &nbsp;
										Excluir
									</button>
								<?php 
								}
								?>
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
	
require 'modals/gerenciar-propostas-modals.php';
require 'scripts/animate.php';
require 'scripts/ajax-form.php';
require 'scripts/bootstrap-notify.php';
?>

<script>
	$formStatus = $(".form-status");
	$checkboxStatus = $(".checkbox-status");

	// Alteração de status atraves do flag
	var check;
	$formStatus.on("click", $checkboxStatus, function(){
		check = $(this).children($checkboxStatus);
		$(this).ajaxSubmit({
			url : 'p-mudar-status-proposta.php' ,
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

    $("#proposal-table").on("click", ".btn-proposal-delete", function(event){
    	event.preventDefault();
    	$("#delete-text").text($(this).data("name"));
    	$("#txt-delete-id").val($(this).data("id"));
    });
	

    $("#btn-delete-proposal").click(function(event){
    	event.preventDefault();
    	$("#form-delete-proposal").ajaxSubmit({
    		url : 'p-delete-proposal.php' ,
    		type : 'post' ,
    		success : function(){
    			location.reload();
    		}
    	});
    });
</script>

<?
require 'footers/main-footer.php';
?>