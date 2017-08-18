<?php 

// Arquivo de processamento

require 'core/initializer.php';

$type = $_POST['slt-type'];
$date = $_POST['txt-date'];
$entry_time = $_POST['txt-entry'];
$exit_time = $_POST['txt-exit'];
$break_start = $_POST['txt-break-start'];
$break_finish = $_POST['txt-break-finish'];

if (Validator::isEmpty(array($type, $date, $entry_time, $exit_time))) {
    $response = array(
        'status' => 'failed',
        'msg' => 'Preencha todos os campos!'
    );
    echo json_encode($response);
    exit();
}

$date = str_replace("/", "-", $date);
$date = strtotime($date);
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
$date = Date('Y-m-d', $date);

$db = Database::getInstance();
$db->query(
    "
    SELECT
        entry_time,
        exit_time,
        break_start,
        break_finish
    FROM
        tb_cp_timekeeping
    WHERE
        id_user = ?
    AND
        date_cp_timekeeping = ?
    "
    ,
    array(
        $id_user,
        $date
    )
);

$time = $db->getResults();

if ($time[0] != null) {
    $response = array(
        'status' => 'failed',
        'msg' => 'É permitido apenas 1 apontamento por dia!'
    );
    echo json_encode($response);
    exit();
}

$hours_worked = ($exit_time - $entry_time) - ($break_finish - $break_start);

if ($hours_worked > 8) {
    $approved = 0;
} else {
    $approved = 1;
}

$db->query(
    "
    INSERT INTO
        tb_cp_timekeeping
    VALUES (
        null
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    ,   ?
    )
    "
    ,
    array(
        $id_user
    ,   $type
    ,   $date
    ,   $entry_time
    ,   $exit_time
    ,   $break_start
    ,   $break_finish
    ,   $approved
    )
);

$response = array(
    'status' => 'success',
    'msg' => 'Apontamento realizado com sucesso!'
);
echo json_encode($response);
