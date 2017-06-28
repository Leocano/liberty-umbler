<?php 

// gerenciar-relatorios.php

require 'headers/main-header.php';

if ($user->checkProfile(array(5))){
	Redirect::to("index.php");
}

require 'scripts/main-script.php';
require 'scripts/datatable.php';

Token::generateToken();

$id_user = $user->getIdUser();
?>

<div class="row">
	<div class="col-xs-12 col-sm-8 title">
		<h1>Relatórios</h1>
	</div>
	<div class="col-xs-12 col-sm-4 text-right">
		<a href="criar-relatorio.php" class="btn btn-success">
			<i class="fa fa-plus"></i> &nbsp;
			Criar Relatório
		</a>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#my-reports">Meus Relatórios</a></li>
			<?php 
				if ($user->checkProfile(array(2,3))){
			?>
			<li><a data-toggle="tab" href="#all-reports">Todos os Relatórios</a></li>
			<?php 
			}
			?>
		</ul>
	</div>
</div>

<div class="tab-content">
	<div id="my-reports" class="tab-pane fade in active">

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Meus Relatórios</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover table-condensed data-table" width="100%">
					<thead>
						<th>Nome</th>
						<th>Criado por</th>
						<th>Data de criação</th>
						<th class="text-right">Opções</th>
					</thead>
					<tbody class="report-table">
						<?php 
						$dao = new ReportDAO;

						$reports = $dao->getMyReports($id_user);

						foreach ($reports as $report) {
							$id_user = $user->getIdUser();
							$j_report = urlencode(json_encode((array) $report));
							?>
							<tr>
								<td><a href="relatorio-salvo.php?j_report=<?=$j_report?>&token=<?=$_SESSION['token']?>"><?=$report->name_report?></a></td>
								<td><?=$report->name?></td>
								<td><?=$report->created?></td>
								<td class="text-right">
									<button class="btn btn-danger btn-sm btn-toggle-delete" data-id="<?=$report->id_report?>" data-desc="<?=$report->name_report?>" data-toggle="modal" href="#modal-delete">
										<i class="fa fa-trash"></i> &nbsp;
										Excluir
									</button>
									<a class="btn btn-success btn-sm" href="editar-relatorio.php?j_report=<?=$j_report?>&token=<?=$_SESSION['token']?>">
										<i class="fa fa-pencil"></i> &nbsp;
										Editar
									</a>
								</td>
							</tr>
							<?php 
							$i++;
						}
						$i--;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php 
	if ($user->checkProfile(array(2,3))){
?>

	<div id="all-reports" class="tab-pane fade">

		<div class="row">
			<div class="col-xs-12 subtitle">
				<h2 class="h3">Todos os Relatórios</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<?php 
					$dao = new ReportDAO;
					$reports = $dao->getAllReports();
					
					$i = 0;

					foreach ($reports as $report) {
						$reports[$i] = (array) $report;
						$i++;
					}

					$reports_by_creator = array();

					foreach($reports as $key => $item){
					   $reports_by_creator[$item['name']][$key] = $item;
					}

					?>

					<div class="panel-group" id="accordion-all-reports" role="tablist" aria-multiselectable="false">

					<?php

					$i = 0;
					foreach ($reports_by_creator as $repo) {
						?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="accordion-collapse-<?=$i?>">
								<h4 class="panel-title" >
									<a role="button" data-toggle="collapse" data-parent="#accordion-collapse-<?=$i?>" href="#collapse-<?=$i?>" aria-expanded="true" aria-controls="collapse-<?=$i?>"">
							        	<?= $repo[$i]['name'] ?>
							        </a>
								</h4>
							</div>

							<div id="collapse-<?=$i?>"" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion-collapse-<?=$i?>">
								<div class="panel-body">
									<table class="table table-striped table-hover table-condensed data-table" width="100%">
										<thead>
											<th>Nome</th>
											<th>Criado por</th>
											<th>Data de criação</th>
											<th class="text-right">Opções</th>
										</thead>
										<tbody class="report-table">
											<?php 
											$dao = new ReportDAO;

											$reports = $dao->getAllReports();

											foreach ($repo as $report) {
												$id_user = $user->getIdUser();
												$j_report = urlencode(json_encode((array) $report));
												?>
												<tr>
													<td><a href="relatorio-salvo.php?j_report=<?=$j_report?>&token=<?=$_SESSION['token']?>"><?=$report['name_report']?></a></td>
													<td><?=$report['name']?></td>
													<td><?=$report['created']?></td>
													<td class="text-right">
														<button class="btn btn-danger btn-sm btn-toggle-delete" data-id="<?=$report['id_report']?>" data-desc="<?=$report['name_report']?>" data-toggle="modal" href="#modal-delete">
															<i class="fa fa-trash"></i> &nbsp;
															Excluir
														</button>
														<a class="btn btn-success btn-sm" href="editar-relatorio.php?j_report=<?=$j_report?>&token=<?=$_SESSION['token']?>">
															<i class="fa fa-pencil"></i> &nbsp;
															Editar
														</a>
													</td>
												</tr>
												<?php 
												$i++;
											}
											$i--;
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<?php
						$i++;
					}
				?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php 
}
?>

</div> <!-- //content // -->

<?php 
	require 'scripts/ajax-form.php';
	require 'modals/gerenciar-relatorios-modals.php';
?>

<script type="text/javascript">
		$btnToggleDelete = $(".btn-toggle-delete");
		$btnDeleteReport = $("#btn-delete-report");
		$formDelete = $("#form-delete-report");

    	$(".report-table").on("click", ".btn-toggle-delete", function(event){
			event.preventDefault();
			$("#delete-text").text($(this).data("desc"));
			$("#txt-delete-id").val($(this).data("id"));
		});

		$btnDeleteReport.click(function(event){
			event.preventDefault();
			$formDelete.ajaxSubmit({
				url: 'p-deletar-relatorio.php',
				type: 'POST',
				success: function(status){
					if (status == "success"){
						location.reload();
					}
				}
			});
		});

		$('.data-table').DataTable({
			"colReorder" : true ,
			"language": {
	            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
	        },
	        "pageLength": 100 ,
        	"responsive": true
	    });
</script>

<?php
	require 'footers/main-footer.php';
?>