<?php 

// Arquivo de processamento

require 'core/initializer.php';

$id_user = $_POST['id-user'];

$history_id = $_POST['id_ticket'];

$id_timekeeping = $_POST['id-edit-timekeeping'];
$description = $_POST['txt-desc'];

$date = $_POST['txt-date'];

$date = str_replace("/","-",$date);
$date = strtotime($date);
$date = date('Y-m-d', $date);

$date = $date . " 00:00:00";

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
    ,   date_timekeeping = ?
    WHERE
        id_timekeeping = ?
    ",
    array(
        $description
    ,   $date
    ,   $id_timekeeping
    )
);

$dao = new HistoryDAO;
$id_user = $_SESSION['user']->getIdUser();
$dao->insertHistoryProduct($history_id, $id_user, "Apontamento de horas editado por");

echo "success";