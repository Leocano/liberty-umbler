<?php 

// exibir-relatorio.php

require 'headers/main-header.php';

// Token::validateToken($_GET['token']);

$json_report = $_GET['j_report'];
$report = json_decode($json_report);

$id_report = $report->id_report;
$id_creator = $report->id_creator_report;
$name_report = $report->name_report;
$sum = $report->sum_report;
$hour_pos = $report->hour_pos_report;

$query = $report->query_report;

$columns = $report->columns_report;
$dao = new ColumnsDAO;
$columns = $dao->getNicknames($columns);


$date_column_cont = 0;
$date_column = 0;

foreach($columns as $col) {
	if (strpos($col->nickname_column, 'Data ') !== false) {
		$date_column = $date_column_cont;
	}
	$date_column_cont++;
}



$col_to_group = $report->col_to_group_report;

$db->query("$query");

$qtd_columns = count($columns);
if ($col_to_group !== null){
	$qtd_columns--;
}

$results = $db->getResults();

$current_date = date("d/m/Y  H:i:s");

require 'scripts/main-script.php';
require 'scripts/datatable.php';

?>
<script type="text/javascript" src="plugins/export/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="plugins/export/libs/js-xlsx/xlsx.core.min.js"></script>
<script type="text/javascript" src="plugins/export/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="plugins/export/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="plugins/export/tableExport.min.js"></script>

<div class="row">
	<div class="col-xs-12 col-sm-4 title">
		<h1><?=$name_report?></h1>
		<p class="help-block">
			Criado por: <?=$report->name?>
			<br>
			Data de criação: <?=$report->created?>
			<br>
			Gerado em: <?=$current_date?>
			<br>
			Período:
			<?php 
				if($report_info[0]->date_field_report = "tb_timekeeping.current_month"){
					echo "De " . Date('01/m/Y') . " até " . Date('t/m/Y');
				} else if($report_info[0]->date_field_report = "tb_timekeeping.last_month"){
					$last_month = strtotime("now -1 month");
					echo "De " . Date('01/m/Y', $last_month) . " até " . Date('t/m/Y', $last_month);
				} else {
					echo $report_info[0]->timespan_report;
				}
			?>
		</p>
	</div>
	<div class="col-xs-12 col-sm-8 text-right title">
		<button class="btn btn-sm btn-success" id="btn-xls">
			<i class="fa fa-arrow-circle-down"></i> &nbsp;
			Exportar para Excel
		</button>
		<button class="btn btn-sm btn-success" id="btn-pdf">
			<i class="fa fa-arrow-circle-down"></i> &nbsp;
			Exportar para PDF
		</button>
		<?php 
		if ($user->getIdUser() == $id_creator || $user->checkProfile(array(3, 2))){
		?>
		<a class="btn btn-sm btn-success" href="editar-relatorio.php?j_report=<?=urlencode($json_report)?>&token=<?=$_GET['token']?>">
			<i class="fa fa-pencil"></i> &nbsp;
			Editar Relatório
		</a>
		<?php 
		}

		if (!$user->checkProfile(array(5))){
		?>
		<button id="btn-copy-report" data-name="<?=$name_report?>" class="btn btn-sm btn-success" data-toggle="modal" href="#modal-copy">
			<i class="fa fa-files-o"></i> &nbsp;
			Copiar Relatório
		</button>
		<a class="btn btn-sm btn-success" href="gerenciar-relatorios.php">
			<i class="fa fa-reply"></i> &nbsp;
			Voltar
		</a>
		<?php
		}
		?>
	</div>
</div>

