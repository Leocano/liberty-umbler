<?php 

// gerenciar-relatorios.php

require 'headers/main-header.php';

if (!$user->checkProfile(array(5))){
	Redirect::to("index.php");
}

$company = $user->getCompany();
$id_company = $company->getIdCompany();

require 'scripts/main-script.php';
require 'scripts/datatable.php';

Token::generateToken();

$id_user = $user->getIdUser();
?>

<div class="row">
	<div class="col-xs-12 title">
		<h1>Relatórios</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<table class="table table-striped table-hover table-condensed data-table" width="100%">
			<thead>
				<th>Nome</th>
				<th>Data de criação</th>
			</thead>
			<tbody class="report-table">
				<?php 
				$dao = new ReportDAO;

				$reports = $dao->getCompanyReports($id_company);

				foreach ($reports as $report) {
					$id_user = $user->getIdUser();
					$j_report = urlencode(json_encode((array) $report));
					?>
					<tr>
						<td><a href="relatorio-salvo.php?j_report=<?=$j_report?>&token=<?=$_SESSION['token']?>"><?=$report->name_report?></a></td>
						<td><?=$report->created?></td>
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

</div> <!-- //content // -->

<?php 
	require 'scripts/ajax-form.php';
	require 'modals/gerenciar-relatorios-modals.php';
?>

<script type="text/javascript">
	$('.data-table').DataTable({
		"colReorder" : true ,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
        },
    	"responsive": true
    });
</script>

<?php
	require 'footers/main-footer.php';
?>