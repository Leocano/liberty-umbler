<?php 

// editar-relatorio.php

require 'headers/main-header.php';

// Token::validateToken($_GET['token']);
$report = $_GET['j_report'];

$report = json_decode($report);

$current_columns = $report->edit_fields_report;
$current_columns = explode(",", $current_columns);

if ($user->checkProfile(array(5))){
	Redirect::to("index.php");
}

$dao = new CompanyDAO;
$companies = $dao->getAllCompanies();

//Pegar todos os consultores para atribuição
$dao = new ConsultantDAO;
$consultants = $dao->getAllConsultants();

//Pegar colunas para o select
$dao = new ColumnsDAO;
$columns = $dao->getAllColumns();

//Pegar colunas para a filtragem de data
$date_columns = $dao->getDateColumns();

$timespan = $report->timespan_report;

$id_user = $user->getIdUser();

$this_month = array(
	Date('01/m/Y'),
	Date('t/m/Y')
);

$last_month = array(
	Date('01/m/Y', strtotime("now -1 month")),
	Date('t/m/Y', strtotime("now -1 month"))
);

?>
<link href="plugins/select-multiple/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="row">
	<div class="col-xs-12 title">
		<h1>Editar Relatório</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Relatório</h2>
	</div>
</div>

<form id="form-report" name="form-report" method="post" action="p-editar-relatorio.php">

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Nome do Relatório</label>
			<input class="form-control" type="text" name="txt-name-report" placeholder="Digite o nome do relatório" value="<?=$report->name_report?>" required>
		</div>
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Quem pode visualizar *</label>
			<?php 
			if ($user->checkProfile(array(1))){
				$disabled_view = "disabled";
			}
			?>
			<select class="selectpicker form-control" name="slt-view[]" <?=$disabled_view?> id="slt-view" multiple data-live-search="true" title="Escolha quem pode visualizar">
				<?php 

				$dao = new ReportDAO;
				$consultants_view = $dao->getViewByReport($report->id_report);

				foreach ($consultants as $consultant) {
					$checked = "";
					$disabled_option = "";
					if ($consultant->id_user == $id_user){
						if (!$user->checkProfile(array(3,2))){
							$checked = "selected";
							$disabled_option = "disabled";
						}
					}

					foreach ($consultants_view as $c_view) {
						$checked_report = "";
						if ($consultant->id_user == $c_view->id_user){
							$checked_report = "selected";
							break;
						}
					}
					?>
					<option value="<?=$consultant->id_user?>" <?=$checked_report?> <?=$disabled_option?> <?=$checked?> ><?=$consultant->name?></option>
					<?php
				}
				?>
			</select>
			<?php 
			if ($user->checkProfile(array(2,3))){
			?>
				<br><br>
				<button id="btn-select-all" class="btn btn-sm btn-primary">
					Adicionar Todos
				</button>
				<button id="btn-remove-all" class="btn btn-sm btn-danger">
					Remover Todos
				</button>
			<?php 
			}
			?>
		</div>
	</div>


	<?php 
	if ($user->checkProfile(array(2, 3))){
	?>
		<div class="row">
			<div class="col-xs-12 col-sm-6 form-group">
				<label>Empresa</label>
				<select class="selectpicker form-control" name="slt-companies[]" multiple data-live-search="true">
				<?php 
					$company_views = $dao->getCompanyViews($report->id_report);
					foreach ($companies as $company) {
						$selected = "";
						foreach ($company_views as $c_view) {
							if ($company->id_company == $c_view->id_company){
								$selected = "selected";
								break;
							}
						}

						?>
						<option value="<?=$company->id_company?>" <?=$selected?> ><?=$company->name_company?></option>
						<?php
					}
				?>
				</select>
			</div>
		</div>
	<?php
	}
	?>


	<div class="row">
		<div class="col-xs-12 form-group">
			<label>Colunas*</label>
			<select multiple="multiple" id="slt-columns" name="slt-columns[]" required>
				<?php 
				$index = 0;
				$selected = null;
				foreach ($columns as $column) {
					// if (in_array($column->name_table . '.' . $column->name_column, $current_columns)){
					// 	$selected = "selected";
					// }
					?>
					<option data-desc="<?=htmlentities($column->nickname_column)?>" value="<?=$column->name_table . '.' . $column->name_column?>" <?=$selected?> ><?=htmlentities($column->nickname_column)?></option>
					<?php
					$selected = null;
				}
				?>
			</select>
		</div>
		<input type="hidden" name="multiple_value" id="multiple_value"  />
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row" id="div-filter">
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Data</label>
			<select class="form-control slt-date" id="slt-date" name="slt-date">
				<?php 
				foreach ($date_columns as $column) {
					$selected = "";
					$date_val = $column->name_table . '.' . $column->name_column;
					if ($date_val == $report->date_field_report){
						$selected = "selected";
					}
					?>
					<option value="<?=$date_val?>" <?=$selected?> ><?=htmlentities($column->nickname_column)?></option>
					<?php
				}
				$selected = null;
				if ($report->date_field_report == "tb_timekeeping.current_month"){
					$selected = "selected";
					$disabled_date = "disabled";
					$disabled_date_class = "date-disabled";
				}
				?>
				<option value="tb_timekeeping.current_month" <?=$selected?> >Apontamentos do mês atual</option>
				<?php 
					$selected = "";
					if ($report->date_field_report == "tb_timekeeping.last_month"){
						$selected = "selected";
						$disabled_date = "disabled";
						$disabled_date_class = "date-disabled";
					}
				?>
				<option value="tb_timekeeping.last_month" <?=$selected?> >Apontamentos do mês anterior</option>
			</select>
		</div>
		<?php 
		$timespan = explode(" até ", $timespan);

		
		if ($report->date_field_report == "tb_timekeeping.current_month"){
		?>
		<div class="col-xs-12 col-sm-4 form-group">
			<label>De: </label>
			<input class="datepicker date-input form-control <?=$disabled_date_class?>" <?=$disabled_date?> type="text" id="slt-date-from" name="slt-date-from" placeholder="Clique para alterar" value="<?=$this_month[0]?>" readonly>
		</div>	
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Até: </label>
			<input class="datepicker date-input form-control <?=$disabled_date_class?>" <?=$disabled_date?> type="text" id="slt-date-to" name="slt-date-to" value="<?=$this_month[1]?>" placeholder="Clique para alterar" readonly>
		</div>	
		<?php
		} else if($report->date_field_report == "tb_timekeeping.last_month"){
		?>

		<div class="col-xs-12 col-sm-4 form-group">
			<label>De: </label>
			<input class="datepicker date-input form-control <?=$disabled_date_class?>" <?=$disabled_date?> type="text" id="slt-date-from" name="slt-date-from" placeholder="Clique para alterar" value="<?=$last_month[0]?>" readonly>
		</div>	
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Até: </label>
			<input class="datepicker date-input form-control <?=$disabled_date_class?>" <?=$disabled_date?> type="text" id="slt-date-to" name="slt-date-to" value="<?=$last_month[1]?>" placeholder="Clique para alterar" readonly>
		</div>	
		<?php 
		} else {
		?>
		<div class="col-xs-12 col-sm-4 form-group">
			<label>De: </label>
			<input class="datepicker date-input form-control " type="text" id="slt-date-from" name="slt-date-from" placeholder="Clique para alterar" value="<?=substr($timespan[0], 3)?>" readonly>
		</div>	
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Até: </label>
			<input class="datepicker date-input form-control " type="text" id="slt-date-to" name="slt-date-to" value="<?=$timespan[1]?>" placeholder="Clique para alterar" readonly>
		</div>	
		<?php 
		}
