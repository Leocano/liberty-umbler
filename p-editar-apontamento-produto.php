<?php 

// Arquivo de processamento

require 'core/initializer.php';

$id_user = $_POST['id-user'];

$history_id = $_POST['id_ticket'];

$id_timekeeping = $_POST['id-edit-timekeeping'];
$description = $_POST['txt-desc'];

$dao = new TimekeepingDAO;

if (Validator::isEmpty(array($description))){
	echo "Preencha todos os campos";
	exit;
}

$description = str_replace('"', "&quot;", $description);
$description = str_replace("'", "&quot;", $description);

$db->query(
    "
    UPDATE
        tb_product_timekeeping
    SET
        desc_timekeeping = ?
    ,   date_timekeeping = CURRENT_TIMESTAMP
    WHERE
        id_timekeeping = ?
    ",
    array(
        $description
    ,   $id_timekeeping
    )
);

$dao = new HistoryDAO;
$id_user = $_SESSION['user']->getIdUser();
$dao->insertHistoryProduct($history_id, $id_user, "Apontamento de horas editado por");

echo "success";