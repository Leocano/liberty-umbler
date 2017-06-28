<?php

// Arquivo de processamento

require 'core/initializer.php';

$id_report = $_POST['slt-report'];
$slt_frequency = $_POST['slt-frequency'];

$dao = new ScheduledReportDAO;
$dao->scheduleReport($id_report, $slt_frequency);

Redirect::to("agendar-relatorio.php");
