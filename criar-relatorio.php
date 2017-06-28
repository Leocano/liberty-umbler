<?php 

// criar-relatorio.php

require 'headers/main-header.php';

Token::generateToken();
$token = $_SESSION['token'];

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

//Pegar colunas para o select
$dao = new ViewReportDAO;
$views = $dao->getAllViews();

$id_user = $user->getIdUser();

?>
<link href="plugins/select-multiple/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="row">
	<div class="col-xs-12 title">
		<h1>Criar Relatório</h1>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 subtitle">
		<h2 class="h3">Relatório</h2>
	</div>
</div>

<form id="form-report" name="form-report" method="post" action="p-criar-relatorio.php">

	<div class="row">
		<div class="col-xs-12 col-sm-6 form-group">
			<label>Nome do Relatório *</label>
			<input class="form-control" type="text" name="txt-name-report" placeholder="Digite o nome do relatório" required>
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

				foreach ($consultants as $consultant) {
					$checked = "";
					$disabled_option = "";
					if ($consultant->id_user == $id_user && $user->checkProfile(array(1))){
						$checked = "selected";
						$disabled_option = "disabled";
					}
					?>
					<option value="<?=$consultant->id_user?>" <?=$disabled_option?> <?=$checked?> ><?=$consultant->name?></option>
					<?php
				}
				?>
			</select>
			<?php 
			if ($user->checkProfile(array(2, 3))){
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
					foreach ($companies as $company) {
						?>
						<option value="<?=$company->id_company?>"><?=$company->name_company?></option>
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
			<label>Colunas *</label>
			<select multiple="multiple" id="slt-columns" name="slt-columns[]" required>
				<?php 

				foreach ($columns as $column) {
					?>
					<!-- <option ondrop="drop(event)" ondragover="allowDrop(event)" ondragstart="drag(event)" draggable="true" data-desc="<?=htmlentities($column->nickname_column)?>" value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option> -->
					<option data-desc="<?=htmlentities($column->nickname_column)?>" value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
					<?php
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
					?>
					<option value="<?=$column->name_table . '.' . $column->name_column?>"><?=htmlentities($column->nickname_column)?></option>
					<?php
				}
				?>
				<option onclick="thisMonth();" value="tb_timekeeping.current_month">Apontamentos do mês atual</option>
				<option onclick="lastMonth();" value="tb_timekeeping.last_month">Apontamentos do mês anterior</option>
			</select>
		</div>
		<div class="col-xs-12 col-sm-4 form-group">
			<label>De *</label>
			<input class="datepicker date-input form-control" type="text" id="slt-date-from" name="slt-date-from" placeholder="Clique para alterar" readonly>
		</div>	
		<div class="col-xs-12 col-sm-4 form-group">
			<label>Até *</label>
			<input class="datepicker date-input form-control" type="text" id="slt-date-to" name="slt-date-to" placeholder="Clique para alterar" readonly>
		</div>	

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
			<div class="clean"></div>
		</div>
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
			    <label><input type="checkbox" id="check-group" name="check-group" checked="true">Agrupar</label>
			</div>
			<div id="div-group">
				<label>Por:</label>
				<select class="selectpicker form-control" id="slt-group" name="slt-group">
					<!-- <option data-desc="Mês" value="tb_timekeeping.month_timekeeping" >Mês</option> -->
					<?php
					foreach ($columns as $column) {
						?>
						<option data-desc="<?=htmlentities($column->nickname_column)?>" value="<?=$column->name_table . '.' . $column->name_column?>" ><?=htmlentities($column->nickname_column)?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>

		<div class="col-xs-12 col-sm-6">
			<div class="checkbox">
			    <label><input type="checkbox" id="check-sum" name="check-sum">Mostrar soma das horas</label>
			</div>
		</div>
	</div>

	<input type="hidden" name="token" value="<?=$_SESSION['token']?>">
	<input type="hidden" name="id-user" value="<?=$user->getIdUser()?>">

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
			<!-- <input type="submit" name=""> -->
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


	$this_month = array(
		Date('01/m/Y'),
		Date('t/m/Y')
	);

	$last_month = array(
		Date('01/m/Y', strtotime("now -1 month")),
		Date('t/m/Y', strtotime("now -1 month"))
	);

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

		//Adicionando novos filtros
		$btnAddFilter = $("#btn-add-filter");
		$btnDeleteFilter = $(".btn-delete-filter");
		$divFilter = $("#div-filter");
		$divFilterContent = $("#div-filter-content");
		$btnSubmit = $("#btn-submit");
		var index = 1;
		var user_name = "<?php echo $user_name; ?>";

		<?php
		if ($user->checkProfile(array(1))){
		?>
			$newFilter = $divFilterContent.clone();
			$newFilter.attr("id" , "div-filter-content-" + index);
			$newFilter.attr("data-delete" , index);
			$newFilter.find(".slt-condition").attr("id" , "slt-condition-" + index);
			$newFilter.find(".slt-condition").attr("name" , "slt-condition[]");
			// $newFilter.find(".slt-condition").addClass("disabled");
			$newFilter.find(".slt-condition").attr("disabled" , "true");
			$newFilter.find(".slt-condition").val("tb_users.name");
			$newFilter.find(".slt-criteria").attr("id" , "slt-criteria-" + index);
			$newFilter.find(".slt-criteria").attr("name" , "slt-criteria[]");
			$newFilter.find(".slt-criteria").attr("disabled" , "true");
			// $newFilter.find(".slt-criteria").addClass("disabled");
			$newFilter.find(".txt-parameter").attr("id" , "txt-parameter-" + index);
			$newFilter.find(".txt-parameter").attr("name" , "txt-parameter[]");
			$newFilter.find(".txt-parameter").attr("readonly" , "true");
			// $newFilter.find(".txt-parameter").addClass("disabled");
			$newFilter.find(".txt-parameter").val(user_name);
			$newFilter.find(".slt-connector").attr("id" , "slt-connector-" + index);
			$newFilter.find(".slt-connector").attr("name" , "slt-connector[]");
			$newFilter.find(".slt-connector").attr("disabled" , "true");
			// $newFilter.find(".slt-connector").addClass("disabled");

			$divFilterContent.find(".slt-condition").find("option[value='tb_users.name']").remove();

			$newFilter.appendTo("#div-filter").removeClass("hidden");
			$newFilter.find(".btn-delete-filter").remove();
			index++;
		<?php 
		}
		?>

		$btnSubmit.click(function(event){
			event.preventDefault();
			<?php
				if ($user->checkProfile(array(1))){
			?>
				$("#slt-condition-1").removeAttr("disabled");
				$("#slt-criteria-1").removeAttr("disabled");
				$("#slt-connector-1").removeAttr("disabled");
				$("#slt-view").removeAttr("disabled");
			<?php 
			}
			?>
			$("#form-report").ajaxSubmit({
				url : 'p-criar-relatorio.php' ,
				type : 'POST' ,
				success : function(status){
					// alert(status);
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
					} else {
						// alert(status);
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

        var drag_val;

        $('#slt-columns').multiSelect({ 
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

  	});

</script>
<script src="plugins/select-multiple/js/jquery.multi-select.js" type="text/javascript"></script>

<?php
	require 'footers/main-footer.php';
?>