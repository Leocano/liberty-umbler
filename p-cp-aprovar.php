<?php 
// Arquivo de processamento

require 'core/initializer.php';

$id = $_POST['id_cp'];

$db = Database::getInstance();

$db->query(
    "
    UPDATE
        tb_cp_timekeeping
    SET
        approved = 1
    WHERE
        id_cp = ?
    "
    ,
    array(
        $id
    )
);

$response = array(
    'msg' => 'Apontamento aprovado com sucesso!'
);
echo json_encode($response);