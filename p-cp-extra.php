<?php 

// Arquivo de processamento

require 'core/initializer.php';

$type = $_POST['slt-type'];
$date = $_POST['txt-date'];
$entry_time = $_POST['txt-entry'];
$exit_time = $_POST['txt-exit'];
$id_user = $_POST['id-user'];
$justification = $_POST['txt-justification'];

if (Validator::isEmpty(array($type, $date, $entry_time, $exit_time, $id_user, $justification))) {
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

// if ($user->checkProfile(array(1)) && $date != Date('Y-m-d')) {
//     $response = array(
//         'status' => 'failed',
//         'msg' => 'Apontamentos devem ser feitos diariamente!'
//     );
//     echo json_encode($response);
//     exit();
// }

$db = Database::getInstance();
$db->query(
    "
    SELECT
        COUNT(*)
    FROM
        tb_cp_timekeeping
    WHERE
        id_user = ?
    AND
        date_cp_timekeeping = ?
    AND
        is_extra = 1
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

$hours_worked = ($exit_time - $entry_time);

if ($hours_worked > 14) {
    $response = array(
        'status' => 'failed',
        'msg' => 'Horas extras devem ser apontadas separadamente!'
    );
    echo json_encode($response);
    exit();
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
    ,   null
    ,   null
    ,   ?
    ,   DEFAULT
    ,   1
    ,   ?
    ,   0
    )
    "
    ,
    array(
        $id_user
    ,   $type
    ,   $date
    ,   $entry_time
    ,   $exit_time
    ,   $justification
    )
);

$response = array(
    'status' => 'success',
    'msg' => 'Apontamento realizado com sucesso!'
);
echo json_encode($response);