<div style="visibility: hidden; height: 1px !important" id="data-export">
	<h1><?=$name_report?></h1>
	<h3>Criado por: <?=$report->name?></h3>
	<h3>Data de criação: <?=$report->created?></h3>
	<h3>Gerado em: <?=$current_date?></h3>
	<h3>Período: <?=$report->timespan_report?></h3>
	<table id="data-export-table" border="1" data-tableexport-display="always">

	</table>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="table-responsive" id="table-wrap">
			<table class="table table-striped table-hover table-bordered" id="data-table">
				<thead>
					<?php 
					foreach ($columns as $column) {
						?>
						<th><?=htmlentities($column->nickname_column)?></th>
						<?php
					}
					?>
				</thead>
				<tbody>
					<?php 
					foreach ($results as $result) {

						echo "<tr>";

						foreach ($result as $key => $value) {
							echo "<td>" . $value . "</td>";
						}

						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>

<?php
	require "modals/copiar-relatorio-modal.php";
?>
<script type="text/javascript">

	$("#btn-copy-report").click(function(event){
		$("#txt-copy-name").val($(this).data("name") + " - Cópia");
	});

		$("#btn-xls").click(function(event){
			event.preventDefault();
			table.page.len(-1).draw();
			$("#data-export table").html($("#data-table").html());

			var htmltable= document.getElementById('data-export');
	  		var html = htmltable.outerHTML;

	  		window.open('data:application/vnd.ms-excel,<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>' + encodeURIComponent(html));

			table.page.len(25).draw();
		});

		$("#btn-pdf").click(function(){
			table.page.len(-1).draw();
			$('#data-table').tableExport({fileName: 'Support Center',
				  type: 'pdf',
				  jspdf: {format: 'bestfit',
				  	margins: {left:10, right:10, top:20, bottom:20},
				  	autotable: {styles: {overflow: 'hidden' ,
				  						 fontSize: 10,
				  						 fillColor: 'inherit', 
                               		 	 textColor: 'inherit'
				  						},
				  				columnWidth: 'auto',
				  				theme: 'striped'}
				  }
			});
			table.page.len(25).draw();
		});

		var qtd_columns = <?=$qtd_columns?>;

		<?php
		if ($col_to_group === null && $sum === null){
			?>
			var table = $('#data-table').DataTable({
				"colReorder" : true ,
				// "dom": 'Bfrtip',
				"language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
		        },
		        "displayLength" : 200,
		        "lengthMenu" : [ 10, 25, 50, 75, 100, 200]
		    });
			<?php
		} else if ($sum == "on" && $col_to_group !== null){
			?>
			var groupRow = <?=$col_to_group?>;
			var hourPos = <?=$hour_pos?>;	
			var dateColumn = <?=$date_column?>

			var table = $('#data-table').DataTable({
				// "colReorder" : true ,
				// "dom": 'Bfrtip',
				// "buttons": [
			 //        'excel', 'pdf'
			 //    ] ,
		        "columnDefs": [
		            { "visible": false, "targets": groupRow} ,
		            { "orderable": false, "targets": "_all"}
		        ],
		        "language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
		        },
		        "order": [[ groupRow, 'asc' ], [dateColumn, 'asc']],
		        "displayLength": 200,
		        "lengthMenu" : [ 10, 25, 50, 75, 100, 200],
		        "drawCallback": function ( settings ) {
		            var api = this.api();
		            var rows = api.rows( {page:'all'} ).nodes();
		            var last=null;
		            var time = null;
		            var totalHours = 0;
		            var totalMinutes = 0;
		            var lastGroup = null;
		            var currentGroup = null;
		            var finalHours = 0;
		            var finalMinutes = 0;

		            api.column(groupRow, {page:'all'} ).data().each( function ( group, i) {
	            		time = this.cell(i, hourPos).data();
		            	time = time.split(/:/);
		            	time[0] = parseInt(time[0]);
		            	time[1] = parseInt(time[1]);
	            	

		            	currentGroup = this.cell(i, groupRow).data();

		            	if (lastGroup != currentGroup && lastGroup != null){
		            		$(rows).eq( i ).before(
		                        '<tr class="group"><td class="text-left" colspan="' + qtd_columns + '" style="font-weight: bold; background-color: #D0E9C6;">Total: ' + pad(totalHours) + ':' + pad(totalMinutes) + ':00</td></tr>'
		                    	+
		                    	'<tr><td colspan="' + qtd_columns + '"></td></tr>'
		                    );
		            		totalHours = 0;
		            		totalMinutes = 0;
		            	}

		            	totalHours = totalHours + time[0];
		            	totalMinutes = totalMinutes + time[1];

		            	finalHours = finalHours + time[0];
		            	finalMinutes = finalMinutes + time[1];

		            	while(totalMinutes >= 60){
		            		totalHours = totalHours + 1;
		            		totalMinutes = totalMinutes - 60;
		            	}

		            	group = group + "";

		                if (last !== group){
		                    $(rows).eq( i ).before(
		                        '<tr role="row" class="group"><td colspan="' + qtd_columns + '" style="font-weight: bold; background-color: #C4E3F3;">'+group+'</td></tr>'
		                    );
		 					
		                    last = group;
		                }

		                lastGroup = currentGroup;
		                lastRow = $(rows).eq( i );
		            });

		            while(finalMinutes >= 60){
	            		finalHours = finalHours + 1;
	            		finalMinutes = finalMinutes - 60;
	            	}
		            
		            lastRow.after(
                        '<tr role="row" class="group"><td class="text-left" colspan="' + qtd_columns + '" style="font-weight: bold; background-color: #D0E9C6;">Total: ' + pad(totalHours) + ':' + pad(totalMinutes) + ':00</td></tr>'
                    	+
                        '<tr role="row" class="group"><td class="text-left" colspan="' + qtd_columns + '" style="font-weight: bold; background-color: #D0E9C6;">Resumo do relatório: ' + pad(finalHours) + ':' + pad(finalMinutes) + ':00</td></tr>'

                    );
					// setExport();
		        }
		    });
			<?php
		} else if($sum === null && $col_to_group !== null) {
			?>
			var groupRow = <?=$col_to_group?>;
			var dateColumn = <?=$date_column?>;

			var table = $('#data-table').DataTable({
				"colReorder" : true ,
		        "columnDefs": [
		            { "visible": false, "targets": groupRow} ,
		            { "orderable": false, "targets": "_all"}
		        ],
		        "language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
		        },
		        "order": [[ groupRow, 'asc' ], [dateColumn, 'asc']],
		        "displayLength": 200,
		        "lengthMenu" : [ 10, 25, 50, 75, 100, 200],
		        "drawCallback": function ( settings ) {
		            var api = this.api();
		            var rows = api.rows( {page:'all'} ).nodes();
		            var last=null;

		            api.column(groupRow, {page:'all'} ).data().each( function ( group, i) {

		                if (last !== group){
		                    $(rows).eq( i ).before(
		                        '<tr role="row" class="group info"><td colspan="' + qtd_columns + '" style="font-weight: bold;">'+group+'</td></tr>'
		                    );
		 					
		                    last = group;
		                }
		            });
		        }
		    });
			<?php
		} else if($sum == "on" && $col_to_group === null){
			?>
			var hourPos = <?=$hour_pos?>;

			var table = $('#data-table').DataTable({
		        "columnDefs": [
		            { "orderable": false, "targets": "_all"}
		        ],
		        "language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.13/i18n/Portuguese-Brasil.json"
		        },
		        "displayLength": 200,
		        "lengthMenu" : [ 10, 25, 50, 75, 100, 200],
		        "drawCallback": function ( settings ) {
		            var api = this.api();
		            var rows = api.rows( {page:'all'} ).nodes();
		            var last=null;
		            var time = null;
		            var totalHours = 0;
		            var totalMinutes = 0;
		            var lastGroup = null;
		            var currentGroup = null;

		            api.column(hourPos, {page:'all'} ).data().each( function ( group, i) {
	            		time = this.cell(i, hourPos).data();
		            	time = time.split(/:/);
		            	time[0] = parseInt(time[0]);
		            	time[1] = parseInt(time[1]);
	            

		            	totalHours = totalHours + time[0];
		            	totalMinutes = totalMinutes + time[1];

		            	while(totalMinutes >= 60){
		            		totalHours = totalHours + 1;
		            		totalMinutes = totalMinutes - 60;
		            	}
		                lastRow = $(rows).eq( i );
		            });
		            lastRow.after(
                        '<tr role="row" class="group"><td class="text-left" colspan="' + qtd_columns + '" style="font-weight: bold; background-color: #D0E9C6;">Total: ' + pad(totalHours) + ':' + pad(totalMinutes) + ':00</td></tr>'
                    )
					// setExport();
		        }
		    });
			<?php
		}
		?>
		
		function pad(n) {
		    return (n < 10) ? ("0" + n) : n;
		}

		function setExport(){
			$("#data-export").html($("#data-table").html());
		}

</script>
<?php
	require 'footers/main-footer.php';
?>