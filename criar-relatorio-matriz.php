<?php 

// criar-relatorio.php

require 'headers/main-header.php';

Token::generateToken();
$token = $_SESSION['token'];

//Pegar todos os consultores para atribuição
$dao = new ConsultantDAO;
$consultants = $dao->getAllConsultants();

//Pegar colunas para o select
$dao = new ColumnsDAO;
$columns = $dao->getAllColumns();

//Pegar as views do report
$dao = new ViewReportDAO;
$views = $dao->getAllViews();

?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="plugins/monthpicker/src/MonthPicker.css">

<div class="row">
	<div class="col-xs-12 title">
		<h1>Criar Relatório Matriz</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Relatório</h2>
	</div>
</div>

<form id="form-report" name="form-report" method="post" action="p-criar-relatorio-matriz.php">
	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Nome do Relatório</label>
			<input class="form-control" type="text" name="txt-name-report" placeholder="Digite o nome do relatório">
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Visibilidade</label>
			<select class="form-control" id="slt-view" name="slt-view">
				<?php 

				foreach ($views as $view) {
					?>
					<option value="<?=$view->id_view_report?>"><?=$view->desc_view_report?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-4 form-group">
			<label>1º Campo</label>
			<select class="form-control selectpicker" id="select-1" name="slt-fields[]">
				<?php 
				foreach ($columns as $column) {
					?>
					<option value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-4 form-group">
			<label>2º Campo</label>
			<select class="form-control selectpicker" id="select-2" name="slt-fields[]">
				<?php 
				foreach ($columns as $column) {
					?>
					<option value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-4 form-group">
			<label>3º Campo</label>
			<select class="form-control selectpicker" id="select-3" name="slt-fields[]">
				<?php 
				foreach ($columns as $column) {
					?>
					<option value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>



	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>



	<div class="row" id="div-filter">

		<div class="col-xs-12 col-sm-4 form-group">
			<label>De: </label>
			<input class="datepicker date-input form-control" type="text" name="slt-date-from" placeholder="Clique para alterar" readonly>
		</div>	
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Até: </label>
			<input class="datepicker date-input form-control" type="text" name="slt-date-to" placeholder="Clique para alterar" readonly>
		</div>	
		
		<div class="col-xs-12 form-group">
			<button class="btn btn-success" id="btn-add-filter">
				<i class="fa fa-plus"></i> &nbsp;
				Adicionar um novo filtro
			</button>
		</div>
		<div class="hidden div-filter-content" id="div-filter-content" data-delete="">
			<div class="col-xs-12 col-sm-4 form-group">
				<label>Nome da coluna</label>
				<select class="form-control slt-condition" id="slt-condition" name="">
					<?php 
					foreach ($columns as $column) {
						?>
						<option value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 form-group">
				<label>Critério</label>
				<select class="form-control slt-criteria" >
					<option value="=">É</option>
					<option value="<>">Não é</option>
					<option value="<">Menor que</option>
					<option value=">">Maior que</option>
					<option value="<=">Menor ou igual a</option>
					<option value=">=">Maior ou igual a</option>
				</select>
			</div>
			<div class="col-xs-12 col-sm-4 form-group">
				<label>Valor</label>
				<input type="text" class="form-control txt-parameter">
			</div>
			<div class="col-xs-12 col-sm-4 form-group">
				<select class="form-control slt-connector">
					<option value="AND">E</option>
					<option value="OR">Ou</option>
				</select>
			</div>
			<div class="col-sm-8 form-group">
				<button class="btn btn-danger btn-delete-filter" data-delete="">
					<i class="fa fa-times"></i> &nbsp;
					Remover
				</button>
			</div>
			<!-- <div class="clearfix"></div> -->
		</div>
		<!-- <div class="clearfix"></div> -->
	</div>

	<input type="hidden" name="token" value="<?=$_SESSION['token']?>">

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" class="btn btn-lg btn-success">
				<i class="fa fa-file"></i> &nbsp;
				Gerar Relatório
			</button>
		</div>
	</div>
</form>

</div> <!-- //content // -->

<?php 
	require 'scripts/main-script.php';
	require 'scripts/bootstrap-select.php';
	require 'scripts/jquery-ui.php';
	require 'scripts/monthpicker.php';
?>
<script type="text/javascript">
	$(".selectpicker").selectpicker();

	//Adicionando novos filtros
		$btnAddFilter = $("#btn-add-filter");
		$btnDeleteFilter = $(".btn-delete-filter");
		$divFilter = $("#div-filter");
		$divFilterContent = $("#div-filter-content");
		//CLASSEEEEEE
		var index = 1;

		$divFilter.on("click" , ".btn-delete-filter" , function(event){
			event.preventDefault();
			index--;
			$dataDelete = $(this).data('delete');
			$("#div-filter-content-" + $dataDelete).fadeOut(function(){
				$(this).remove();
			});
		});

		$btnAddFilter.click(function(event){
			event.preventDefault();
			$newFilter = $divFilterContent.clone();
			$newFilter.attr("id" , "div-filter-content-" + index);
			$newFilter.attr("data-delete" , index);
			$newFilter.find(".slt-condition").attr("id" , "slt-condition-" + index);
			$newFilter.find(".slt-condition").attr("name" , "slt-condition[]");
			$newFilter.find(".slt-criteria").attr("id" , "slt-criteria-" + index);
			$newFilter.find(".slt-criteria").attr("name" , "slt-criteria[]");
			$newFilter.find(".txt-parameter").attr("id" , "txt-parameter-" + index);
			$newFilter.find(".txt-parameter").attr("name" , "txt-parameter[]");
			$newFilter.find(".slt-connector").attr("id" , "slt-connector-" + index);
			$newFilter.find(".slt-connector").attr("name" , "slt-connector[]");
			$newFilter.find(".btn-delete-filter").attr("id" , "btn-delete-filter-" + index);
			$newFilter.find(".btn-delete-filter").attr("name" , "btn-delete-filter[]");
			$newFilter.find(".btn-delete-filter").attr("data-delete" , index);

			$newFilter.appendTo("#div-filter").removeClass("hidden");
			index++;
		});

		$('.datepicker').MonthPicker({ StartYear: 2017, ShowIcon: false });
</script>
<?php 
	require 'footers/main-footer.php';
?>