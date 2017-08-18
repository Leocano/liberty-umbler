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

// if ($) {
//     $response = array();
// }

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

$db = Database::getInstance();

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
    ,   1
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
    )
);

$response = array(
    'status' => 'success',
    'msg' => 'Apontamento realizado com sucesso!'
);
echo json_encode($response);