?>

		<div class="col-xs-12 form-group">
			<hr>
		</div>

		<div class="col-xs-12 form-group">
			<button class="btn btn-success" id="btn-add-filter">
				<i class="fa fa-plus"></i> &nbsp;
				Adicionar um novo filtro avançado
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
					<option value="LIKE">Contém</option>
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
		</div>
		<?php 
			if ($report->json_filters_report != ""){
				$filter_compare = array(
					0 => array("=", "É") ,
					1 => array("<>", "Não é") ,
					2 => array("<", "Menor que") ,
					3 => array(">", "Maior que") ,
					4 => array("<=", "Menor ou igual a") ,
					5 => array(">=", "Maior ou igual a") ,
					6 => array("LIKE", "Contém") ,
				);
				// var_dump($filter_compare);
				// exit();
				$filters = json_decode($report->json_filters_report);
				$i = 1;
				foreach ($filters as $filter) {
					$filter_attr = explode("&", $filter);
					?>
					<div class="div-filter-content" id="div-filter-content-<?=$i?>" data-delete="<?=$i?>">
						<div class="col-xs-12 col-sm-4 form-group">
							<label>Nome da coluna</label>
							<select class="form-control slt-condition" id="slt-condition-<?=$i?>" name="slt-condition[]" data-counter="<?=$i?>">
								<?php 
								foreach ($columns as $column) {
									$selected = "";
									if ($filter_attr[0] == $column->name_table . '.' . $column->name_column){
										$selected = "selected";
										$selected_column = $column->name_column;
									}
									?>
									<option value="<?=$column->name_table . '.' . $column->name_column?>" <?=$selected?> ><?=htmlentities($column->nickname_column)?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label>Critério</label>
							<select class="form-control slt-criteria-<?=$i?>" id="slt-criteria-<?=$i?>" name="slt-criteria[]">
								<?php 
								foreach ($filter_compare as $comp) {
									$selected = "";
									if ($filter_attr[1] == $comp[0]) {
										$selected = "selected";
									}
									?>
									<option value="<?=$comp[0]?>" <?=$selected?> ><?=$comp[1]?></option>
									<?php
								}
								?>
							</select>
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<label>Valor</label>
							<?php
								$column_name = $selected_column;

								$db = Database::getInstance();

								$db->query(
									"
									SELECT
										query_column
									FROM
										tb_report_columns
									WHERE
										name_column = ?
									",
									array($column_name)
								);

								$query_column = $db->getResults();

								if ($query_column[0]->query_column == null) {
								?>
									<input type="text" class="form-control txt-parameter" id="txt-parameter-<?=$i?>" name="txt-parameter[]" value="<?=$filter_attr[2]?>">
								<?php
								} else {
									$db->query($query_column[0]->query_column);
									$query_values = $db->getResults();
									if ($user->checkProfile(array(1)) && $i == 1){
										$disabled_consultant = "disabled";
									} else {
										$disabled_consultant = "";
									}
									?>
										<select <?=$disabled_consultant?> class="form-control txt-parameter selectpicker param-<?=$i?>" data-live-search="true" id="txt-parameter-<?=$i?>" name="txt-parameter[]" >
											<?php 
											foreach($query_values as $val) {
												$selected = "";
												if ($filter_attr[2] == $val->$column_name) {
													$selected = "selected";
												} else {
													$selected = "";
												}
												?>
												<option <?=$selected?> value="<?=$val->$column_name?>"><?=$val->$column_name?></option>
												<?php
											}
											?>
										</select>
									<?php
								}
							?>
						</div>
						<div class="col-xs-12 col-sm-4 form-group">
							<select class="form-control slt-connector" id="slt-connector-<?=$i?>" name="slt-connector[]">
								<?php
								if ($filter_attr[3] == "AND"){
									$selected = "selected";
								} else {
									$selected = "";
								}
								?>
								<option value="AND" <?=$selected?> >E</option>
								<?php
								if ($filter_attr[3] == "OR"){
									$selected = "selected";
								} else {
									$selected = "";
								}

								?>
								<option value="OR" <?=$selected?> >Ou</option>
							</select>
						</div>
						<div class="col-sm-8 form-group">
							<button class="btn btn-danger btn-delete-filter" data-delete="<?=$i?>" id="btn-delete-filter-<?=$i?>" name="btn-delete-filter[]">
								<i class="fa fa-times"></i> &nbsp;
								Remover
							</button>
							<div class="clean"></div>
						</div>
						<div class="clean"></div>
					</div>
					<?php
					$i++;
				}
			}
		?>
		<div class="clean"></div>
	</div>

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<div class="checkbox">
				<?php 
					$checked = null;

					if ($report->col_to_group_report != null){
						$checked = "checked";
					}
				?>
			    <label><input type="checkbox" id="check-group" name="check-group" <?=$checked?> >Agrupar</label>
			</div>
			<div id="div-group">
				<label>Por:</label>
				<select class="selectpicker form-control" id="slt-group" name="slt-group">
					<?php
					foreach ($columns as $column) {
						$selected = null;
						if ($report->persistent_group_report == $column->name_table . '.' . $column->name_column){
							$selected = "selected";
						}
						?>
						<option data-desc="<?=htmlentities($column->nickname_column)?>" value="<?=$column->name_table . '.' . $column->name_column?>" <?=$selected?> ><?=htmlentities($column->nickname_column)?></option>
						<?php
						$selected = null;
					}
					?>
				</select>
			</div>
		</div>

		<div class="col-xs-12 col-sm-6">
			<div class="checkbox">
				<?php 
				$checked = null;
				if ($report->sum_report == "on"){
					$checked = "checked";
				}
				?>
			    <label><input type="checkbox" id="check-sum" name="check-sum" <?=$checked?> >Mostrar soma das horas</label>
			</div>
		</div>
	</div>

	<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
	<input type="hidden" name="id-user" value="<?=$user->getIdUser()?>">
	<input type="hidden" name="id-report" value="<?=$report->id_report?>">

	<div class="row">
		<div class="col-xs-12 form-group">
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<button type="submit" class="btn btn-lg btn-success" id="btn-submit">
				<i class="fa fa-file"></i> &nbsp;
				Salvar e Gerar
			</button>
			<a href="gerenciar-relatorios.php" class="btn btn-lg btn-danger">
				<i class="fa fa-times"></i> &nbsp;
				Cancelar
			</a>
		</div>
	</div>
</form>

</div> <!-- //content // -->

<?php 
	require 'scripts/main-script.php';
	require 'scripts/animate.php';
	require 'scripts/bootstrap-select.php';
	require 'scripts/jquery-ui.php';
	require 'scripts/ajax-form.php';
	require 'scripts/bootstrap-notify.php';

	$user_name = $user->getName();

?>

<script>
$(document).ready(function(){

	function thisMonth(){
		$("#slt-date-from").val("<?php echo $this_month[0]; ?>");
		$("#slt-date-to").val("<?php echo $this_month[1]; ?>");
	}

	function lastMonth(){
		$("#slt-date-from").val("<?php echo $last_month[0]; ?>");
		$("#slt-date-to").val("<?php echo $last_month[1]; ?>");
	}

	$("#slt-date").on("change", function(event){
		if($(this).val() == "tb_timekeeping.current_month" || $(this).val() == "tb_timekeeping.last_month"){
			$(".date-input").attr("disabled", "true");
			$(".date-input").addClass("date-disabled");
			if ($(this).val() == "tb_timekeeping.current_month"){
				thisMonth();
			} else {
				lastMonth();
			}
		} else {
			$(".date-input").removeAttr("disabled");
			$(".date-input").removeClass("date-disabled");
		}
	});

	var drag_val;
	$('#slt-columns').multiSelect({ 
    	afterInit: function (value, text){
    		var ordered_cols = $("#slt-columns").val();
			$("#multiple_value").val(ordered_cols.join());
    	},
    	afterSelect: function(value, text){
            $("#multiple_value").val("");
			console.clear();
			$(".ms-selection .ms-selected").each(function(){
				drag_val = $("#multiple_value").val();
				$("#multiple_value").val(drag_val + $(this).data("ms-value") + ",");
			});
			console.log($("#multiple_value").val());
      	},
      	afterDeselect: function(value, text){
            $("#multiple_value").val("");
			console.clear();
			$(".ms-selection .ms-selected").each(function(){
				drag_val = $("#multiple_value").val();
				$("#multiple_value").val(drag_val + $(this).data("ms-value") + ",");
			});
			console.log($("#multiple_value").val());
      	},
    	keepOrder: true 
    });

	<?php 
	echo "var json_column_order = " . json_encode($current_columns) . ";";
	?>
	var order = 0;

	$.each(json_column_order, function(index, value){
		$("#slt-columns > option").each(function(){
			if (value == $(this).val()){
				$('#slt-columns').multiSelect('select', value);
			}
		});
	});

    $(".ms-selection .ms-list").sortable({
		update: function(event, ui){
			$("#multiple_value").val("");
			console.clear();
			$(".ms-selection .ms-selected").each(function(){
				drag_val = $("#multiple_value").val();
				$("#multiple_value").val(drag_val + $(this).data("ms-value") + ",");
			});
			console.log($("#multiple_value").val());
		} ,
		helper: "clone"
	});
	
	$(".ms-selection .ms-list").disableSelection();

	$btnSubmit = $("#btn-submit");

	$btnSubmit.click(function(event){
		event.preventDefault();
		<?php
			if ($user->checkProfile(array(1))){
		?>
			$("#slt-condition-1").removeAttr("disabled");
			$("#slt-criteria-1").removeAttr("disabled");
			$("#slt-connector-1").removeAttr("disabled");
			$("#txt-parameter-1").removeAttr("disabled");
			$(".selectpicker").selectpicker("refresh");
			$("#slt-view").removeAttr("disabled");
		<?php 
		}
		?>
		$("#form-report").ajaxSubmit({
			url : 'p-editar-relatorio.php' ,
			type : 'POST' ,
			success : function(status){
				if (status == "empty"){
					$.notify({
						// options
						message: 'Preencha todos os campos obrigatórios!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else if (status == "hours"){
					$.notify({
						// options
						message: 'Você deve exibir o campo de horas para somá-lo!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else if (status == 'group'){
					$.notify({
						// options
						message: 'Você deve exibir o campo que deseja agrupar no relatório!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else if (status == 'block') {
					$.notify({
						// options
						message: 'Consultores não podem alterar o representante!' 
					},{
						// settings
						type: 'danger' ,
						placement: {
							from: "bottom",
							align: "right"
						}
					});
				} else {
					var data = JSON.parse(status);
					window.location = "exibir-relatorio.php?token=" + data["token"] + "&lastid=" + data['id'];
				}

				<?php
					if ($user->checkProfile(array(1))){
				?>
					$("#slt-condition-1").attr("disabled", "true");
					$("#slt-criteria-1").attr("disabled", "true");
					$("#slt-connector-1").attr("disabled", "true");
					$("#slt-view").attr("disabled", "true");
				<?php 
				}
				?>
			}
		});
	});

	//Adicionando novos filtros
	$btnAddFilter = $("#btn-add-filter");
	$btnDeleteFilter = $(".btn-delete-filter");
	$divFilter = $("#div-filter");
	$divFilterContent = $("#div-filter-content");
	//CLASSEEEEEE
	<?php 
	if ($report->json_filters_report != ""){
		echo "var index = " . $i;
	} else {
		echo "var index = 1";
	}
	?>

	var user_name = "<?php echo $user_name; ?>";

	<?php
	if ($user->checkProfile(array(1))){
	?>
		$("#btn-delete-filter-1").remove();
		$("#slt-condition-1").attr("disabled", "true");
		$("#slt-criteria-1").attr("disabled", "true");
		$("#slt-connector-1").attr("disabled", "true");
		$("#txt-parameter-1").attr("readonly", "true");

		$divFilterContent.find(".slt-condition").find("option[value='tb_users.name']").remove();

	<?php 
	}
	?>

	$divFilter.on("click" , ".btn-delete-filter" , function(event){
		event.preventDefault();
		index--;
		$dataDelete = $(this).data('delete');
		$("#div-filter-content-" + $dataDelete).fadeOut(function(){
			$(this).remove();
		});
	});
	
	$divFilter.on("change", ".slt-condition", function(event) {
			$index = $(this).data("counter");
			$.ajax({
				type: 'post',
				url: 'p-dynamic-columns.php',
				data: {
					column: $(this).val()
				},
				success: function(response) {
					response = JSON.parse(response);
					$(".param-"+$index).selectpicker('destroy');
					if (response !== "null") {
						$options = "";
						response.forEach(function(value) {
							$options = $options + "<option value='" + value + "'>" + value + "</option>"
						});
						$("#txt-parameter-" + $index).replaceWith(`
							<select class="form-control txt-parameter selectpicker param-`+$index+`" data-live-search="true" id="txt-parameter-` + $index + `" name="txt-parameter[]" >
								` +
								$options
								+ `
							</select>
						`);
						$(".selectpicker").selectpicker();
					} else {
						$("#txt-parameter-" + $index).replaceWith(`
							<input type="text" class="form-control txt-parameter" id="txt-parameter-` + $index + `" name="txt-parameter[]">
						`);
					}
				}
			})
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
		$newFilter.find(".slt-condition").attr("data-counter" , index);
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


	$( function() {
		$( ".datepicker" ).datepicker({
  			changeMonth: true,
  			changeYear: true
		});
	});

	$.datepicker.regional['pt-BR'] = {
        closeText: 'Fechar',
        prevText: '&#x3c;Anterior',
        nextText: 'Pr&oacute;ximo&#x3e;',
        currentText: 'Hoje',
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
        'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
        'Jul','Ago','Set','Out','Nov','Dez'],
        dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);

    //animação do agrupar
    $("#check-group").click(function(){
    	if ($(this).is(':checked')){
    		$("#div-group").fadeIn();
    	} else {
    		$("#div-group").fadeOut();
    	}
    });

    var column_value;
    var index;
    var description;
    
    $("#btn-select-all").click(function(event){
    	event.preventDefault();
    	$("#slt-view").selectpicker("selectAll");
    });

    $("#btn-remove-all").click(function(event){
    	event.preventDefault();
    	$("#slt-view").selectpicker("deselectAll");
    });
});

</script>
<script src="plugins/select-multiple/js/jquery.multi-select.js" type="text/javascript"></script>

<?php
	require 'footers/main-footer.php';
?>