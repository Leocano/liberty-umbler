<?php 

// home.php
//  Página principal do SC

require 'headers/cp-header.php';
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="plugins/clockpicker/src/clockpicker.css">

<div class="col-xs-12 col-sm-6 col-md-4 well well-lg">
	<div class="row">
		<div class="col-xs-12">
			<h2 class="h3">CP</h2>
		</div>
		<div class="col-xs-12">
			<button data-toggle="modal" data-target="#modal-cp-timekeeping" id="btn-cp-add" type="button" class="btn btn-default">
				Novo Apontamento
			</button>
			<button data-toggle="modal" data-target="#modal-cp-report" type="button" class="btn btn-default">
				Folha de Ponto
			</button>
		</div>
	</div>
</div>

<?php
require 'modals/cp-apontamento-modal.php';
require 'modals/cp-relatorio-modal.php';
require 'scripts/main-script.php';
require 'scripts/jquery-ui.php';
require 'scripts/clockpicker.php';
require 'scripts/ajax-form.php';
?>
<script>
	$btn_cp_timekeeping = $("#btn-cp-timekeeping");

	$btn_cp_timekeeping.click(function(event){
		event.preventDefault();
		$("#form-cp-timekeeping").ajaxSubmit({
			url: 'p-cp-apontamento.php',
			type: 'post',
			data: {
				id_user: <?=$user->getIdUser()?>
			},
			success: function(status) {
				console.log(status);
				// status = JSON.parse(status);
			}
		});
	});

	$( function() {
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	});

	$( function() {
		$( ".monthpicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'mm/yy',
			onClose: function(dateText, inst) { 
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
			}
		});
	});

	$.datepicker.regional['pt-BR'] = {
		closeText: 'Fechar',
		prevText: '&#x3c;Anterior',
		nextText: 'Pr&oacute;ximo&#x3e;',
		currentText: 'Hoje',
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
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
	$('.clockpicker').clockpicker({
		donetext: 'Pronto!',
		autoclose: true
	});
</script>
<?php
require 'footers/main-footer.php';
?>