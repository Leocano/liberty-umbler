<?php 

// Arquivo de processamento

require 'core/initializer.php';

$type = $_POST['slt-type'];
$date = $_POST['txt-date'];
$entry_time = $_POST['txt-entry'];
$exit_time = $_POST['txt-exit'];
$break_start = $_POST['txt-break-start'];
$break_finish = $_POST['txt-break-finish'];
$id_user = $_POST['id-user'];
$id_cp = $_POST['txt-id-cp'];
$extra_start = $_POST['txt-extra-start'];
$extra_end = $_POST['txt-extra-end'];
$justification = $_POST['txt-justification'];
$check_extra = $_POST['check-extra'];
$approved = 1;

if (Validator::isEmpty(array($type, $date, $entry_time, $exit_time, $break_start, $break_finish, $id_user))) {
    $response = array(
        'status' => 'failed',
        'msg' => 'Preencha todos os campos!'
    );
    echo json_encode($response);
    exit();
}

if($check_extra == true) {
    $approved = 0;
    if (Validator::isEmpty(array($extra_start, $extra_end, $justification))) {
        $response = array(
            'status' => 'failed',
            'msg' => 'Preencha todos os campos!'
        );
        echo json_encode($response);
        exit();
    }
} else {
    $extra_start = '00:00:00';
    $extra_end = '00:00:00';
    $justification = null;
}

$date = str_replace("/", "-", $date);
$date = strtotime($date);
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
$date = Date('Y-m-d', $date);

if ($user->checkProfile(array(1)) && $date != Date('Y-m-d')) {
    $response = array(
        'status' => 'failed',
        'msg' => 'Apontamentos devem ser feitos diariamente!'
    );
    echo json_encode($response);
    exit();
}

$db = Database::getInstance();

// $db->query(
//     "
//     SELECT
//         COUNT(*) as qtd
//     FROM
//         tb_cp_timekeeping
//     WHERE
//         date_cp_timekeeping = ?
//     AND
//         id_user = ?
//     AND
//         is_extra = 0
//     "
//     ,
//     array(
//         $date,
//         $id_user
//     )
// );
// $results = $db->getResults();

// if ($results[0]->qtd >= 1) {
//     $response = array(
//         'status' => 'failed',
//         'msg' => 'É permitido apenas 1 apontamento por dia!'
//     );
//     echo json_encode($response);
//     exit();
// }

$db->query(
    "
    UPDATE
        tb_cp_timekeeping
    SET
        id_user = ?,
        type_cp = ?,
        entry_time = ?,
        break_start = ?,
        break_end = ?,
        exit_time = ?,
        timekeeping_timestamp = CURRENT_TIMESTAMP,
        extra_start = ?,
        extra_end = ?,
        justification = ?,
        approved = ?,
        ip_address = ?
    WHERE
        id_cp = ?
    "
    ,
    array(
        $id_user
    ,   $type
    ,   $entry_time
    ,   $break_start
    ,   $break_finish
    ,   $exit_time
    ,   $extra_start
    ,   $extra_end
    ,   $justification
    ,   $approved
    ,   $_SERVER['REMOTE_ADDR']
    ,   $id_cp
    )
);

$response = array(
    'status' => 'success',
    'msg' => 'Apontamento editado com sucesso!'
);
echo json_encode($response);
